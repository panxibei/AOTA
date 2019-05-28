<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScglHcfxTuopansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scgl_hcfx_tuopans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pinming', 50)->comment('品名');
            $table->string('daima', 20)->comment('代码');
            $table->string('guige')->comment('规格');
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
        Schema::dropIfExists('scgl_hcfx_tuopans');
    }
}
