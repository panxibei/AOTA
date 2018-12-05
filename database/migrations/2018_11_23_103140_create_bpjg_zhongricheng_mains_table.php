<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBpjgZhongrichengMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bpjg_zhongricheng_mains', function (Blueprint $table) {
            $table->increments('id');
			$table->timestamp('riqi')->nullable();
			$table->string('xianti', 50)->comment('线体');
			$table->string('qufen', 50)->comment('区分');
			$table->string('jizhongming', 50)->comment('机种名');
			$table->string('pinfan', 50)->comment('品番');
			$table->string('pinming', 50)->comment('品名');
			$table->string('leibie', 20)->comment('分类，冲压或成型');
			$table->integer('xuqiushuliang')->default(0)->unsigned()->comment('需求数量');
			$table->integer('zongshu')->default(0)->unsigned()->comment('总数');
			$table->integer('shuliang')->default(0)->unsigned()->comment('数量');
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
        Schema::dropIfExists('bpjg_zhongricheng_mains');
    }
}
