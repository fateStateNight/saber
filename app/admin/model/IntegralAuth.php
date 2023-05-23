<?php

namespace app\admin\model;


use app\common\model\TimeModel;

class IntegralAuth extends TimeModel
{
    private $appId = 'wx87fb4896ee814b3d';

    private $appKey = '4814aea67055c9258fa31ee5f1a3bcfa';

    //获取用户账号信息
    public function getWeiUserAccountInfo()
    {
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $newUri = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            $this->write_log('/tmp/liupeng1.log','callback url : '.$newUri);
            $baseUrl = urlencode($newUri);
            $urlObj["appid"] = $this->appId;
            $urlObj["redirect_uri"] = "$baseUrl";
            $urlObj["response_type"] = "code";
            $urlObj["scope"] = "snsapi_userinfo";//snsapi_base   //snsapi_userinfo
            $urlObj["state"] = "STATE"."#wechat_redirect";
            $buff = "";
            foreach ($urlObj as $k => $v){
                if($k != "sign") $buff .= $k . "=" . $v . "&";
            }
            $bizString = trim($buff, "&");
            $this->write_log('/tmp/liupeng1.log','get code start');
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
            $this->write_log('/tmp/liupeng1.log','the all url: '.$url);
            Header("Location: $url");
            $this->write_log('/tmp/liupeng1.log','get code end');
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $urlObj["appid"] = $this->appId;
            $urlObj["secret"] = $this->appKey;
            $urlObj["code"] = $code;
            $urlObj["grant_type"] = "authorization_code";
            $buff = "";
            foreach ($urlObj as $k => $v){
                if($k != "sign")
                    $buff .= $k . "=" . $v . "&";
            }
            $bizString = trim($buff, "&");
            $this->write_log('/tmp/liupeng1.log','get openid start');
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
            $data = $this->curl_get($url);
            $openid = $data['openid'];
            $this->write_log('/tmp/liupeng1.log','the openid is '.$openid);
            $userInfo = $this->get_user_info($data);
            $this->write_log('/tmp/liupeng1.log','the userInfo is '.serialize($userInfo));
            return $userInfo;
        }
    }

    public function get_user_info($parameter){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$parameter['access_token']."&openid=".$parameter['openid']."&lang=zh_CN";
        $user_info = $this->curl_get($url);
        return $user_info;
    }

    public function curl_get($url){
        $options = array();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $res = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($res,true);
        return $data;
    }

    public function write_log($filename, $content){
        $newContent = date("Y-m-d H:i:s")." ".$content."\r\n";
        file_put_contents($filename,$newContent,FILE_APPEND);
        return true;
    }

}