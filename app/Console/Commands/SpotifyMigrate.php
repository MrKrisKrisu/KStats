<?php

namespace App\Console\Commands;

use App\Models\SpotifyPlayActivity;
use Illuminate\Console\Command;

class SpotifyMigrate extends Command {

    protected $signature = 'spotify:migrate  {limit=1000}';

    public function handle(): int {
        $toMigrate = SpotifyPlayActivity::with(['track'])
                                        ->whereNull('trackId')
                                        ->distinct()
                                        ->select('track_id')
                                        ->limit($this->argument('limit'))
                                        ->get();
        foreach($toMigrate as $row) {
            $track = $row->track;
            $rows  = SpotifyPlayActivity::where('track_id', $track->track_id)->update(['trackId' => $track->id]);
            echo strtr('* Updated :rows Rows', [':rows' => $rows]) . PHP_EOL;
        }
        return 0;
    }

}
