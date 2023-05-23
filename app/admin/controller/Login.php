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

namespace app\admin\controller;


use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use think\captcha\facade\Captcha;
use think\facade\Env;

/**
 * Class Login
 * @package app\admin\controller
 */
class Login extends AdminController
{

    /**
     * 初始化方法
     */
    public function initialize()
    {
        parent::initialize();
        $action = $this->request->action();
        if (!empty(session('admin')) && !in_array($action, ['out'])) {
            $adminModuleName = config('app.admin_alias_name');
            $this->success('已登录，无需再次登录', [], __url("@{$adminModuleName}"));
        }
    }

    /**
     * 用户登录
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        $captcha = Env::get('easyadmin.captcha', 1);
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [
                'username|用户名'      => 'require',
                'password|密码'       => 'require',
                'keep_login|是否保持登录' => 'require',
            ];
            $captcha == 1 && $rule['captcha|验证码'] = 'require|captcha';
            $this->validate($post, $rule);
            $admin = SystemAdmin::where(['username' => trim($post['username'],' ')])->find();
            if (empty($admin)) {
                $this->error('用户不存在');
            }
            if (password($post['password']) != $admin->password) {
                $this->error('密码输入有误');
            }
            if ($admin->status == 0) {
                $this->error('账号已被禁用');
            }
            $admin->login_num += 1;
            $admin->save();
            $admin = $admin->toArray();
            unset($admin['password']);
            $admin['expire_time'] = $post['keep_login'] == 1 ? true : time() + 3600*12;
            session('admin', $admin);
            $this->success('登录成功');
        }
        $this->assign('captcha', $captcha);
        $this->assign('demo', $this->isDemo);
        return $this->fetch();
    }

    /**
     * 用户退出
     *
     */
    public function out()
    {
        session('admin', null);
        $this->success('退出登录成功');
    }

    /**
     * 验证码
     * @return \think\Response
     */
    public function captcha()
    {
        return Captcha::create();
    }

    /**
     * 淘宝回调地址请求
     *
     */
    public function getCode()
    {
        if (!isset($_GET['code'])){
            $url = $_SERVER['REQUEST_URI'];
        }else{
            $url = $_SERVER['REQUEST_URI'];
        }
        $this->write_log('/tmp/taobaoTest.log', $url);
        $code = $_GET['code'];
        $url = 'https://oauth.taobao.com/token';
        $postfields= array('grant_type'=>'authorization_code',
            'client_id'=>'31602444',
            'client_secret'=>'fc0835326f7ce0d1e66d7d2de7582434',
            'code'=>$code,
            'redirect_uri'=>'https://www.childrendream.cn/manage/login/getToken');
        $post_data = '';
        foreach($postfields as $key=>$value){
            $post_data .="$key=".urlencode($value)."&";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //指定post数据
        curl_setopt($ch, CURLOPT_POST, true);
        //添加变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, substr($post_data,0,-1));
        $output = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->write_log('/tmp/taobaoTest.log', $httpStatusCode);
        curl_close($ch);
        $this->write_log('/tmp/taobaoTest.log', $output);
    }

    public function getToken()
    {
        $url = $_SERVER['REQUEST_URI'];
        $this->write_log('/tmp/taobaoTest.log', $url);
    }

    public function write_log($filename, $content){
        $newContent = date("Y-m-d H:i:s")." ".$content."\r\n";
        file_put_contents($filename,$newContent,FILE_APPEND);
        return true;
    }

    public function getEmailResult()
    {
        $url = 'https://api.nbhao.org/v1/email/verify';
        $post_data = array(
            'email' => '708079461@qq.com'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //指定post数据
        curl_setopt($ch, CURLOPT_POST, true);
        //添加变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        var_export($httpStatusCode);
        curl_close($ch);
        var_export($output);
    }

    public function getQQStatus()
    {
        $online_gif = array(
            'http://pub.idqqimg.com/qconn/wpa/button/button_old_40.gif', // 离线
            'http://pub.idqqimg.com/qconn/wpa/button/button_old_41.gif' // 在线
        );
        $url = 'http://webpresence.qq.com/getonline?Type=1&1921026512';
        $ch = curl_init($url); // 返回online[0]=1; 0表示离线1表示在线
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        var_export($result);die;
// preg_match("/online\[0\]=(\d+);/isu", $result, $match);
        $flag = substr($result, -2, 1);
        echo $online_gif[$flag];
    }

}
