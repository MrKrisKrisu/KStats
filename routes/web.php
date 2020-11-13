<?php

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SpotifyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::view('/', 'welcome')->name('welcome');

Route::get('/auth/redirect/{provider}', 'SocialController@redirect')
     ->name('redirectProvider');
Route::get('/auth/callback/{provider}', 'SocialController@callback');

Auth::routes();

Route::middleware(['privacy_confirmation'])->group(function () {

    Route::get('/home/', 'HomeController@index')->name('home');

    Route::get('/settings/', 'SettingsController@index')
         ->name('settings');
    Route::post('/settings/', 'SettingsController@save');
    Route::post('/settings/user/password/change', 'SettingsController@changePassword')->name('settings.user.password.change');
    Route::post('/settings/connections/telegram/delete', 'SettingsController@deleteTelegramConnection')
         ->name('settings.connections.telegram.delete');
    Route::post('/settings/add_mail', 'SettingsController@addEmail')
         ->name('settings.save.email');
    Route::post('/settings/delete_mail', 'SettingsController@deleteEmail')
         ->name('settings.delete.email');
    Route::post('/settings/set_lang', 'SettingsController@setLanguage')
         ->name('settings.set.lang');
    Route::get('/user/verify_mail/{user_id}/{verification_key}', 'UnauthorizedSettingsController@verifyMail')
         ->name('user.verify');

    Route::get('/spotify/', 'SpotifyController@index')
         ->name('spotify');
    Route::get('/spotify/mood-o-meter', [SpotifyController::class, 'renderMoodMeter'])
         ->name('spotify.mood-o-meter');
    Route::get('/spotify/explore', [SpotifyController::class, 'renderExplore'])
         ->name('spotify.explore');
    Route::post('/spotify/explore/submit', [SpotifyController::class, 'saveExploration'])
         ->name('spotify.explore.submit');
    Route::get('/spotify/track/{id}', 'SpotifyController@trackDetails')
         ->name('spotify.track');
    Route::get('/spotify/artist/{id}', [SpotifyController::class, 'renderArtist'])
         ->name('spotify.artist');
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
});

Route::view('/imprint', 'legal.imprint');
Route::view('/privacy', 'legal.privacy_policy')
     ->name('legal.privacy_policy');
Route::post('/privacy/confirm', [SettingsController::class, 'confirmPrivacyPolicy'])
     ->name('legal.privacy_policy.confirm');

Route::post('/' . config('telegram.bots.mybot.token') . '/webhook', function () {
    $updates = Telegram::commandsHandler(true);
    Log::debug($updates);
    return 'ok';
});