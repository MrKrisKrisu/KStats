<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::view('/', 'welcome')->name('welcome');
Route::view('/opensource/', 'opensource');

Route::get('/auth/redirect/{provider}', 'SocialController@redirect')
    ->name('redirectProvider');
Route::get('/auth/callback/{provider}', 'SocialController@callback');

Auth::routes();


Route::get('/home/', 'HomeController@index')->name('home');

Route::get('/settings/', 'SettingsController@index')
    ->name('settings');
Route::post('/settings/', 'SettingsController@save');
Route::post('/settings/connections/telegram/delete', 'SettingsController@deleteTelegramConnection')
    ->name('settings.connections.telegram.delete');
Route::post('/settings/add_mail', 'SettingsController@addEmail')->name('settings.save.email');
Route::post('/settings/delete_mail', 'SettingsController@deleteEmail')->name('settings.delete.email');
Route::get('/user/verify_mail/{user_id}/{verification_key}', 'UnauthorizedSettingsController@verifyMail')
    ->name('user.verify');

Route::get('/spotify/', 'SpotifyController@index')
    ->name('spotify');
Route::get('/spotify/track/{id}', 'SpotifyController@trackDetails')
    ->name('spotify.track');
Route::get('/spotify/history/{date?}', 'SpotifyController@renderDailyHistory')
    ->name('spotify.history');
Route::get('/spotify/top-tracks/{term?}', 'SpotifyController@topTracks')
    ->name('spotify.topTracks');
Route::get('/spotify/lost-tracks/', 'SpotifyController@lostTracks')
    ->name('spotify.lostTracks');
Route::post('/spotify/lost-tracks/', 'SpotifyController@saveLostTracks')
    ->name('spotify.saveLostTracks');

Route::get('/rewe/', 'ReweController@index')
    ->name('rewe');
Route::get('/rewe/receipt/{id}', 'ReweController@renderBonDetails')
    ->name('rewe_receipt');
Route::get('/rewe/receipt/download/{id}', 'ReweController@downloadRawReceipt')
    ->name('download_raw_rewe_receipt');

Route::get('/crowdsourcing/rewe/', 'CrowdsourceController@renderRewe')
    ->name('crowdsourcing_rewe');
Route::post('/crowdsourcing/rewe/', 'CrowdsourceController@handleSubmit');

Route::get('/twitter', 'TwitterController@index')
    ->name('twitter');

Route::view('/imprint/', 'imprint');
Route::view('/disclaimer/', 'disclaimer');