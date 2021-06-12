<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpammersTable extends Migration
{

    public function up()
    {
        Schema::create(
            config('honey.spammer_blocking.table_name', 'spammers'),
            function (Blueprint $table) {
                $table->id();
                $table->string('ip_address');
                $table->integer('attempts');
                $table->dateTime('blocked_at')->nullable();
                $table->timestamps();
            }
        );
    }
    
    public function down()
    {
        Schema::dropIfExists(config('honey.spammer_blocking.table_name', 'spammers'));
    }

}
