<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factory_id');
            $table->double('cml1');
            $table->double('cml2');
            $table->double('cml3');
            $table->double('cml4');
            $table->double('cml5');
            $table->double('cml6');
            $table->double('cml7');
            $table->double('cml8');
            $table->double('cml9');
            $table->double('cml10');
            $table->double('cml11');
            $table->double('cml12');
            $table->double('cml13');
            $table->double('cml14');
            $table->double('cml15');
            $table->double('cml16');
            $table->double('cml17');
            $table->double('cml18');
            $table->double('cml19');
            $table->double('cml20');
            $table->double('cml21');
            $table->double('cml22');
            $table->double('cml23');
            $table->double('cml24');
            $table->double('cml25');
            $table->double('cml26');
            $table->double('cml27');
            $table->double('cml28');
            $table->double('cml29');
            $table->double('cml30');
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
        Schema::dropIfExists('cms');
    }
}
