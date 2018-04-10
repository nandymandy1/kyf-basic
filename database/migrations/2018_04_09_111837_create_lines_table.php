<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('factory_id');
          $table->integer('l1');
          $table->integer('l2');
          $table->integer('l3');
          $table->integer('l4');
          $table->integer('l5');
          $table->integer('l6');
          $table->integer('l7');
          $table->integer('l8');
          $table->integer('l9');
          $table->integer('l10');
          $table->integer('l11');
          $table->integer('l12');
          $table->integer('l13');
          $table->integer('l14');
          $table->integer('l15');
          $table->integer('l16');
          $table->integer('l17');
          $table->integer('l18');
          $table->integer('l19');
          $table->integer('l20');
          $table->integer('l21');
          $table->integer('l22');
          $table->integer('l23');
          $table->integer('l24');
          $table->integer('l25');
          $table->integer('l26');
          $table->integer('l27');
          $table->integer('l28');
          $table->integer('l29');
          $table->integer('l30');
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
        Schema::dropIfExists('lines');
    }
}
