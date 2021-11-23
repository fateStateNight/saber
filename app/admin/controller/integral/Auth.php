<?php

namespace app\admin\controller\integral;


use app\admin\model\IntegralAuth;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Auth
 * @package app\admin\controller\integral
 */
class Auth extends AdminController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new IntegralAuth();
        $this->init();
    }

    public function init()
    {
        $url = 'https://'.$_SERVER['SERVER_NAME'].'/manage/integral.exchange/index.html';;
        if(!empty(session('weiUser'))){
            Header("Location: $url");
            exit();
        }
        $weiAccountInfo = $this->model->getWeiUserAccountInfo();
        $saveData = array(
            'nickname' => base64_encode($weiAccountInfo['nickname']),
            'openid' => $weiAccountInfo['openid'],
            'sex' => $weiAccountInfo['sex'],
            'province' => $weiAccountInfo['province'],
            'city' => $weiAccountInfo['city'],
            'headimgurl' => $weiAccountInfo['headimgurl'],
            'create_time' => date("Y-m-d H:i:s"),
        );
        try {
            $systemWeiUserModel = new \app\admin\model\SystemWeixinUser();
            $save = $systemWeiUserModel->save($saveData);
            //$save = $this->model->save($saveData);
        } catch (\Exception $e) {
            $this->error('保存失败:'.$e->getMessage());
        }
        session('weiUser',$saveData);
        Header("Location: $url");
        exit();
    }

    public function index()
    {
        
    }
    
}