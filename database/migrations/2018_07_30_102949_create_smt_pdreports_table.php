<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtPdreportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_pdreports', function (Blueprint $table) {
            $table->increments('id');
			$table->string('xianti', 20);
			$table->string('banci', 20);
			$table->string('jizhongming', 50);
			$table->string('spno', 50);
			$table->string('pinming', 50);
			$table->integer('lotshu')->unsigned();
			$table->string('gongxu', 20);
			$table->integer('dianmei')->unsigned();
			$table->integer('meimiao')->unsigned();
			$table->integer('meishu')->unsigned();
			$table->integer('taishu')->unsigned();
			$table->integer('lotcan')->unsigned();
			$table->integer('chajiandianshu')->unsigned();
			$table->float('jiadonglv');
			$table->integer('xinchan')->unsigned()->nullable();
			$table->integer('liangchan')->unsigned()->nullable();
			$table->integer('dengdaibupin')->unsigned()->nullable();
			$table->integer('wujihua')->unsigned()->nullable();
			$table->integer('qianhougongchengdengdai')->unsigned()->nullable();
			$table->integer('wubupin')->unsigned()->nullable();
			$table->integer('bupinanpaidengdai')->unsigned()->nullable();
			$table->integer('dingqidianjian')->unsigned()->nullable();
			$table->integer('guzhang')->unsigned()->nullable();
			$table->integer('bupinbuchong')->unsigned()->nullable();
			$table->integer('shizuo')->unsigned()->nullable();
			$table->text('jizaishixiang')->nullable();
			$table->string('dandangzhe', 20)->nullable();
			$table->string('querenzhe', 20)->nullable();
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
        Schema::dropIfExists('smt_pdreports');
    }
}
