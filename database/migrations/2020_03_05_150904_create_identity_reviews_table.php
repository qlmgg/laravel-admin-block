<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdentityReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identity_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("uid");
            $table->smallInteger("way")->comment("验证方式 0身份验证 1支付宝认证 2 微信认证 3银行认证");
            $table->string('realname',50)->comment("真实姓名");
            $table->bigInteger('phone')->comment("认证手机号");
            $table->string('account',100)->comment("认证账户");
            $table->string('image')->comment("认证图片");
            $table->timestamp('pass_at')->nullable();
            $table->tinyInteger('status')->default(0)->comment(" -1认证失败 0 未认证 1 认证通过");
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
        Schema::dropIfExists('identity_reviews');
    }
}
