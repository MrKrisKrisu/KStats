<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{

    protected $fillable = [
        'user_id', 'name', 'val'
    ];

    public static function get(int $userID, $key, $defaultVal = NULL)
    {
        $setting = UserSettings::where('user_id', $userID)->where('name', $key)->first();
        if ($setting !== NULL)
            return $setting->val;

        if ($defaultVal == NULL)
            return NULL;

        $setting = UserSettings::create([
            'user_id' => $userID,
            'name' => $key,
            'val' => $defaultVal
        ]);

        return $setting->val;
    }

    public static function set(int $userID, $key, $val)
    {
        UserSettings::updateOrCreate(
            [
                'user_id' => $userID,
                'name' => $key,
            ],
            [
                'val' => $val
            ]
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
