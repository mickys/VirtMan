<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtmanNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'virtman_nodes',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('url');
                $table->string('status')->default('inactive');
                $table->timestamp('last_sync_at')->nullable();
                $table->timestamps();
            }
        );

        Schema::table(
            'virtman_machines', function (Blueprint $table) {
                $table->foreign('node_id')->references('id')->on('virtman_nodes');
            }
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('virtman_nodes');
    }
}
