<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model {

    protected $fillable = [
        'user_id', 'name', 'val'
    ];

    public static function get(int $userID, $key, $defaultVal = null) {
        $setting = UserSettings::where('user_id', $userID)->where('name', $key)->first();
        if($setting !== null)
            return $setting->val;

        if($defaultVal == null)
            return null;

        $setting = UserSettings::create([
                                            'user_id' => $userID,
                                            'name'    => $key,
                                            'val'     => $defaultVal
                                        ]);

        return $setting->val;
    }

    public static function set(int $userID, $key, $val) {
        UserSettings::updateOrCreate(
            [
                'user_id' => $userID,
                'name'    => $key,
            ],
            [
                'val' => $val
            ]
        );
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
