<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorldCupMatch extends Model
{
    protected $table ="worldcups";
    protected $primaryKey ='uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected  $guarded = [];
}
