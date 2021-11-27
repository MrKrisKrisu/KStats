<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\TwitterFollower;
use App\Http\Controllers\Backend\Twitter\UnfollowerTrackingController;

/**
 * Currently not used. Prepared for future use.
 */
class CheckTwitterUnfollow implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private TwitterFollower $relationship;

    public function __construct(TwitterFollower $relationship) {
        $this->relationship = $relationship;
    }

    public function handle(): void {
        UnfollowerTrackingController::checkUnfollow($this->relationship);
    }
}
