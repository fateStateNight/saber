<?php

// +----------------------------------------------------------------------
// | App Integral Exchange
// +----------------------------------------------------------------------
// | 独立页面
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | 不限制登录状态
// +----------------------------------------------------------------------

namespace app\admin\controller\integral;


use app\admin\model\IntegralContact;
use app\common\controller\AdminController;
//use EasyAdmin\annotation\ControllerAnnotation;
//use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Log;
use think\response\Json;

/**
 * Class Draw
 * @package app\admin\controller\integral
 */
class Contact extends AdminController
{

    //use \app\admin\traits\Curd;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new IntegralContact();
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            try {
                var_dump($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $this->success('保存成功');
        }
        return $this->fetch();
    }

    public function searchContact()
    {
        $parameter = $this->request->get();
        if(!$parameter['title']){
            $ret = [
                'code' => 1,
                'msg' => '搜索内容不能为空',
                'data' => ''
            ];
            return json($ret);
        }
        $adminObj = new \app\admin\model\SystemAdmin();
        //判断是否是手机号
        if(preg_match("/^1[3456789]\d{9}$/", $parameter['title'])){
            //查询手机号
            $result = $adminObj->where('phone_number','like','%'.$parameter['title'].'%')->find();
        }else{
            //查询微信号
            $result = $adminObj->where('weixin_number','like','%'.$parameter['title'].'%')->find();
        }
        if($result){
            $ret = [
                'code' => 0,
                'msg' => '此信息属于本公司员工的联系方式',
                'data' => ''
            ];
        }else{
            $ret = [
                'code' => 2,
                'msg' => '此信息未知，不属于本公司员工信息',
                'data' => ''
            ];
        }
        return json($ret);
    }

}