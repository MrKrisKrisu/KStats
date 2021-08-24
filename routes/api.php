<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;

Route::post('/telegram/webhook', 'TelegramController@handleWebhook');

Route::middleware('auth:web')->get('/spotify/user/favourite_year', 'SpotifyController@getFavouriteYear');
Route::middleware('auth:web')->get('/spotify/user/average_bpm', 'SpotifyController@getAverageBPM');
Route::middleware('auth:web')->get('/spotify/user/average_session_length', 'SpotifyController@getAverageSessionLength');
Route::middleware('auth:web')->get('/spotify/user/track_count', 'SpotifyController@getTrackCount');
Route::middleware('auth:web')->get('/spotify/user/top_artists/{time_from?}/{time_to?}/{limit?}', [SpotifyController::class, 'getTopArtists']);
Route::middleware('auth:web')->get('/spotify/user/top_tracks/{time_from?}/{time_to?}/{limit?}', [SpotifyController::class, 'getTopTracks']);
Route::middleware('auth:web')->get('/spotify/user/playtime/{time_from?}/{time_to?}', 'SpotifyController@getPlaytime');
Route::middleware('auth:web')->get('/spotify/user/last_played', 'SpotifyController@getLastPlayed');


