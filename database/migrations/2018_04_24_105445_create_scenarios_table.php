<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScenariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scenarios', function (Blueprint $table) {
		$table->increments('id');
		$table->string('name');
		$table->smallInteger('question_id');
		$table->string('question_name');
            $table->timestamps();
        });
	
	Schema::create('scenario_situation', function (Blueprint $table) {
		$table->increments('id');
		$table->smallInteger('situation_id');
		$table->smallInteger('scenario_id');
            $table->timestamps();
        });
	
	Schema::create('field_scenario', function (Blueprint $table) {
		$table->increments('id');
		$table->smallInteger('scenario_id');
		$table->smallInteger('field_id');
		$table->string('value');
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
        Schema::dropIfExists('scenarios');
        Schema::dropIfExists('scenario_situation');
        Schema::dropIfExists('field_scenario');
    }
}
