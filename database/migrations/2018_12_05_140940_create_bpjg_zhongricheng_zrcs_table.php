<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBpjgZhongrichengZrcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bpjg_zhongricheng_zrcs', function (Blueprint $table) {
            $table->increments('id');
			$table->timestamp('riqi');
			$table->string('jizhongming', 50)->comment('机种名');
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
        Schema::dropIfExists('bpjg_zhongricheng_zrcs');
    }
}
