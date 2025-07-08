<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promo extends Model
{
    protected $table = 'promo';
    protected $guarded = [];
    protected $primaryKey = 'id_promo';
    public $timestamps = false;

    public function details()  {
         return $this->hasMany(PromoDetail::class,'id_promo','id_promo');
    }
}
