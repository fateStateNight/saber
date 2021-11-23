<?php

// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | PHP交流群: 763822524
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zhongshaofa/EasyAdmin
// +----------------------------------------------------------------------


namespace app\common\model;


use think\Model;
use think\facade\Cache;

/**
 * redis的模型
 * Class RedisModel
 * @package app\common\model
 */
class RedisModel extends Model
{

    /**
     * 自动时间戳类型
     * @var string
     */
    protected $autoWriteTimestamp = true;

    /**
     * redis 切库
     * @param int $num 库号（0~15）
     */
    public static function choose($num=0)
    {
        Cache::store('redis')->handler()->select($num);
    }

}