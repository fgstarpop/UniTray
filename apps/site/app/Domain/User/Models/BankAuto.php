<?php

declare(strict_types=1);

namespace App\Domain\User\Models;

use App\Domain\Model;
use App\User;

class BankAuto extends Model
{
    protected $guarded = [];
    protected $table = 'bank_auto';
    protected $connection = 'dbuser';
    public static function boot()
    {
        parent::boot();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 }
