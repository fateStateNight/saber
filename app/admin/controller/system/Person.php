<?php
// +----------------------------------------------------------------------


namespace app\admin\controller\system;


use app\admin\model\SystemAdmin;
use app\admin\model\SystemAdminGroup;
use app\admin\model\SystemAdminGroupRelation;
use app\admin\model\SystemPerson;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use EasyAdmin\upload\Uploadfile;
use think\App;

/**
 * @ControllerAnnotation(title="个人空间")
 * Class Person
 * @package app\admin\controller\system
 */
class Person extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemPerson();
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        $this->assign('user_id',session('admin')['id']);
        return $this->fetch();
    }

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
<<<<<<< HEAD
            $groupList = [];
=======
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
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

}