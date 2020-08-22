<?php

namespace App\Console\Commands;

use App\SpotifyDeviceActivity;
use App\SpotifyPlayActivity;
use Illuminate\Console\Command;

class DeviceMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kstats:migratedevice {count=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit = $this->argument("count");
        echo "Start migrating $limit rows...\r\n";
        $devices = SpotifyDeviceActivity::limit($limit)->get();
        foreach ($devices as $device) {
            if ($device->device != NULL) {
                $d = SpotifyPlayActivity::where('user_id', $device->device->user_id)
                    ->where('created_at', '>', $device->created_at->addMinutes(-2))
                    ->where('created_at', '<', $device->created_at->addMinutes(2))
                    ->where('device_id', NULL)
                    ->update([
                        'device_id' => $device->device->id
                    ]);
                echo "* updated $d rows \r\n";
            }
            $device->delete();
        }
        return 0;
    }
}
