<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_signs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('uid');
            $table->integer("ymd")->index();
            $table->string("reward_credit", 10)->comment("奖励货币");
            $table->decimal("reward_amount", 17, 5)->comment("奖励货币数量");
            $table->smallInteger("source")->default(0)->comment("0用户自己签到，1系统补交签到");
            $table->smallInteger("status")->default(0)->comment("0签到成功");
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_signs');
    }
}
