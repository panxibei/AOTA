<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBpjgZhongrichengZrcfxsTable extends Migration
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
			$table->integer('d1')->nullable()->default(0)->unsigned()->comment('1号数量');
			$table->integer('d2')->nullable()->default(0)->unsigned()->comment('2号数量');
			$table->integer('d3')->nullable()->default(0)->unsigned()->comment('3号数量');
			$table->integer('d4')->nullable()->default(0)->unsigned()->comment('4号数量');
			$table->integer('d5')->nullable()->default(0)->unsigned()->comment('5号数量');
			$table->integer('d6')->nullable()->default(0)->unsigned()->comment('6号数量');
			$table->integer('d7')->nullable()->default(0)->unsigned()->comment('7号数量');
			$table->integer('d8')->nullable()->default(0)->unsigned()->comment('8号数量');
			$table->integer('d9')->nullable()->default(0)->unsigned()->comment('9号数量');
			$table->integer('d10')->nullable()->default(0)->unsigned()->comment('10号数量');
			$table->integer('d11')->nullable()->default(0)->unsigned()->comment('11号数量');
			$table->integer('d12')->nullable()->default(0)->unsigned()->comment('12号数量');
			$table->integer('d13')->nullable()->default(0)->unsigned()->comment('13号数量');
			$table->integer('d14')->nullable()->default(0)->unsigned()->comment('14号数量');
			$table->integer('d15')->nullable()->default(0)->unsigned()->comment('15号数量');
			$table->integer('d16')->nullable()->default(0)->unsigned()->comment('16号数量');
			$table->integer('d17')->nullable()->default(0)->unsigned()->comment('17号数量');
			$table->integer('d18')->nullable()->default(0)->unsigned()->comment('18号数量');
			$table->integer('d19')->nullable()->default(0)->unsigned()->comment('19号数量');
			$table->integer('d20')->nullable()->default(0)->unsigned()->comment('20号数量');
			$table->integer('d21')->nullable()->default(0)->unsigned()->comment('21号数量');
			$table->integer('d22')->nullable()->default(0)->unsigned()->comment('22号数量');
			$table->integer('d23')->nullable()->default(0)->unsigned()->comment('23号数量');
			$table->integer('d24')->nullable()->default(0)->unsigned()->comment('24号数量');
			$table->integer('d25')->nullable()->default(0)->unsigned()->comment('25号数量');
			$table->integer('d26')->nullable()->default(0)->unsigned()->comment('26号数量');
			$table->integer('d27')->nullable()->default(0)->unsigned()->comment('27号数量');
			$table->integer('d28')->nullable()->default(0)->unsigned()->comment('28号数量');
			$table->integer('d29')->nullable()->default(0)->unsigned()->comment('29号数量');
			$table->integer('d30')->nullable()->default(0)->unsigned()->comment('30号数量');
			$table->integer('d31')->nullable()->default(0)->unsigned()->comment('31号数量');
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
