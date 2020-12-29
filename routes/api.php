<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:web')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/telegram/webhook', 'TelegramController@handleWebhook');

Route::middleware('auth:web')->get('/spotify/user/favourite_year', 'SpotifyController@getFavouriteYear');
Route::middleware('auth:web')->get('/spotify/user/average_bpm', 'SpotifyController@getAverageBPM');
Route::middleware('auth:web')->get('/spotify/user/average_session_length', 'SpotifyController@getAverageSessionLength');
Route::middleware('auth:web')->get('/spotify/user/track_count', 'SpotifyController@getTrackCount');
Route::middleware('auth:web')->get('/spotify/user/top_artists/{time_from?}/{time_to?}/{limit?}', 'SpotifyController@getTopArtists');
Route::middleware('auth:web')->get('/spotify/user/top_tracks/{time_from?}/{time_to?}/{limit?}', 'SpotifyController@getTopTracks');
Route::middleware('auth:web')->get('/spotify/user/playtime/{time_from?}/{time_to?}', 'SpotifyController@getPlaytime');
Route::middleware('auth:web')->get('/spotify/user/last_played', 'SpotifyController@getLastPlayed');


