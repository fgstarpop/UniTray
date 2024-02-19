<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumnSourceTableOrders extends Migration
{
    public function up()
    {
        if (!Schema::connection('dbuser')->hasColumn('orders', 'source')) {
            Schema::connection('dbuser')->table('orders', function (Blueprint $table) {
                $table->text('source')->nullable(true);
            });
        }
    }
}
