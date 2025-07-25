<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loyal extends Model
{
    protected $table = 'loyal';
    protected $guarded = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
