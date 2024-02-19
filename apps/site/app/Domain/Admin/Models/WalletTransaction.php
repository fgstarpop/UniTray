<?php

namespace App\Domain\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class WalletTransaction extends Model
{
    public $table = 'wallet_transactions';
    public $guarded = [];
    protected $connection = 'dbuser';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
 