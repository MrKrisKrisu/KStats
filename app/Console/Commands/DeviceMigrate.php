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
        $devices = SpotifyDeviceActivity::limit($this->argument("count"))->get();
        foreach ($devices as $device) {
            SpotifyPlayActivity::where('user_id', $device->device->user_id)
                ->where('created_at', '>', $device->created_at->addMinutes(-2))
                ->where('created_at', '<', $device->created_at->addMinutes(2))
                ->where('device_id', NULL)
                ->update([
                    'device_id' => $device->device->id
                ]);
            $device->delete();
        }
        return 0;
    }
}
