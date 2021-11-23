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

namespace app\admin\controller\system;


use app\admin\model\SystemAdmin;
use app\admin\model\SystemAdminGroup;
use app\admin\model\SystemAdminGroupRelation;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\response\Json;

/**
 * @ControllerAnnotation(title="管理员群组")
 * Class Auth
 * @package app\admin\controller\system
 */
class AdminGroup extends AdminController
{

    use \app\admin\traits\Curd;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemAdminGroup();
    }

    /**
     * @NodeAnotation(title="群组")
     */
    public function index()
    {
        $groupIdArr = (new SystemAdminGroupRelation())
            ->getAdminGroupIdByAdminId(session('admin')['id']);
        $groupList = $this->model->where('id', 'in', $groupIdArr)
            ->whereOr('publish', '=', 1)
            ->select();
        if($groupList != null){
            $groupList = $groupList->toArray();
        }
        $this->assign('innerGroupId', implode(',',$groupIdArr));
        $this->assign('adminInfo',session('admin'));
        $this->assign('groupList',$groupList);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="新增")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            //var_dump($post);die;
            $publish = 0;
            if(array_key_exists('publish',$post) && $post['publish'] == 'on'){
                $publish = 1;
            }
            //更新群组信息
            $groupInfo = [
                'title' => $post['title'],
                'group_image' => str_replace('http:', 'https:', $post['group_image']),
                'publish' => $publish,
                'member_num' => count($post['groupSelect']),
                'remark' => $post['remark'],
                'create_id' => session('admin')['id']
            ];
            try {
                $save = $this->model->save($groupInfo);
            } catch (\Exception $e) {
                $this->error('新增失败');
            }
            if($save){
                $groupRelationObj = new SystemAdminGroupRelation();
                foreach($post['groupSelect'] as $groupMemberInfo){
                    $relationArr = [
                        'group_id' => $this->model->id,
                        'admin_id' => $groupMemberInfo['value'],
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                    $ret = $groupRelationObj->insert($relationArr);
                }
            }
            if($save && $ret){
                $this->success('新增成功');
            }else{
                $this->error('创建失败');
            }
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        $row->isEmpty() && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            //var_dump($post);die;
            $publish = 0;
            if(array_key_exists('publish',$post) && $post['publish'] == 'on'){
                $publish = 1;
            }
            //更新群组信息
            $groupInfo = [
                'title' => $post['title'],
                'group_image' => str_replace('http:', 'https:', $post['group_image']),
                'publish' => $publish,
                'member_num' => count($post['groupSelect']),
                'remark' => $post['remark'],
                'create_id' => session('admin')['id']
            ];
            try {
                $save = $row->save($groupInfo);
            } catch (\Exception $e) {
                $this->error('新增失败');
            }
            if($save){
                $groupRelationObj = new SystemAdminGroupRelation();
                $adminIdArr = $groupRelationObj->getAdminIdByGroupId($id);
                //插入新增的成员数据
                $groupSelectMemberArr = [];
                foreach($post['groupSelect'] as $groupMemberInfo){
                    if(!in_array($groupMemberInfo['value'],$adminIdArr)){
                        $relationArr = [
                            'group_id' => $id,
                            'admin_id' => $groupMemberInfo['value'],
                            'create_time' => date('Y-m-d H:i:s'),
                        ];
                        $ret = $groupRelationObj->insert($relationArr);
                    }
                    $groupSelectMemberArr[] = $groupMemberInfo['value'];
                }
                //删除移除的成员数据
                foreach($adminIdArr as $adminIdInnerGroup){
                    if(!in_array($adminIdInnerGroup,$groupSelectMemberArr)){
                        $delRet = $groupRelationObj->where('admin_id','=',$adminIdInnerGroup)
                            ->where('group_id','=',$id)
                            ->delete();
                    }
                }
            }
            if($save){
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败');
            }
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="删除")
     */
    public function delete($id)
    {
        $row = $this->model->whereIn('id', $id)->select();
        $row->isEmpty() && $this->error('数据不存在');
        try {
            $save = $row->delete();
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        if($save){
            $selectData = (new SystemAdminGroupRelation())
                ->whereIn('group_id',$id)
                ->select();
            $ret = $selectData->delete();
        }
        $save && $ret ? $this->success('删除成功') : $this->error('删除失败');

        //$this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="群组成员")
     */
    public function getGroupMemberList($groupId = null)
    {
        if($groupId == null){
            $adminIdArr = [session('admin')['id']];
        }else{
            $adminIdArr = (new SystemAdminGroupRelation())
                ->getAdminIdByGroupId($groupId);
        }
        $this->success('success',$adminIdArr);
        return $adminIdArr;
    }

    /**
     * @NodeAnotation(title="所有成员")
     */
    public function getAllMemberList()
    {
        $allMemberList = (new SystemAdmin())
            ->where('status','=', '1')
            ->field(['id','nickname'])
            ->select();
        $allMemberArr = [];
        if($allMemberList != null){
            foreach($allMemberList as $key=>$memberInfo){
                $allMemberArr[$key] = [
                    'value' => $memberInfo['id'],
                    'title' => $memberInfo['nickname']
                ];
                if($memberInfo['id'] == session('admin')['id']){
                    $allMemberArr[$key]['disabled'] = 'true';
                }
            }
        }
        $this->success('success',$allMemberArr);
    }


    /**
     * @NodeAnotation(title="修改公开开关")
     */
    public function updateGroupPublish($groupId,$publish)
    {
        if($publish == 'on'){
            $publish = 1;
        }else{
            $publish = 0;
        }
        $row = $this->model->find($groupId);
        //更新群组信息
        $groupInfo = [
            'publish' => $publish,
        ];
        try {
            $save = $row->save($groupInfo);
        } catch (\Exception $e) {
            $this->error('修改失败');
        }
        if($save){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }


    /**
     * @NodeAnotation(title="加入群组")
     */
    public function joinGroup($groupId)
    {
        $row = $this->model->find($groupId);
        //更新群组信息
        $groupInfo = [
            'member_num' => $row->member_num + 1,
        ];
        try {
            $save = $row->save($groupInfo);
            $groupRelationObj = new SystemAdminGroupRelation();
            $relationArr = [
                'group_id' => $groupId,
                'admin_id' => session('admin')['id'],
                'create_time' => date('Y-m-d H:i:s'),
            ];
            $ret = $groupRelationObj->insert($relationArr);
        } catch (\Exception $e) {
            $this->error('加入失败');
        }
        if($save && $ret){
            $this->success('加入成功');
        }else{
            $this->error('加入失败');
        }
    }


    /**
     * @NodeAnotation(title="退出群组")
     */
    public function quitGroup($groupId)
    {
        $row = $this->model->find($groupId);
        //更新群组信息
        $groupInfo = [
            'member_num' => $row->member_num - 1,
        ];
        try {
            $save = $row->save($groupInfo);
            $groupRelationObj = new SystemAdminGroupRelation();
            $ret = $groupRelationObj->where('group_id','=',$groupId)
                ->where('admin_id','=',session('admin')['id'])
                ->delete();
        } catch (\Exception $e) {
            $this->error('退出失败');
        }
        if($save && $ret){
            $this->success('退出成功');
        }else{
            $this->error('退出失败');
        }
    }

}