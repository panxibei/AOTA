<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtPdplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_pdplans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('suoshuyuefen')->comment('所属月份');
            // $table->integer('suoshuriqi')->comment('所属日期');
            $table->string('xianti', 20)->comment('线体');
			$table->string('jizhongming', 50)->comment('机种名');
			$table->string('spno', 50)->comment('SP NO.');
			$table->string('pinming', 50)->comment('品名');
			$table->string('gongxu', 20)->comment('工序');
			$table->integer('lotshu')->unsigned()->comment('LOT数');
			$table->text('chanliangxinxi')->comment('产量信息');
			// $table->integer('d1_A')->nullable()->default(0)->unsigned()->comment('1号产量_A班');
			// $table->integer('d1_B')->nullable()->default(0)->unsigned()->comment('1号产量_B班');
			// $table->integer('d2_A')->nullable()->default(0)->unsigned()->comment('2号产量_A班');
			// $table->integer('d2_B')->nullable()->default(0)->unsigned()->comment('2号产量_B班');
			// $table->integer('d3_A')->nullable()->default(0)->unsigned()->comment('3号产量_A班');
			// $table->integer('d3_B')->nullable()->default(0)->unsigned()->comment('3号产量_B班');
			// $table->integer('d4_A')->nullable()->default(0)->unsigned()->comment('4号产量_A班');
			// $table->integer('d4_B')->nullable()->default(0)->unsigned()->comment('4号产量_B班');
			// $table->integer('d5_A')->nullable()->default(0)->unsigned()->comment('5号产量_A班');
			// $table->integer('d5_B')->nullable()->default(0)->unsigned()->comment('5号产量_B班');
			// $table->integer('d6_A')->nullable()->default(0)->unsigned()->comment('6号产量_A班');
			// $table->integer('d6_B')->nullable()->default(0)->unsigned()->comment('6号产量_B班');
			// $table->integer('d7_A')->nullable()->default(0)->unsigned()->comment('7号产量_A班');
			// $table->integer('d7_B')->nullable()->default(0)->unsigned()->comment('7号产量_B班');
			// $table->integer('d8_A')->nullable()->default(0)->unsigned()->comment('8号产量_A班');
			// $table->integer('d8_B')->nullable()->default(0)->unsigned()->comment('8号产量_B班');
			// $table->integer('d9_A')->nullable()->default(0)->unsigned()->comment('9号产量_A班');
			// $table->integer('d9_B')->nullable()->default(0)->unsigned()->comment('9号产量_B班');
			// $table->integer('d10_A')->nullable()->default(0)->unsigned()->comment('10号产量_A班');
			// $table->integer('d10_B')->nullable()->default(0)->unsigned()->comment('10号产量_B班');
			// $table->integer('d11_A')->nullable()->default(0)->unsigned()->comment('11号产量_A班');
			// $table->integer('d11_B')->nullable()->default(0)->unsigned()->comment('11号产量_B班');
			// $table->integer('d12_A')->nullable()->default(0)->unsigned()->comment('12号产量_A班');
			// $table->integer('d12_B')->nullable()->default(0)->unsigned()->comment('12号产量_B班');
			// $table->integer('d13_A')->nullable()->default(0)->unsigned()->comment('13号产量_A班');
			// $table->integer('d13_B')->nullable()->default(0)->unsigned()->comment('13号产量_B班');
			// $table->integer('d14_A')->nullable()->default(0)->unsigned()->comment('14号产量_A班');
			// $table->integer('d14_B')->nullable()->default(0)->unsigned()->comment('14号产量_B班');
			// $table->integer('d15_A')->nullable()->default(0)->unsigned()->comment('15号产量_A班');
			// $table->integer('d15_B')->nullable()->default(0)->unsigned()->comment('15号产量_B班');
			// $table->integer('d16_A')->nullable()->default(0)->unsigned()->comment('16号产量_A班');
			// $table->integer('d16_B')->nullable()->default(0)->unsigned()->comment('16号产量_B班');
			// $table->integer('d17_A')->nullable()->default(0)->unsigned()->comment('17号产量_A班');
			// $table->integer('d17_B')->nullable()->default(0)->unsigned()->comment('17号产量_B班');
			// $table->integer('d18_A')->nullable()->default(0)->unsigned()->comment('18号产量_A班');
			// $table->integer('d18_B')->nullable()->default(0)->unsigned()->comment('18号产量_B班');
			// $table->integer('d19_A')->nullable()->default(0)->unsigned()->comment('19号产量_A班');
			// $table->integer('d19_B')->nullable()->default(0)->unsigned()->comment('19号产量_B班');
			// $table->integer('d20_A')->nullable()->default(0)->unsigned()->comment('20号产量_A班');
			// $table->integer('d20_B')->nullable()->default(0)->unsigned()->comment('20号产量_B班');
			// $table->integer('d21_A')->nullable()->default(0)->unsigned()->comment('21号产量_A班');
			// $table->integer('d21_B')->nullable()->default(0)->unsigned()->comment('21号产量_B班');
			// $table->integer('d22_A')->nullable()->default(0)->unsigned()->comment('22号产量_A班');
			// $table->integer('d22_B')->nullable()->default(0)->unsigned()->comment('22号产量_B班');
			// $table->integer('d23_A')->nullable()->default(0)->unsigned()->comment('23号产量_A班');
			// $table->integer('d23_B')->nullable()->default(0)->unsigned()->comment('23号产量_B班');
			// $table->integer('d24_A')->nullable()->default(0)->unsigned()->comment('24号产量_A班');
			// $table->integer('d24_B')->nullable()->default(0)->unsigned()->comment('24号产量_B班');
			// $table->integer('d25_A')->nullable()->default(0)->unsigned()->comment('25号产量_A班');
			// $table->integer('d25_B')->nullable()->default(0)->unsigned()->comment('25号产量_B班');
			// $table->integer('d26_A')->nullable()->default(0)->unsigned()->comment('26号产量_A班');
			// $table->integer('d26_B')->nullable()->default(0)->unsigned()->comment('26号产量_B班');
			// $table->integer('d27_A')->nullable()->default(0)->unsigned()->comment('27号产量_A班');
			// $table->integer('d27_B')->nullable()->default(0)->unsigned()->comment('27号产量_B班');
			// $table->integer('d28_A')->nullable()->default(0)->unsigned()->comment('28号产量_A班');
			// $table->integer('d28_B')->nullable()->default(0)->unsigned()->comment('28号产量_B班');
			// $table->integer('d29_A')->nullable()->default(0)->unsigned()->comment('29号产量_A班');
			// $table->integer('d29_B')->nullable()->default(0)->unsigned()->comment('29号产量_B班');
			// $table->integer('d30_A')->nullable()->default(0)->unsigned()->comment('30号产量_A班');
			// $table->integer('d30_B')->nullable()->default(0)->unsigned()->comment('30号产量_B班');
			// $table->integer('d31_A')->nullable()->default(0)->unsigned()->comment('31号产量_A班');
			// $table->integer('d31_B')->nullable()->default(0)->unsigned()->comment('31号产量_B班');
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
        Schema::dropIfExists('smt_pdplans');
    }
}
