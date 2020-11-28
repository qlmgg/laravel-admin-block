<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('type')->default(0)->comment("0充值 1提现 3转账 4 p2p私下转账");
            $table->string("mark")->comment("唯一标记");
            $table->integer('p_uid')->default(0)->comment("上级uid");
            $table->integer('uid');
            $table->string('credit',10)->comment("操作货币");
            $table->decimal('number',17,5)->default(0.00000);
            $table->string("pic")->comment("凭证图片");
            $table->smallInteger('status')->comment("0 已提交，1审核通过, 2未通过");
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
        Schema::dropIfExists('credit_reviews');
    }
}
