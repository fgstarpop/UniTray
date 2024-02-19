<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumnSourceTableDonate extends Migration
{
    public function up()
    {
        if (!Schema::connection('dbuser')->hasColumn('donate', 'source')) {
            Schema::connection('dbuser')->table('donate', function (Blueprint $table) {
                $table->text('source')->nullable(true);
            });
        }
    }
}
