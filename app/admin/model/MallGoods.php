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

namespace app\admin\model;


use app\common\model\TimeModel;

class MallGoods extends TimeModel
{

    protected $table = "";

    protected $deleteTime = 'delete_time';

    public function cate()
    {
        return $this->belongsTo('app\admin\model\MallCate', 'cate_id', 'id');
    }

    //关联账户中创建人的ID
    public function creater()
    {
        return $this->belongsTo('app\admin\model\SystemAdmin', 'creater_id ', 'id');
    }

    //关联账户中管理员的ID
    public function admin()
    {
        return $this->belongsTo('app\admin\model\SystemAdmin', 'admin_id ', 'id');
    }

    //推广状态
    public function getPublishStatus()
    {
        return ['0'=>'未推广','1'=>'推广中','2'=>'推广完成','3'=>'已停止',];
    }

}