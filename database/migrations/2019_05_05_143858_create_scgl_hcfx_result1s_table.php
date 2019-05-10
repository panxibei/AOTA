<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScglHcfxResult1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scgl_hcfx_result1s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('suoshuriqi', 50)->comment('所属日期');
            $table->string('jizhongming', 50)->comment('机种');
            $table->integer('chanliang')->comment('1号-20号的产量（计划）');
            $table->string('tuopanxinghao', 50)->comment('托盘型号');
            $table->integer('tai_per_tuo')->comment('台/托');
            $table->float('lilun_tuo')->comment('理论（托）');
            $table->float('shiji_tuo')->comment('实际（托）');
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
        Schema::dropIfExists('scgl_hcfx_result1s');
    }
}
