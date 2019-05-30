<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_days', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->time('time_beg');
            $table->time('time_end');
            $table->tinyInteger('stamp_beg');
            $table->tinyInteger('stamp_end');
            $table->boolean('day_off')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_days');
    }
}
