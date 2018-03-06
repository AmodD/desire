<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relationships', function (Blueprint $table) {
		$table->increments('id');
		$table->string('name');
            $table->timestamps();
	});


	Schema::create('field_relationship', function (Blueprint $table) {
		$table->integer('field_id');
		$table->integer('relationship_id');
	});

	Schema::create('relationship_transaction', function (Blueprint $table) {
		$table->integer('relationship_id');
		$table->integer('transaction_id');
		$table->integer('label_id');
	});

    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relationships');
        Schema::dropIfExists('field_relationship');
    }
}
