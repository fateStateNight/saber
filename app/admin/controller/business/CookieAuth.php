<?php

namespace app\admin\controller\business;


use app\admin\model\BusinessCookieAuth;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class CookieAuth
 * @package app\admin\controller\business
 */
class CookieAuth extends AdminController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new BusinessCookieAuth();
    }

    public function index()
    {
        $userName = $_POST['user_name'];
        $cookieContent = $_POST['cookies'];
        $this->write_log('/tmp/cookies.log',json_encode($_POST));
        if($userName == 'undefined'){
            return false;
        }
        $cookieArr = explode(';', $cookieContent);
        $x5sec = '';
        if($cookieArr){
            foreach($cookieArr as $item){
                $itemArr = explode('=',$item);
                if($itemArr[0] == 'x5sec'){
                    $x5sec = $itemArr[1];
                    break;
                }
            }
        }
        //var_dump($cookieContent);die;
        $taobaoModel = new \app\admin\model\SystemTaobaoAccount();
        $data = [
            'cookies' => $cookieContent,
        ];
        if($x5sec != ''){
            $data['x5sec'] = $x5sec;
        }/*else{
            //从数据库中读取秘钥并拼接到cookie中
            $x5sec = $taobaoModel->where('user_name',$userName)->value('x5sec');
            $cookieContent .= 'x5sec='.$x5sec.';';
            $data = [
                'user_name' => $userName,
                'cookies' => $cookieContent,
            ];
        }*/
        $ret = $taobaoModel->where('user_name',$userName)->update($data);
        $this->write_log('/tmp/cookies.log',json_encode($ret));
        return $ret;
    }

    public function updateCookies()
    {
        /*$userName = $_POST['user_name'];
        $cookieContent = $_POST['cookies'];
        $cookieArr = explode(';',$cookieContent);
        if($cookieArr != ''){
            foreach($cookieArr as $item){
                $itemArr = explode('=',$item);
                if($itemArr[0] == '_tb_token_'){
                    $token = $itemArr[1];
                    break;
                }
            }
        }
        $taobaoModel = new \app\admin\model\SystemTaobaoAccount();
        $accountInfo = $taobaoModel->where('user_name', $userName)->select()->toArray();
        $sourceCookies = json_decode($accountInfo['cookies'], true);
        if(array_key_exists('cookies', $sourceCookies)){
            $sourceCookies['cookies'][$token] = $cookieContent;
        }else{
            
        }
        $data = [
            'user_name' => $userName,
            'cookies' => $cookieContent
        ];
        $ret = $taobaoModel->where('user_name',$userName)->update($data);
        $this->write_log('/tmp/cookies.log',json_encode($ret));
        return $ret;*/
    }

    function write_log($filename, $content){
        $newContent = date("Y-m-d H:i:s")." ".$content."\r\n";
        file_put_contents($filename,$newContent,FILE_APPEND);
        return true;
    }
    
}