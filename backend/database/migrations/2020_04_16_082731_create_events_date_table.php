<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_date', function (Blueprint $table) {
          $table->id();
          $table->date('date');
          $table->bigInteger('eventsId')->unsigned();
          $table->foreign('eventsId')->references('id')->onDelete('cascade')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_date');
    }
}
