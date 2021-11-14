<?php

use App\Http\Controllers\CrowdsourceController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\Frontend\Receipt\Grocy\ApiController;
use App\Http\Controllers\Frontend\Receipt\ImportController;
use App\Http\Controllers\Frontend\Spotify\FriendshipPlaylistController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReweController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TwitterController;
use App\Http\Controllers\UnauthorizedSettingsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Settings\LanguageController;

Route::view('/', 'welcome')->name('welcome');

Route::get('/auth/redirect/{provider}', [SocialController::class, 'redirect'])
     ->name('redirectProvider');
Route::get('/auth/callback/{provider}', [SocialController::class, 'callback']);

Auth::routes();

Route::middleware(['auth', 'privacy_confirmation'])->group(function() {

    Route::get('/home/', [HomeController::class, 'index'])->name('home');

    Route::get('/friends', [FriendshipController::class, 'renderFriendshipPage'])->name('friendships');
    Route::post('/friends/module/activate', [FriendshipController::class, 'activateModule'])->name('friendships.module.activate');
    Route::post('/friends/action/cancel', [FriendshipController::class, 'cancelFriendship'])->name('friendships.action.cancel');
    Route::post('/friends/action/request', [FriendshipController::class, 'requestFriendship'])->name('friendships.action.request');

    Route::prefix('settings')->group(function() {
        Route::get('/', [SettingsController::class, 'index'])
             ->name('settings');
        Route::post('/', [SettingsController::class, 'save']);

        Route::post('/user/password/change', [SettingsController::class, 'changePassword'])
             ->name('settings.user.password.change');
        Route::post('/connections/telegram/delete', [SettingsController::class, 'deleteTelegramConnection'])
             ->name('settings.connections.telegram.delete');
        Route::post('/add_mail', [SettingsController::class, 'addEmail'])
             ->name('settings.save.email');
        Route::post('/delete_mail', [SettingsController::class, 'deleteEmail'])
             ->name('settings.delete.email');
        Route::post('/set_lang', [LanguageController::class, 'updateLanguage'])
             ->name('settings.set.lang');
    });

    Route::get('/user/verify_mail/{user_id}/{verification_key}', [UnauthorizedSettingsController::class, 'verifyMail'])
         ->name('user.verify');

    Route::get('/spotify/', [SpotifyController::class, 'index'])
         ->name('spotify');
    Route::get('/spotify/mood-o-meter', [SpotifyController::class, 'renderMoodMeter'])
         ->name('spotify.mood-o-meter');
    Route::get('/spotify/explore', [SpotifyController::class, 'renderExplore'])
         ->name('spotify.explore');
    Route::post('/spotify/explore/submit', [SpotifyController::class, 'saveExploration'])
         ->name('spotify.explore.submit');
    Route::get('/spotify/track/{id}', [SpotifyController::class, 'trackDetails'])
         ->name('spotify.track');
    Route::get('/spotify/artist/{id}', [SpotifyController::class, 'renderArtist'])
         ->name('spotify.artist');
    Route::get('/spotify/history/{date?}', [SpotifyController::class, 'renderDailyHistory'])
         ->name('spotify.history');
    Route::get('/spotify/top-tracks/{term?}', [SpotifyController::class, 'topTracks'])
         ->name('spotify.topTracks');
    Route::get('/spotify/lost-tracks/', [SpotifyController::class, 'lostTracks'])
         ->name('spotify.lostTracks');
    Route::post('/spotify/lost-tracks/', [SpotifyController::class, 'saveLostTracks'])
         ->name('spotify.saveLostTracks');

    Route::get('/spotify/friendship-playlists', [FriendshipPlaylistController::class, 'renderFriendshipPlaylists'])
         ->name('spotify.friendship-playlists');
    Route::post('/spotify/friendship-playlists/create', [FriendshipPlaylistController::class, 'createFriendshipPlaylist'])
         ->name('spotify.friendship-playlists.create');
    Route::get('/spotify/friendship-playlists/{friendId}', [FriendshipPlaylistController::class, 'renderList'])
         ->name('spotify.friendship-playlists.show');

    Route::get('/rewe/', [ReweController::class, 'index'])
         ->name('rewe');
    Route::get('/rewe/receipt/{id}', [ReweController::class, 'renderBonDetails'])
         ->name('rewe_receipt');
    Route::get('/rewe/receipt/download/{id}', [ReweController::class, 'downloadRawReceipt'])
         ->name('download_raw_rewe_receipt');
    Route::get('/rewe/product/{id}', [ReweController::class, 'showProduct'])
         ->name('rewe.product');
    Route::get('/rewe/shop/{id}', [ReweController::class, 'showShop'])
         ->name('rewe.shop');

    Route::prefix('receipt')->group(function() {
        Route::post('/import', [ImportController::class, 'import'])
             ->name('receipt.import.upload');
    });

    Route::get('/crowdsourcing/rewe/', [CrowdsourceController::class, 'renderRewe'])
         ->name('crowdsourcing_rewe');
    Route::post('/crowdsourcing/rewe/', [CrowdsourceController::class, 'handleSubmit']);

    Route::get('/twitter', [TwitterController::class, 'index'])
         ->name('twitter');

    Route::prefix('grocy')->group(function() {
        Route::get('/', [ApiController::class, 'renderOverview'])->name('grocy');
        Route::post('/connect', [ApiController::class, 'connect'])->name('grocy.connect');
        Route::post('/disconnect', [ApiController::class, 'disconnect'])->name('grocy.disconnect');
    });
});

Route::view('/imprint', 'legal.imprint');
Route::view('/privacy', 'legal.privacy_policy')
     ->name('legal.privacy_policy');
Route::post('/privacy/confirm', [SettingsController::class, 'confirmPrivacyPolicy'])
     ->name('legal.privacy_policy.confirm');

Route::post('/' . config('telegram.bots.mybot.token') . '/webhook', [TelegramController::class, 'handleTelegram']);
