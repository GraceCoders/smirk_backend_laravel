<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('block_connections', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('blocked_by')->references('id')->on('users')->onDelete('cascade');
        //     $table->foreignId('blocked_to')->references('id')->on('users')->onDelete('cascade');
        //     $table->tinyInteger('status')->default(1)->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_connections');
    }
}
