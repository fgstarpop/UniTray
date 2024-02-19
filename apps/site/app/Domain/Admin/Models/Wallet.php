<?php

namespace App\Domain\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Wallet extends Model
{
    public $guarded = [];
    protected $connection = 'dbuser';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
