<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Http\Controllers\Backend\Spotify\FetchController;

class FetchSpotifyLastPlayed implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function handle(): void {
        FetchController::fetchRecentlyPlayed($this->user);
    }
}
