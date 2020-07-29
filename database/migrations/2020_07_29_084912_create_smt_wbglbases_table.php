<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtWbglbasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_wbglbases', function (Blueprint $table) {
			$table->increments('id');
			$table->string('wangbanbufan', 50);
			$table->string('jizhongming', 50);
			$table->string('pinming', 50);
			$table->string('xilie', 50)->nullable();
			$table->string('wangbanbianhao', 10)->nullable();
			$table->string('bianhao', 10)->nullable();
			$table->string('wangbanhoudu', 50)->nullable();
			$table->string('teshugongyi', 50)->nullable();
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
        Schema::dropIfExists('smt_wbglbases');
    }
}
