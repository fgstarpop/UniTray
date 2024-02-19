<?php

namespace App\Domain\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Numbers extends Model
{
    public $guarded = [];
    public $table = 'number';
    protected $connection = 'dbuser';
}
