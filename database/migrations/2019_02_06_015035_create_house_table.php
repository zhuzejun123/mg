<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('city')->comment('城市')->nullable(true)->default('');
            $table->string('area')->comment('地区')->nullable(true)->default('');
            $table->string('village')->comment('小区名')->nullable(true)->default('')->index();
            $table->string('number')->comment('幢/门牌号')->nullable(true)->default('');
            $table->string('floor')->comment('楼层')->nullable(true)->default('')->index();
            $table->string('road')->comment('街道')->nullable(true)->default('');
            $table->string('phone')->comment('房主手机号')->nullable(true)->default('');
            $table->tinyInteger('house_size')->comment('房屋规模')->nullable(true)->default(0)->index();
            $table->tinyInteger('decorate')->comment('装修程度')->nullable(true)->default(0)->index();
            $table->tinyInteger('house_area')->comment('房屋面积')->nullable(true)->default(0)->index();
            $table->tinyInteger('house_direction')->comment('房屋朝向')->nullable(true)->default(0)->index();
            $table->unsignedBigInteger('price')->comment('价格')->nullable(true)->default(0)->index();
            $table->tinyInteger('house_type')->comment('房屋类型1-出租房 2-出售房 3-都可')->nullable(true)->default(3);
            $table->tinyInteger('house_status')->comment('房屋状态1-已出售 2-已出租 3-出租中 4-出售中 5-已预定 6-待出售 7-待出租 8-闲置 9-已预定 10-放弃房源 11-待处理')->nullable(true)->default(11);
            $table->tinyInteger('is_cert')->comment('是否有房产证')->nullable(true)->default(0);
            $table->text('remark')->comment('备注信息')->nullable(true)->default(null);
            $table->dateTime('register_time')->comment('登记日期')->nullable(true)->default(null);
            $table->dateTime('throw_time')->comment('出租/出售日期')->nullable(true)->default(null);
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
        Schema::dropIfExists('house');
    }
}
