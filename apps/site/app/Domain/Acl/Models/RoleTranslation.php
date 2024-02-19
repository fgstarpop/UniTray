<?php

namespace App\Domain\Acl\Models;

use Illuminate\Database\Eloquent\Model;

class RoleTranslation extends Model
{
    public $timestamps = false;
    protected $connection = 'dbuser';
    public $guarded = [];
}
