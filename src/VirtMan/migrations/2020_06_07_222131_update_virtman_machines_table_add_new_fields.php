<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVirtmanMachinesTableAddNewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('virtman_machines', function (Blueprint $table) {
            $table->unsignedTinyInteger('sealed', 0);
            $table->unsignedTinyInteger('archive_detached', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('virtman_machines', function (Blueprint $table) {
            $table->dropColumn([
                'sealed', 'archive_detached'
            ]);
        });

    }
}
