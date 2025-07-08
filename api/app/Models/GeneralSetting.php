<?php

namespace App\Models;

use App\Libraries\Tele\TeleTrx;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class GeneralSetting extends Model
{
    protected $table = 'general_settings';
    protected $guarded = [];

    static function getByKey($key)
    {
        $d = self::where('key', $key)->first();
        $value = '';
        if (@$d->id) {
            $value = $d->value;
        }
        return $value;
    }

    static function setByKey($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
    
}
