<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid');
            $table->index('uid');
            $table->string('role',20);
            $table->index('role');
            $table->string('ip',50);
            $table->index('ip');
            $table->timestamp('starttime', $precision = 3)->nullable();
            $table->timestamp('endtime', $precision = 3)->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};
