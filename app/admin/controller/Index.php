<?php

namespace app\admin\controller;


use app\admin\model\SystemAdmin;
<<<<<<< HEAD
use app\admin\model\SystemAdminGroup;
use app\admin\model\SystemAdminGroupRelation;
use app\admin\model\SystemQuick;
use app\common\controller\AdminController;
use EasyAdmin\upload\Uploadfile;
=======
use app\admin\model\SystemQuick;
use app\common\controller\AdminController;
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
use think\App;
use think\facade\Env;

class Index extends AdminController
{

    /**
     * 后台主页
     * @return string
     * @throws \Exception
     */
    public function index()
    {
<<<<<<< HEAD
        $this->assign('user_id',session('admin')['id']);
=======
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
        return $this->fetch('', [
            'admin' => session('admin'),
        ]);
    }

    /**
     * 后台欢迎页
     * @return string
     * @throws \Exception
     */
    public function welcome()
    {
        $quicks = SystemQuick::field('id,title,icon,href')
            ->where(['status' => 1])
            ->order('sort', 'desc')
            ->limit(8)
            ->select();
        $this->assign('quicks', $quicks);
<<<<<<< HEAD
        //获取当月成交总金额
        $businessSceneModel = new \app\admin\model\BusinessScene();
        $accountInfo = $businessSceneModel->getALiAccountInfo(session('admin')['taobao_accountId']);
        $onlineInfo = $businessSceneModel->getOnlineAccountInfo($accountInfo[0]['token'], $accountInfo[0]['cookies']);
        $currentMonthPrice = 0;
        $eventPrice618 = 0;
        $eventPrePrice618 = 0;
        $currentTime = date('Y-m-d H:i:s');
        if($onlineInfo && array_key_exists('data', $onlineInfo)){
            $totalPrice = $businessSceneModel->getUnionInfo($accountInfo[0]['token'], $accountInfo[0]['cookies'],date('Y-m-01',strtotime('last month')));
            if($totalPrice && array_key_exists('data',$totalPrice) && array_key_exists('summaryBucket',$totalPrice['data'])){
                $currentMonthPrice = round($totalPrice['data']['summaryBucket']['cmAlipayAmt'],2);
            }
            //获取618活动数据
            $eventTotalPrice618 = $businessSceneModel->getEventUnionInfo($accountInfo[0]['token'], $accountInfo[0]['cookies']);
            if($eventTotalPrice618 && array_key_exists('data',$eventTotalPrice618) && array_key_exists('baseData',$eventTotalPrice618['data'])){
                if(array_key_exists('payment',$eventTotalPrice618['data']['baseData']) && array_key_exists('alipayAmt',$eventTotalPrice618['data']['baseData']['payment'])){
                    $eventPrice618 = $eventTotalPrice618['data']['baseData']['payment']['alipayAmt']*5;
                }
                if(array_key_exists('presale',$eventTotalPrice618['data']['baseData']) && array_key_exists('presalePredictAlipayAmt',$eventTotalPrice618['data']['baseData']['presale'])){
                    $eventPrePrice618 = $eventTotalPrice618['data']['baseData']['presale']['presalePredictAlipayAmt']*5;
                }
            }
        }
        $this->assign('currentMonthPrice',$currentMonthPrice);
        //$this->assign('eventPrice618',$eventPrice618);
        //$this->assign('eventPrePrice618',$eventPrePrice618);
        //获取报名商品数
        $businessGoodsModel = new \app\admin\model\BusinessGoods();
        $goodsCount = $businessGoodsModel->where(['auditorId'=>session('admin')['id']])
            ->where('endTime', '>=', $currentTime)->count();
        $this->assign('goodsCount',$goodsCount);

=======
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
        return $this->fetch();
    }

