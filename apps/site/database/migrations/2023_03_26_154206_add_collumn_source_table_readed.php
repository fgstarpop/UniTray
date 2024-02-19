<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumnSourceTableReaded extends Migration
{
    public function up()
    {
        if (!Schema::connection('dbuser')->hasColumn('readed', 'source')) {
            Schema::connection('dbuser')->table('readed', function (Blueprint $table) {
                $table->text('source')->nullable(true);
            });
        }
    }
}
