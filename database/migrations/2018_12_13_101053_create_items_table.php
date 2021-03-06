<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');                   //商品ID
            $table->string('name')->nullable();         //商品名
            $table->text('profile')->nullable();        //紹介文
            $table->integer('price');                   //金額
            $table->integer('sizeid')->nullable();      //サイズ
            $table->integer('peasid')->nullable();      //peas数
            $table->string('tag1')->nullable();         //タグ1
            $table->string('tag2')->nullable();         //タグ2
            $table->string('tag3')->nullable();         //タグ3
            $table->string('image')->nullable();        //Image画像
            $table->boolean('view')->default(1);  //可視か不可視か（基本不可視）
            $table->integer('createrid');               //作成者
            $table->integer('updaterid')->nullable();   //更新者
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
        Schema::dropIfExists('items');
    }
}
