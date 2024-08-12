<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            // Define `date` as a timestamp
            $table->timestamp('date');
            // Define `heading` as a text field
            $table->text('heading');
            // Define `url` as a varchar (string) with a specified length
            $table->string('url', 255);
            $table->integer('type');
            // Define `fromdate` and `todate` as timestamps
            $table->timestamp('fromdate');
            $table->timestamp('todate');
            // Define `status` as an integer
            $table->integer('status');
            // Add Laravel's created_at and updated_at timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
};
