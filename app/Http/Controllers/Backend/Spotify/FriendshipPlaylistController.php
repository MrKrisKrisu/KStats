<?php

namespace App\Http\Controllers\Backend\Spotify;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use App\SpotifyTrack;
use Illuminate\Support\Collection;

abstract class FriendshipPlaylistController extends Controller {

    public static function getCommonTracks(User $user, User $friend) {

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

}
