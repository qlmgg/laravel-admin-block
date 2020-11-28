<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('credit',10)->comment("操作货币");
            $table->char('option')->comment("+/-");
            $table->integer('p_uid')->default(0)->comment("支付人");
            $table->integer('uid')->comment("收钱人id");
            $table->decimal('much',17,5)->comment("转了多少");
            $table->string('remark')->comment("备注");
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
        Schema::dropIfExists('credit_logs');
    }
}