    /**
     * 修改管理员信息
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editAdmin()
    {
        $id = session('admin.id');
        $row = (new SystemAdmin())
            ->withoutField('password')
            ->find($id);
        empty($row) && $this->error('用户信息不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $this->isDemo && $this->error('演示环境下不允许修改');
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $row
                    ->allowField(['head_img', 'nickname', 'phone', 'remark', 'update_time'])
                    ->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * 修改密码
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editPassword()
    {
        $id = session('admin.id');
        $row = (new SystemAdmin())
            ->withoutField('password')
            ->find($id);
        if (!$row) {
            $this->error('用户信息不存在');
        }
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $this->isDemo && $this->error('演示环境下不允许修改');
            $rule = [
                'password|登录密码'       => 'require',
                'password_again|确认密码' => 'require',
            ];
            $this->validate($post, $rule);
            if ($post['password'] != $post['password_again']) {
                $this->error('两次密码输入不一致');
            }

            // 判断是否为演示站点
            $example = Env::get('easyadmin.example', 0);
            $example == 1 && $this->error('演示站点不允许修改密码');

            try {
                $save = $row->save([
                    'password' => password($post['password']),
                ]);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            if ($save) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

<<<<<<< HEAD


    /**
     * @NodeAnotation(title="好友列表")
     */
    public function getFriendList()
    {
        //获取管理员列表
        $allMember = (new SystemAdmin())->where('status','=',1)
            ->select();
        $memberList = [
            'mine' => [],
            'friend' => [
                0 => [
                    'groupname' => '公司群',
                    'id' => '1',
                    'online' => '1',
                    'list' => [],
                ],
            ],
            'group' => [],
        ];
        if($allMember){
            $allMemberArr = $allMember->toArray();
            $friendList = [];
            foreach($allMemberArr as $memberInfo){
                $memberArr = [
                    'username' => $memberInfo['nickname'],
                    'id' => $memberInfo['id'],
                    'status' => 'online',
                    'sign' => $memberInfo['remark'],
                    'avatar' => $memberInfo['head_img'],
                ];
                if($memberInfo['id'] == session('admin')['id']){
                    $memberList['mine'] = $memberArr;
                    continue;
                }
                $friendList[] = $memberArr;
            }
            $memberList['friend'][0]['list'] = $friendList;
        }
        //获取群组列表
        $groupIdArr = (new SystemAdminGroupRelation())
            ->getAdminGroupIdByAdminId(session('admin')['id']);
        $allGroup = (new SystemAdminGroup())->where('id', 'in', $groupIdArr)
            ->select();
        if($allGroup){
            $allGroupArr = $allGroup->toArray();
            $groupList = [];
            foreach($allGroupArr as $groupInfo){
                $groupList[] = [
                    'groupname' => $groupInfo['title'],
                    'id' => $groupInfo['id'],
                    'avatar' => $groupInfo['group_image'],
                ];
            }
            $memberList['group'] = $groupList;
        }
        $result = [
            'code' => 0,
            'msg' => 'success',
            'data' => $memberList,
        ];
        return json($result);
    }

    /**
     * @NodeAnotation(title="好友信息")
     */
    public function getMemberInfo($id)
    {
        //获取管理员列表
        $adminIdArr = (new SystemAdminGroupRelation())
            ->getAdminIdByGroupId($id);
        $allMember = (new SystemAdmin())->where('status','=',1)
            ->whereIn('id',$adminIdArr)
            ->select();
        $memberList = [
            'owner' => [],
            'members' => '',
            'list' => [],
        ];
        if($allMember){
            $allMemberArr = $allMember->toArray();
            foreach($allMemberArr as $memberInfo){
                $memberArr = [
                    'username' => $memberInfo['nickname'],
                    'id' => $memberInfo['id'],
                    'sign' => $memberInfo['remark'],
                    'avatar' => $memberInfo['head_img'],
                ];
                if($memberInfo['id'] == session('admin')['id']){
                    $memberList['owner'] = $memberArr;
                    //continue;
                }
                $memberList['list'][] = $memberArr;
            }
            $memberList['members'] = count($allMemberArr);
        }
        $result = [
            'code' => 0,
            'msg' => 'success',
            'data' => $memberList,
        ];
        return json($result);
    }

    /**
     * @NodeAnotation(title="上传文件")
     */
    public function upload()
    {
        $data = [
            'upload_type' => $this->request->post('upload_type'),
            'file'        => $this->request->file('file'),
        ];
        $uploadConfig = sysconfig('upload');
        empty($data['upload_type']) && $data['upload_type'] = $uploadConfig['upload_type'];
        $rule = [
            'upload_type|指定上传类型有误' => "in:{$uploadConfig['upload_allow_type']}",
            'file|文件'              => "require|file|fileExt:{$uploadConfig['upload_allow_ext']}|fileSize:{$uploadConfig['upload_allow_size']}",
        ];
        $this->validate($data, $rule);
        try {
            $upload = Uploadfile::instance()
                ->setUploadType($data['upload_type'])
                ->setUploadConfig($uploadConfig)
                ->setFile($data['file'])
                ->save();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        if ($upload['save'] == true) {
            $result = [
                'code' => 0,
                'msg' => $upload['msg'],
                'data' => [
                    'src' => $upload['url'],
                    'name' => trim(strrchr($upload['url'], '/'),'/')
                ],
            ];
        } else {
            $result = [
                'code' => 1,
                'msg' => $upload['msg'],
                'data' => [
                    'src' => '',
                    'name' => ''
                ],
            ];
        }
        return json($result);
    }

    /**
     * @NodeAnotation(title="获取最近消息")
     */
    public function getRecentMsg()
    {
        $data = [
            [
                'id' => 0,
                'content' => '欢迎使用腾旭聊天系统！',
                'uid' => 168,
                'from' => '',
                'from_group' => '',
                'type' => 1,
                'remark' => '',
                'href' => '',
                'read' => 1,
                'time' => '刚刚',
                'user' => [
                    'id' => '',
                ],
            ],
        ];
        $result = [
            'code' => 0,
            'pages' => 1,
            'data' => $data,
        ];
        return json($result);
    }



=======
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
}
