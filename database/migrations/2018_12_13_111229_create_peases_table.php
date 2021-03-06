<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeasesTable extends Migration
{
    //peas数
    public function up()
    {
        Schema::create('peases', function (Blueprint $table) {
            $table->increments('id');                   //peasID
            $table->integer('cnt');                     //ピース数
            $table->integer('createrid');               //作成者
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
        Schema::dropIfExists('peases');
    }
}
