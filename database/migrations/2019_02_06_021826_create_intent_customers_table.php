<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntentCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intent_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_name')->comment('客户名称')->nullable(true)->default('');
            $table->string('customer_phone')->comment('客户手机号')->nullable(true)->default('');
            $table->unsignedBigInteger('intent_price')->comment('客户报价')->nullable(true)->default(0);
            $table->unsignedBigInteger('final_price')->comment('最终价格')->nullable(true)->default(0);
            $table->tinyInteger('intent')->comment('客户意向度 1-10')->nullable(true)->default(1);
            $table->tinyInteger('house_id')->comment('房源id')->nullable(true)->default(0);
            $table->dateTime('watch_time')->comment('看房日期')->nullable(true)->default(null);
            $table->dateTime('sign_time')->comment('签合同日期')->nullable(true)->default(null);
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
        Schema::dropIfExists('intent_customers');
    }
}
