<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_settings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned();
            $table->foreign('tournament_id')
                ->references('id')
                ->on('tournament')
                ->onDelete('cascade');

            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')
                ->references('id')
                ->on('category')
                ->onDelete('cascade');

            $table->unique(array('tournament_id', 'category_id'));
            // General Section

            // Category Section
            $table->tinyInteger('isTeam');
            $table->tinyInteger('teamSize');
            $table->tinyInteger('fightDuration');
            $table->tinyInteger('hasRoundRobin');
            $table->tinyInteger('roundRobinWinner');
            $table->tinyInteger('hasEncho');
            $table->tinyInteger('enchoQty');
            $table->tinyInteger('enchoDuration');
            $table->tinyInteger('hasHantei');

            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_settings');
    }
}
