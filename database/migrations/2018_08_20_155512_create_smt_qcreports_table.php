<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtQcreportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_qcreports', function (Blueprint $table) {
            $table->increments('id');
			// $table->integer('dr_id')->unsigned();
			$table->timestamp('shengchanriqi');
			$table->string('xianti', 20);
			$table->string('banci', 20);
			$table->string('jizhongming', 50);
			$table->string('pinming', 50);
			$table->string('gongxu', 20);
			$table->string('spno', 50);
			$table->integer('lotshu')->unsigned();
			$table->integer('dianmei')->unsigned();
			$table->integer('meishu')->unsigned();
			$table->integer('hejidianshu')->unsigned();
			$table->integer('bushihejianshuheji')->default(0)->unsigned();
			$table->float('ppm')->default(0);
			
			$table->string('buliangneirong', 50)->nullable();
			$table->string('weihao', 50)->nullable();
			$table->integer('shuliang')->unsigned()->nullable();
			$table->string('jianchajileixing', 20)->nullable();
			$table->string('jianchazhe', 20)->nullable();
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
        Schema::dropIfExists('smt_qcreports');
    }
}
