<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $guarded = [];
    protected $primaryKey = 'id_menu';
    public $timestamps = false;
}
