<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';
    // protected $guarded = [];

    protected $fillable = [
        'ulasan',
        'rating',
        'id_transaksi',
        'id_menu',
        'tanggal',
        'respon',
        'tanggal_repon',
        'tipe',
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function transaksi()
    {
        return $this->hasOne(Transaksi::class,'id_transaksi','id_transaksi');
    }

    public function menu()
    {
        return $this->hasOne(Menu::class,'id_menu','id_menu');
    }

    
}
