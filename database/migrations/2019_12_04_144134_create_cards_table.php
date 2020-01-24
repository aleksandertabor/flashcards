<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deck_id')->nullable()->index();
            $table->string('question', 255)->nullable();
            $table->string('answer', 255)->nullable();
            $table->string('example_question', 255)->nullable();
            $table->string('example_answer', 255)->nullable();
            $table->timestamps();

            $table->foreign('deck_id')->references('id')->on('decks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
