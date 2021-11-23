<?php

// +----------------------------------------------------------------------
// | 管理员分组
// +----------------------------------------------------------------------
// | 关联聊天群组
// +----------------------------------------------------------------------
// | 个人聊天界面
// +----------------------------------------------------------------------

namespace app\admin\model;


use app\common\model\TimeModel;

class SystemAdminGroupRelation extends TimeModel
{

    /**
     * 根据管理员Id获取群组Id
     * @param $adminId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminGroupIdByAdminId($adminId)
    {
        $groupIdArr = (new SystemAdminGroupRelation())
            ->where('admin_id', '=', $adminId)
            ->column('group_id');
        if($groupIdArr == null){
            $groupIdArr = [];
        }
        return $groupIdArr;
    }

    /**
     * 根据群组ID获取管理员ID
     * @param $groupId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminIdByGroupId($groupId)
    {
        $groupIdArr = (new SystemAdminGroupRelation())
            ->where('group_id', '=', $groupId)
            ->column('admin_id');
        return $groupIdArr;
    }

}