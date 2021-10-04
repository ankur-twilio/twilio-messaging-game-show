<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('game_id');
            $table->unsignedInteger('player_id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('message_id');
            $table->text('answer');
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
        Schema::dropIfExists('answer_records');
    }
}
