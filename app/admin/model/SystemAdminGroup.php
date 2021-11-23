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

class SystemAdminGroup extends TimeModel
{

    protected $deleteTime = 'delete_time';

    /**
     * 根据管理员获取群组
     * @param $adminId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminGroupByAdminId($adminId)
    {
        $groupIdArr = (new SystemAdminGroupRelation())
            ->getAdminGroupIdByAdminId($adminId);
        $groupId = '';
        $groupList = (new SystemAdminGroup())
            ->where('id','in', $groupId)
            ->select();
        if($groupList->isEmpty()){
            return [];
        }
        return $groupList->toArray();
    }

}