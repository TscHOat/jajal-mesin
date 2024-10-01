<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMesinCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesin_commands', function (Blueprint $table) {
            $table->id();
            $table->string('command');
            $table->foreignId('mesin_absensi_id')->constrained('mesin_absensi')->onDelete('cascade');
            $table->timestamp('transmit_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('response')->nullable();
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
        Schema::dropIfExists('mesin_commands');
    }
}
