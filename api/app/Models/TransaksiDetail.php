<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $table = 'transaksi_detail';
    protected $guarded = [];
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function ulasan()
    {
        return $this->belongsTo(Ulasan::class, 'id_menu', 'id_menu');
    }

    
}
