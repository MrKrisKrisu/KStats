<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Meter;
use App\Models\MeterReading;

class MeterSeeder extends Seeder {

    public function run(): void {
        $user = User::find(1);

        $totalValue = rand(8765400, 7654300) / 100;

        $meter = Meter::factory(['user_id' => $user->id])->create();
        for($i = 0; $i < 10; $i++) {
            $totalValue -= rand(100, 9900) / 100;

            MeterReading::factory()->create([
                                                'meter_id'   => $meter->id,
                                                'reading_at' => now()->subDays($i),
                                                'value'      => $totalValue,
                                            ]);
        }
    }
}
