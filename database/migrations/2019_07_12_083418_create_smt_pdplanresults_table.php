<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtPdplanresultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_pdplanresults', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('suoshuriqi')->comment('所属日期');
            $table->string('xianti', 20)->comment('线体');
            $table->string('banci', 10)->comment('班次');
			$table->string('jizhongming', 50)->comment('机种名');
			$table->string('spno', 50)->comment('SP NO.');
			$table->string('pinming', 50)->comment('品名');
			$table->string('gongxu', 20)->comment('工序');
			$table->integer('lotshu')->unsigned()->comment('LOT数');
			$table->integer('jihuachanliang')->unsigned()->comment('计划产量');
            $table->timestamps();
            $table->unique(['suoshuriqi', 'xianti', 'banci', 'jizhongming', 'spno', 'pinming', 'gongxu']);
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
        Schema::dropIfExists('smt_pdplanresults');
    }
}
