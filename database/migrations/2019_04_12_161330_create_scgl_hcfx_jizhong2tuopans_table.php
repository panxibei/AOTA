<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScglHcfxJizhong2tuopansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scgl_hcfx_jizhong2tuopans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jizhongming', 50)->comment('机种名');
            $table->string('tuopanxinghao', 50)->comment('托盘型号');
            $table->integer('tai_per_tuo')->unsigned()->comment('台/托');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scgl_hcfx_jizhong2tuopans');
    }
}
