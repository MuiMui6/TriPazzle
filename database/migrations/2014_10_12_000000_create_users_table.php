<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');                           //ユーザID
            $table->string('name');                             //名前
            $table->string('email')->unique();                  //メール
            $table->timestamp('email_verified_at')->nullable(); //メール確認日
            $table->string('password');                         //パスワード
            $table->boolean('rank')->default(0);          //rank
            $table->string('anser');                            //秘密の言葉
            $table->integer('updaterid')->nullable();           //更新者
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
