<?php

namespace App\Http\Controllers\Backend\Spotify;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use App\SpotifyTrack;
use Illuminate\Support\Collection;
use App\SpotifyFriendshipPlaylist;
use Illuminate\Database\RecordsNotFoundException;
use Carbon\Carbon;

abstract class FriendshipPlaylistController extends Controller {

    public static function getCommonTracks(User $user, User $friend): Collection {
        $topTracksUser     = self::getTopTrackIds($user, 300);
        $topTracksFriend   = self::getTopTrackIds($friend, 300);
        $likedTracksUser   = self::getLikedTrackIds($user, 300);
        $likedTracksFriend = self::getLikedTrackIds($friend, 300);

        $topTrackIds         = $topTracksUser->intersect($topTracksFriend);
        $bothLikedTrackIds   = $likedTracksUser->intersect($likedTracksFriend);
        $userLikedTrackIds   = $topTracksFriend->intersect($likedTracksUser);
        $friendLikedTrackIds = $topTracksUser->intersect($likedTracksFriend);

        $trackIds = $topTrackIds->union($bothLikedTrackIds)
                                ->union($userLikedTrackIds)
                                ->union($friendLikedTrackIds);

        return SpotifyTrack::whereIn('id', $trackIds)->get();
    }

    private static function getTopTrackIds(User $user, int $limit = 50): Collection {
        return DB::table('spotify_play_activities')
                 ->join('spotify_tracks', 'spotify_tracks.track_id', '=', 'spotify_play_activities.track_id')
                 ->where('user_id', $user->id)
                 ->groupBy('spotify_tracks.id')
                 ->select(['spotify_tracks.id', DB::raw('COUNT(*) AS cnt')])
                 ->orderByDesc('cnt')
                 ->limit($limit)
                 ->get()
                 ->pluck('id');
    }

    private static function getLikedTrackIds(User $user, int $limit = 50): Collection {
        return DB::table('spotify_track_ratings')
                 ->where('user_id', $user->id)
                 ->where('rating', 1)
                 ->select('track_id')
                 ->limit($limit)
                 ->pluck('track_id');
    }

    /**
     * @param User $user
     * @param User $friend
     * @param bool $createIfDoesntExist
     *
     * @return array|object
     */
    public static function getFriendshipPlaylist(User $user, User $friend, bool $createIfDoesntExist = false) {
        $playlistId = SpotifyFriendshipPlaylist::where('user_id', $user->id)
                                               ->where('friend_id', $friend->id)
                                               ->first()?->playlist_id;

        if($playlistId != null) {
            $session = new \SpotifyWebAPI\Session(
                clientId: config('services.spotify.client_id'),
                clientSecret: config('services.spotify.client_secret'),
            );
            $session->setAccessToken($user->socialProfile->spotify_accessToken);

            $api = new \SpotifyWebAPI\SpotifyWebAPI([], $session);
            return $api->getPlaylist($playlistId);
        }

        if($createIfDoesntExist) {
            self::createFriendshipPlaylist($user, $friend);
            return self::getFriendshipPlaylist($user, $friend, false);
        }

        throw new RecordsNotFoundException;
    }

    private static function createFriendshipPlaylist(User $user, User $friend): SpotifyFriendshipPlaylist {
        $session = new \SpotifyWebAPI\Session(
            clientId: config('services.spotify.client_id'),
            clientSecret: config('services.spotify.client_secret'),
        );
        $session->setAccessToken($user->socialProfile->spotify_accessToken);

        $api = new \SpotifyWebAPI\SpotifyWebAPI([], $session);

        $playlist = $api->createPlaylist([
                                             'name'        => 'Freundschaftsplaylist von ' . $user->username . ' und ' . $friend->username,
                                             'description' => 'Freundschaftsplaylist - generiert von KStats auf k118.de',
                                             'public'      => false
                                         ]);

        SpotifyFriendshipPlaylist::updateOrCreate([
                                                      'user_id'   => $user->id,
                                                      'friend_id' => $friend->id,
                                                  ], [
                                                      'playlist_id' => $playlist->id
                                                  ]);

        return self::refreshFriendshipPlaylist($user, $friend);
    }

    /**
     * @param User $user
     * @param User $friend
     *
     * @return SpotifyFriendshipPlaylist
     */
    private static function refreshFriendshipPlaylist(User $user, User $friend): SpotifyFriendshipPlaylist {
        $session = new \SpotifyWebAPI\Session(
            clientId: config('services.spotify.client_id'),
            clientSecret: config('services.spotify.client_secret'),
        );
        $session->setAccessToken($user->socialProfile->spotify_accessToken);

        $api = new \SpotifyWebAPI\SpotifyWebAPI([], $session);

        $friendshipPlaylist = SpotifyFriendshipPlaylist::where('user_id', $user->id)
                                                       ->where('friend_id', $friend->id)
                                                       ->first();

        if($friendshipPlaylist?->playlist_id == null) {
            throw new RecordsNotFoundException();
        }

        $tracks = self::getCommonTracks($user, $friend)->map(function($spotifyTack) {
            return 'spotify:track:' . $spotifyTack->track_id;
        });

        $api->replacePlaylistTracks($friendshipPlaylist?->playlist_id, $tracks->toArray());
        $friendshipPlaylist->update(['last_refreshed' => Carbon::now()]);

        return $friendshipPlaylist;
    }

}
