<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $table = 'house';

    public static $STATUS = [
        1 => '待出租',
        2 => '待出售',
        3 => '已预定',
        4 => '已出租',
        5 => '已出售',
        6 => '中介拒绝合作'
    ];

    public static $TYPE = [
        1 => '出租',
        2 => '出售',
    ];

    public static $DECORATE = [
        1 => '精装修',
        2 => '简装修',
        3 => '毛坯房',
        4 => '部分装修',
    ];

    public static $HOUSE_SIZE = [
        1 => '单间',
        2 => '一室一厅一卫',
        3 => '一室二厅一卫',
        4 => '一室二厅二卫',
        5 => '二室一厅二卫',
        6 => '二室二厅一卫',
        7 => '二室二厅二卫',
        8 => '三室一厅二卫',
        16 => '三室二厅二卫',
        9 => '三室二厅三卫',
        10 => '三室三厅二卫',
        11 => '四室一厅二卫',
        12 => '四室一厅三卫',
        13 => '四室二厅二卫',
        14 => '四室三厅三卫',
        15 => '别墅',
    ];

    public static $HOUSE_DIRECTION = [
        1 => '朝南',
        2 => '朝北',
        3 => '朝西',
        4 => '朝东',
        5 => '朝东南',
        6 => '朝东北',
        7 => '朝西南',
        8 => '朝西北',
    ];

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = bcmul($value, 100);
    }

    public function getPriceAttribute($value)
    {
        return bcdiv($value, 100, 2);
    }
}
