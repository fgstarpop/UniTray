<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumnSourceTableFollows extends Migration
{
    public function up()
    {
        if (!Schema::connection('dbuser')->hasColumn('follows', 'source')) {
            Schema::connection('dbuser')->table('follows', function (Blueprint $table) {
                $table->text('source')->nullable(true);
            });
        }
    }
}
