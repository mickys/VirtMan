<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVirtmanNodesTableAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('virtman_nodes', function (Blueprint $table) {
            $table->longText('routing')->nullable();
            $table->longText('xml')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('virtman_nodes', function (Blueprint $table) {
            $table->dropColumn([
                'routing', 'xml'
            ]);
        });

    }
}
