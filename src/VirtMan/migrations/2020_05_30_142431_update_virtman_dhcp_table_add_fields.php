<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVirtmanDhcpTableAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('virtman_dhcp', function (Blueprint $table) {
            $table->integer('http_port')->nullable();
            $table->integer('tusd_port')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('virtman_dhcp', function (Blueprint $table) {
            $table->dropColumn([
                'http_port', 'tusd_port'
            ]);
        });

    }
}
