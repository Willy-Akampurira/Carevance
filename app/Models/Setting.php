<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'setting_key',
        'value',
    ];

    public $timestamps = false; // optional, since settings rarely need timestamps

    /**
     * Get a setting by key.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('setting_key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set or update a setting.
     */
    public static function setValue(string $key, $value)
    {
        return static::updateOrCreate(
            ['setting_key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Bulk update multiple settings at once.
     */
    public static function setValues(array $settings)
    {
        foreach ($settings as $key => $value) {
            static::setValue($key, $value);
        }
    }
}
