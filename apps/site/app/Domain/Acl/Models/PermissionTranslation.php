<?php

namespace App\Domain\Acl\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionTranslation extends Model
{
    public $timestamps = false;
    protected $connection = 'dbuser';
    public $guarded = [];
}
