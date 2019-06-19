<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_configs', function (Blueprint $table) {
            $table->increments('id');
			$table->string('title', 100);
			$table->string('name', 100)->unique();
            $table->text('value')->nullable();
            $table->string('suoshu')->comment('配置所属哪个页面');
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
        Schema::dropIfExists('smt_configs');
    }
}
