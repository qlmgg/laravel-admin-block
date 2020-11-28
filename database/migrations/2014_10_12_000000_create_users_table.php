<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->integer("group_id")->default(0)->comment("分组id");
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->string('name', 50);
            $table->bigInteger('phone')->unique();
            $table->string('email', 100);
            $table->string('password', 100)->comment("密码");
            $table->string('avatar')->default("")->comment("头像");
            $table->string('realname', 50)->comment("真实姓名")->nullable();
            $table->string('idcard', 100)->comment("身份证号")->nullable();

            $table->decimal("credit1", 17,5)->default(0.00000);
            $table->decimal("credit2", 17,5)->default(0.00000);
            $table->decimal("credit3", 17,5)->default(0.00000);
            $table->decimal("credit4", 17,5)->default(0.00000);
            $table->decimal("credit5", 17,5)->default(0.00000);
            $table->decimal("credit6", 17,5)->default(0.00000);

            $table->smallInteger('status')->comment("-1 待激活 0 正常 1 冻结");
            $table->integer('order')->default(0)->comment("排序");

            $table->unsignedInteger('level');
            $table->string('path');

            $table->rememberToken();
            $table->softDeletes();
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
