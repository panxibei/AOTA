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
			$table->dateTime('shengchanriqi');
			$table->string('xianti', 20);
			$table->string('banci', 20);
			$table->string('jizhongming', 50)->nullable();
			$table->string('spno', 50)->nullable();
			$table->string('pinming', 50)->nullable();
			$table->integer('lotshu')->unsigned()->nullable();
			$table->string('gongxu', 20)->nullable();
			$table->integer('dianmei')->unsigned()->nullable();
			$table->integer('meimiao')->unsigned()->nullable();
			$table->integer('meishu')->unsigned()->nullable();
			$table->integer('shijishengchanshijian')->unsigned()->nullable(); // dianmei * meishu
			$table->integer('shoudongshengchanshijian')->unsigned()->nullable();
			$table->integer('bupinbuchongshijian')->nullable();
			$table->integer('taishu')->unsigned()->nullable();
			$table->integer('lotcan')->unsigned()->nullable();
			$table->integer('chajiandianshu')->unsigned()->nullable();
			$table->float('jiadonglv')->nullable();
			$table->integer('xinchan')->unsigned()->nullable();
			$table->integer('liangchan')->unsigned()->nullable();
			$table->integer('qiehuancishu')->unsigned()->default(0);
			$table->integer('dengdaibupin')->unsigned()->nullable();
			$table->integer('wujihua')->unsigned()->nullable();
			$table->integer('qianhougongchengdengdai')->unsigned()->nullable();
			$table->integer('wubupin')->unsigned()->nullable();
			$table->integer('bupinanpaidengdai')->unsigned()->nullable();
			$table->integer('dingqidianjian')->unsigned()->nullable();
			$table->integer('guzhang')->unsigned()->nullable();
			// $table->integer('xinjizhongshengchanshijian')->unsigned()->nullable();
			$table->integer('shizuo')->unsigned()->nullable()->comment('新机种生产时间（试作）');
			$table->text('jizaishixiang')->nullable();
			$table->string('luruzhe', 20)->nullable();
			$table->string('bianjizhe', 20)->nullable();
			$table->string('dandangzhe', 20)->nullable();
			$table->string('querenzhe', 20)->nullable();
			$table->timestamps();
			// $table->index('shengchanriqi');
			// $table->index('xianti');
			// $table->index('banci');
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
