<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtmanMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtman_machines', function(Blueprint $table){
          $table->increments('id');
          $table->string('name');
          $table->string('type');
          $table->string('arch')->default('x86_64');
          $table->unsignedInteger('memory');
          $table->unsignedInteger('cpus');
          $table->string('status')->default('installing');
          $table->string('ip');
          $table->unsignedInteger('node_id');
          $table->string('address');
          $table->date('started_at');
          $table->date('stopped_at');
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
        Schema::dropIfExists('virtman_machines');
    }
}
