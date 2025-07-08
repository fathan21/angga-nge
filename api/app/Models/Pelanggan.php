<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $guarded = [];
    protected $primaryKey = 'id_pelanggan';
    public $timestamps = false;
}
