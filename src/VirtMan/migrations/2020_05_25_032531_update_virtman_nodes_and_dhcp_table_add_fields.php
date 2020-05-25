<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVirtmanNodesAndDhcpTableAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('virtman_nodes', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->integer('resource_vcpus')->nullable();
            $table->integer('resource_memory')->nullable();
            $table->integer('resource_storage')->nullable();
            $table->string('interface_0_name')->default('enp1s0');
            $table->string('interface_0_mac')->nullable();
            $table->string('interface_0_ip')->nullable();
            $table->string('interface_1_name')->default('virbr0');
            $table->string('interface_1_mac')->nullable();
            $table->string('interface_1_ip')->nullable();
            $table->string('interface_1_netmask')->nullable();
            $table->string('interface_1_dhcp_start')->nullable();
            $table->string('interface_1_dhcp_end')->nullable();
        });

        Schema::table('virtman_dhcp', function (Blueprint $table) {
            $table->string('node')->nullable();
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
                'type',
                'resource_vcpus',
                'resource_memory',
                'resource_storage',
                'interface_0_name',
                'interface_0_mac',
                'interface_0_ip',
                'interface_1_name',
                'interface_1_mac',
                'interface_1_ip',
                'interface_1_netmask',
                'interface_1_dhcp_start',
                'interface_1_dhcp_end',
            ]);
        });

        Schema::table('virtman_dhcp', function (Blueprint $table) {
            $table->dropColumn([
                'node',
            ]);
        });

    }
}
