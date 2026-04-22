<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Explicitly define the table name
    protected $table = 'settings';

    // Allow mass assignment for these fields
    protected $fillable = [
        'setting_key',
        'value',
    ];

    /**
     * Helper: get a setting by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('setting_key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Helper: set or update a setting
     *
     * @param string $key
     * @param mixed $value
     * @return \App\Models\Setting
     */
    public static function setValue(string $key, $value)
    {
        return static::updateOrCreate(
            ['setting_key' => $key],
            ['value' => $value]
        );
    }
}
