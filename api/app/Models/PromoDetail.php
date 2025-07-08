<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoDetail extends Model
{
    protected $table = 'promo_detail';
    protected $guarded = [];
    protected $primaryKey = 'id_promo_detail';
    public $timestamps = false;

    

    public function promo()
    {
        return $this->hasOne(Promo::class,'id_promo','id_promo');
    }
}
