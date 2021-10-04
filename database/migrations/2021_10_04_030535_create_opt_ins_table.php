<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opt_ins', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('person_id');
            $table->text('source_description');
            $table->string('program')->nullable();
            $table->unsignedInteger('message_id')->nullable();
            $table->string('opt_out_id')->nullable();
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
        Schema::dropIfExists('opt_ins');
    }
}
