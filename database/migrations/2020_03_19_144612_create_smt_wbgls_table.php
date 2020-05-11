<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtWbglsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_wbgls', function (Blueprint $table) {
			$table->increments('id');
			$table->string('wangbanbufan', 50);
			$table->string('jizhongming', 50);
			$table->string('pinming', 50);
			// $table->string('gongxu', 20);
			$table->string('xilie', 50);
			$table->string('bianhao', 10);
			$table->string('wangbanhoudu', 50)->nullable();
			$table->string('teshugongyi', 50)->nullable();
			$table->integer('zhangli1')->unsigned();
			$table->integer('zhangli2')->unsigned();
			$table->integer('zhangli3')->unsigned();
			$table->integer('zhangli4')->unsigned();
			$table->integer('zhangli5')->unsigned();
			$table->string('luruzhe', 20)->nullable();
			$table->string('bianjizhe', 20)->nullable();
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
        Schema::dropIfExists('smt_wbgls');
    }
}
