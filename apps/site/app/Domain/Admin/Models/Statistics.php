<?php

namespace App\Domain\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    public $guarded = [];
    public $table = 'statistics';
    protected $connection = 'dbuser';
}
