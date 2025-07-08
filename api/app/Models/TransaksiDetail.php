<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $table = 'transaksi_detail';
    protected $guarded = [];
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    
}
