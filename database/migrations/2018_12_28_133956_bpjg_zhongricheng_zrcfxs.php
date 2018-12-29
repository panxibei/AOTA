<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BpjgZhongrichengZrcfxs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bpjg_zhongricheng_zrcfxs', function (Blueprint $table) {
            $table->increments('id');
			$table->string('jizhongming', 50)->comment('机种名');
			$table->integer('d1')->default(0)->unsigned()->comment('1号数量');
			$table->integer('d2')->default(0)->unsigned()->comment('2号数量');
			$table->integer('d3')->default(0)->unsigned()->comment('3号数量');
			$table->integer('d4')->default(0)->unsigned()->comment('4号数量');
			$table->integer('d5')->default(0)->unsigned()->comment('5号数量');
			$table->integer('d6')->default(0)->unsigned()->comment('6号数量');
			$table->integer('d7')->default(0)->unsigned()->comment('7号数量');
			$table->integer('d8')->default(0)->unsigned()->comment('8号数量');
			$table->integer('d9')->default(0)->unsigned()->comment('9号数量');
			$table->integer('d10')->default(0)->unsigned()->comment('10号数量');
			$table->integer('d11')->default(0)->unsigned()->comment('11号数量');
			$table->integer('d12')->default(0)->unsigned()->comment('12号数量');
			$table->integer('d13')->default(0)->unsigned()->comment('13号数量');
			$table->integer('d14')->default(0)->unsigned()->comment('14号数量');
			$table->integer('d15')->default(0)->unsigned()->comment('15号数量');
			$table->integer('d16')->default(0)->unsigned()->comment('16号数量');
			$table->integer('d17')->default(0)->unsigned()->comment('17号数量');
			$table->integer('d18')->default(0)->unsigned()->comment('18号数量');
			$table->integer('d19')->default(0)->unsigned()->comment('19号数量');
			$table->integer('d20')->default(0)->unsigned()->comment('20号数量');
			$table->integer('d21')->default(0)->unsigned()->comment('21号数量');
			$table->integer('d22')->default(0)->unsigned()->comment('22号数量');
			$table->integer('d23')->default(0)->unsigned()->comment('23号数量');
			$table->integer('d24')->default(0)->unsigned()->comment('24号数量');
			$table->integer('d25')->default(0)->unsigned()->comment('25号数量');
			$table->integer('d26')->default(0)->unsigned()->comment('26号数量');
			$table->integer('d27')->default(0)->unsigned()->comment('27号数量');
			$table->integer('d28')->default(0)->unsigned()->comment('28号数量');
			$table->integer('d29')->default(0)->unsigned()->comment('29号数量');
			$table->integer('d30')->default(0)->unsigned()->comment('30号数量');
			$table->integer('d31')->default(0)->unsigned()->comment('31号数量');
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
        Schema::dropIfExists('bpjg_zhongricheng_zrcfxs');
    }
}
