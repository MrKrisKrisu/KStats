<?php

namespace App\Http\Controllers\Backend\Spotify;

use App\Http\Controllers\Controller;
use App\Models\SpotifyTrack;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;

abstract class SpotifySocialExploreController extends Controller {
    /**
     * @param User $user
     * @throws GuzzleException
     * @todo duplicate code, messy code, ... but currently i'm lazy
     */
    public static function sendExploreSuggestion(User $user) {

        if(!isset($user->socialProfile->telegram_id)) {
            throw new \Exception('not connected to telegram');
        }

        $tracks       = $user->spotifyActivity()->select(['track_id'])->groupBy('track_id');
        $alreadyRated = SpotifyTrack::whereIn('id', $user->spotifyRatedTracks()->select(['track_id']))->select('track_id');

        $friends = $user->friends;

        $trackToExplore = null;

        if($friends->count() > 0) {//} && rand(1, 100) > 40) {
            //Use a popular song from a friend
            $friend          = $friends->random(1)->first();
            $friendTopTracks = $friend->spotifyActivity()
                                      ->where('created_at', '>', Carbon::now()->subWeeks(6)->toDateString())
                                      ->groupBy('track_id')
                                      ->select([
                                                   'track_id',
                                                   DB::raw('COUNT(*) as minutes')
                                               ]);

            $trackToExplore = SpotifyTrack::joinSub($friendTopTracks, 'friends_top_tracks', function($join) {
                $join->on('spotify_tracks.track_id', '=', 'friends_top_tracks.track_id');
            })
                                          ->whereIn('spotify_tracks.track_id', $friendTopTracks->select('track_id'))
                                          ->whereNotIn('spotify_tracks.track_id', $tracks)
                                          ->whereNotIn('spotify_tracks.track_id', $alreadyRated)
                                          ->where('preview_url', '<>', null)
                                          ->orderByDesc('friends_top_tracks.minutes')
                                          ->select(['spotify_tracks.*', 'friends_top_tracks.minutes'])
                                          ->first();

        }
        if($trackToExplore !== null) {
            $trackReason = strtr(__('spotify.explore.reason.friend'), [
                ':friend' => $friend->username
            ]);
        } else {
            //Use a popular song from trends
            $trackToExplore = SpotifyTrack::where('preview_url', '<>', null)
                                          ->whereNotIn('track_id', $tracks)
                                          ->whereNotIn('track_id', $alreadyRated)
                                          ->orderByDesc('popularity')
                                          ->first();
            $trackReason    = __('spotify.explore.reason.trend');
        }

        $message = "*Wie findest du dieses Lied?*\n";
        $message .= "\n";
        $message .= "``` " . $trackToExplore->name . "```\n";
        $message .= "``` von " . $trackToExplore?->artists?->first()?->name . "```\n";
        $message .= "\n";
        $message .= str_replace(["\"", "."], ["\\\"", "\\."], $trackReason);
        $message .= "\n";
        $message .= "\n";
        $message .= strtr('[Lied anhören](:url)', [
            ':url' => $trackToExplore->preview_url
        ]);

        $client = new Client(['base_uri' => 'https://api.telegram.org']);
        $client->post(strtr('/bot:token/sendMessage', [':token' => config('telegram.bots.mybot.token')]), [
            'json' => [
                'chat_id'      => $user->socialProfile->telegram_id,
                'text'         => $message,
                'parse_mode'   => 'MarkdownV2',
                'reply_markup' => [
                    'inline_keyboard' => [
                        [
                            ['text' => 'Gut', 'callback_data' => '/explore 1 ' . $trackToExplore->track_id],
                            ['text' => 'Schlecht', 'callback_data' => '/explore 0 ' . $trackToExplore->track_id],
                            ['text' => 'Überspringen', 'callback_data' => '/explore -1 ' . $trackToExplore->track_id]
                        ]
                    ]],
            ]
        ]);

    }

}
