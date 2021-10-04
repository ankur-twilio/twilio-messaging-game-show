<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('quick_title');
            $table->text('title');
            $table->string('type');
            $table->json('options')->nullable();
            $table->string('sync_map_item')->nullable();
            $table->boolean('active')->default(0);
            $table->boolean('default_live_answer_display')->default(0);
            $table->boolean('allow_answer_change')->default(1);
            $table->boolean('use_options')->default(1);
            $table->unsignedInteger('allowed_answer_count')->default(1);
            $table->unsignedInteger('game_id');
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
        Schema::dropIfExists('questions');
    }
}
