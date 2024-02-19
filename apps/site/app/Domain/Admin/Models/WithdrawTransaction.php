<?php

declare(strict_types=1);

namespace App\Domain\Admin\Models;
use Illuminate\Database\Eloquent\Model;

class WithdrawTransaction extends Model
{
    public $guarded = [];
    protected $connection = 'dbuser';
    
}
