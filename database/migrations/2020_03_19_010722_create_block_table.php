<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //图鉴
        Schema::create('block_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->comment("名称");
            $table->string('levelname', 50)->default("")->comment("级别名称");
            $table->string('pic', 100)->comment("头图");
            $table->time('begin_time')->comment("领养开始时间");
            $table->time('end_time')->comment("领养结束时间");

            $table->decimal("min_worth_price", 17, 5)->comment("最小价值");
            $table->decimal("max_worth_price", 17, 5)->comment("最大价值");
            $table->decimal("reserve_price", 17, 5)->comment("预约微分");
            $table->decimal("adopt_price", 17, 5)->comment("领养微分");
            $table->integer("profit_rate")->comment("合约天数");
            $table->integer("profit_day")->default(0)->comment("合约比例");

            $table->integer("bestow1")->default(0)->comment("奖励1");
            $table->integer("bestow2")->default(0)->comment("奖励2");
            $table->integer("bestow3")->default(0)->comment("奖励3");
            $table->integer("bestow4")->default(0)->comment("奖励4");
            $table->integer("bestow5")->default(0)->comment("奖励5");
            $table->integer("bestow6")->default(0)->comment("奖励6");

            $table->softDeletes();
            $table->timestamps();
        });

        //会员销售
        Schema::create('block_card_sells', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("recording_id");
            $table->integer("uid")->comment("发布出售人");
            $table->integer("card_id");
            $table->decimal("price", 17, 5)->comment("出售的价格");
            $table->smallInteger("is_sale")->comment("是否可以出售 0 不可 1可");
            $table->integer("order")->default(0)->comment("排序");
            $table->tinyInteger("lock")->default(0)->comment("已经销售出去，加锁");
            $table->tinyInteger("status")->default(0)->comment("0等待审核 1 审核通过");
            $table->softDeletes();
            $table->timestamps();
        });
        // 队列表
        Schema::create('block_batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cycle', 50)->comment("周期")->unique();
            $table->smallInteger('status')->default(0)->comment("0未启动,1立即运行,2执行结束");
            $table->smallInteger('queue')->default(0)->comment("0队列未执行 1 队列执行完毕");
            $table->softDeletes();
            $table->timestamps();
        });


        //记录表
        Schema::create('block_recordings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("card_id");
            $table->integer("batch_id")->comment("批次id");
            $table->integer("ymd")->comment("Ymd周期");

            $table->integer("card_sell_id")->default(0)->comment("发布人记录的card_sell_id");
            $table->integer("release_uid")->default(0)->comment("发布人 0系统发布");
            $table->integer("uid")->default(0)->comment("预约/抢购用户id");

            $table->smallInteger("type")->default(0)->comment("0系统分配 1 预约 2 抢购 3收益转换");


            $table->string("deduct_credit", 10)->comment("扣除货币");
            $table->decimal("deduct_amount", 17, 5)->comment("扣除货币数量");
            $table->smallInteger("refund")->default(0)->comment("是否退款");


            $table->string("hashblock")->comment("区块链唯一标识");

            $table->decimal("init_price", 17, 5)->comment("初始价值");
            $table->decimal("price", 17, 5)->comment("当前价值");
            $table->integer("daydown")->default(0)->comment("收益天数倒计时");


            $table->smallInteger("winbidding")->default(0)->comment("uid 中标 0 未中标 1 中标");
            $table->timestamp("winbidding_at")->nullable()->comment("中标时间");
            $table->smallInteger("userhave")->default(0)->comment("uid 拥有该卡片");
            $table->timestamp("userhave_at")->nullable()->comment("拥有该卡片的时间");
            //$table->smallInteger("expired")->default(0)->comment("是否到期");
            $table->smallInteger("transfer_status")->default(0)->comment("转让状态 0 正常收益 1 待转让");

            $table->softDeletes();
            $table->timestamps();
        });

        //p2p支付提交
        Schema::create('block_recording_pay_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("recording_id");
            $table->integer("from_uid");
            $table->integer("uid");
            $table->decimal("price", 17, 5)->comment("支付价格");
            $table->string('image')->comment("支付截图");
            $table->tinyInteger('status')->default(0)->comment("0 等待审核 1 审核通过  2审核不通过");
            $table->string('fail_reason')->default("")->comment("不通过原因");
            $table->softDeletes();
            $table->timestamps();
        });
        // 每日产值记录
        Schema::create('block_recording_pirce_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("ymd")->comment("周期");
            $table->integer('recording_id')->comment("记录id");
            $table->integer('uid')->comment("用户id");
            $table->string('credit', 10)->comment("操作货币");
            $table->decimal('much',17,5)->comment("收益多少");
            $table->string('remark')->comment("备注");
            $table->softDeletes();
            $table->timestamp('created_at')->nullable();
        });
        //申诉
        Schema::create('block_recording_appeals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("recording_id");
            $table->integer("complaint_uid")->default(0)->comment("原告");
            $table->integer("accused_uid")->default(0)->comment("被告");
            $table->integer("ymd")->comment("周期");
            $table->text("reason");
            $table->tinyInteger('status')->default(0);
            $table->softDeletes();
            $table->timestamp('created_at')->nullable();
        });
        //成长记录
        Schema::create('block_recording_growings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("ymd")->comment("周期");
            $table->integer('recording_id')->comment("记录id");
            $table->integer('old_card_id')->comment("老图鉴ID");
            $table->integer('new_card_id')->comment("新图鉴ID");
            $table->softDeletes();
            $table->timestamp('created_at')->nullable();
        });
        // 抽奖记录
        Schema::create('block_lottery_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("uid")->comment("用户id");
            $table->bigInteger('phone');
            $table->string('prizename');

            $table->string("deduct_credit", 10)->comment("扣除货币");
            $table->decimal("deduct_amount", 17, 5)->comment("扣除货币数量");

            $table->boolean("status")->default(1);
            $table->string("address")->comment("发货地址");
            $table->softDeletes();
            $table->timestamps();
        });
        // 公告
        Schema::create('block_notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger("type")->comment("类型 1系统 2活动");
            $table->string("title", 128)->comment("标题");
            $table->text("content")->comment("内容");
            $table->boolean("is_index")->default(0)->comment("是否在首页弹出");
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
        Schema::dropIfExists('block_cards');
        Schema::dropIfExists('block_card_sells');
        Schema::dropIfExists('block_batches');
        Schema::dropIfExists('block_recordings');
        Schema::dropIfExists('block_recording_pay_reviews');
        Schema::dropIfExists('block_recording_pirce_logs');
        Schema::dropIfExists('block_recording_appeals');
        Schema::dropIfExists('block_recording_growings');
        Schema::dropIfExists('block_lottery_logs');
        Schema::dropIfExists('block_notices');
    }
}
