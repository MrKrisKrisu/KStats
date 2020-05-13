<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{

    protected $fillable = [
        'user_id', 'name', 'val'
    ];

    public static function get(int $user_id, $key, $defaultVal = NULL)
    {
        $setting = UserSettings::where('user_id', $user_id)->where('name', $key)->first();
        if ($setting !== NULL)
            return $setting->val;

        if ($defaultVal == NULL)
            return NULL;

        $setting = UserSettings::create([
            'user_id' => $user_id,
            'name' => $key,
            'val' => $defaultVal
        ]);

        return $setting->val;
    }

    public static function set(int $user_id, $key, $val)
    {
        UserSettings::updateOrCreate(
            [
                'user_id' => $user_id,
                'name' => $key,
            ],
            [
                'val' => $val
            ]
        );
    }
}
