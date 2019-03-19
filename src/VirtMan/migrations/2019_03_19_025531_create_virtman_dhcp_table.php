<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtmanDhcpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'virtman_dhcp',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('ip')->unique();
                $table->string('mac')->unique();
                $table->string('name')->unique();
                $table->unsignedInteger('parent')->nullable();
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
        Schema::dropIfExists('virtman_dhcp');
    }
}
