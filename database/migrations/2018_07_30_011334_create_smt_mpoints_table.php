<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_mpoints', function (Blueprint $table) {
            $table->increments('id');
			$table->string('jizhongming', 50);
			$table->string('pinming', 50);
			$table->string('mian', 50);
			$table->integer('diantai')->unsigned();
			$table->integer('pinban')->unsigned();
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
        Schema::dropIfExists('mpoints');
    }
}
