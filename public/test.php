<?php


$parameter = $_GET;

/*if (!array_key_exists('state',$parameter)){
    get_openid();
}elseif($parameter['code'] != null){
    //echo "456<br/>";
    error_log(serialize($parameter),2,'/tmp/liupeng.log');
    jump();
}
var_dump($parameter);die;*/
//file_put_contents('/tmp/liupeng1.log',date('Y-m-d H:i:s').' ceshi ceshi',FILE_APPEND);
//error_log(date('Y-m-d H:i:s').' ceshi ceshi',2,'/tmp/liupeng.log');


var_dump(123);die;
$content = $_POST['cookies'];
write_log('/tmp/cookies.log', $content);



function write_log($filename, $content){
    $newContent = date("Y-m-d H:i:s")." ".$content."\r\n";
    file_put_contents($filename,$newContent,FILE_APPEND);
    return true;
}
//获取用户微信openid
function GetOpenid(){
    //$appid = env('WEIXIN_KEY');
    //$appKey = env('WEIXIN_SECRET');
    $appid = 'wx87fb4896ee814b3d';
    $appKey = '4814aea67055c9258fa31ee5f1a3bcfa';
    //通过code获得openid
    if (!isset($_GET['code'])){
        //触发微信返回code码
        $scheme = $_SERVER['HTTPS']=='on' ? 'https://' : 'http://';
        $uri = $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
        if($_SERVER['REQUEST_URI']) $uri = $_SERVER['REQUEST_URI'];
        //$newUri = $scheme.$_SERVER['HTTP_HOST'].$uri;
        $newUri = 'https://www.childrendream.cn/test.php';
        write_log('/tmp/liupeng1.log','callback url : '.$newUri);
        $baseUrl = urlencode($newUri);
        $urlObj["appid"] = $appid;
        $urlObj["redirect_uri"] = "$baseUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_userinfo";//snsapi_base
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $buff = "";
        foreach ($urlObj as $k => $v){
            if($k != "sign") $buff .= $k . "=" . $v . "&";
        }
        $bizString = trim($buff, "&");
        write_log('/tmp/liupeng1.log','get code start');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
        write_log('/tmp/liupeng1.log','the all url: '.$url);
        Header("Location: $url");
        write_log('/tmp/liupeng1.log','get code end');
        exit();
    } else {
//获取code码，以获取openid
        $code = $_GET['code'];
        $urlObj["appid"] = $appid;
        $urlObj["secret"] = $appKey;
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $buff = "";
        foreach ($urlObj as $k => $v){
            if($k != "sign")
                $buff .= $k . "=" . $v . "&";
        }
        $bizString = trim($buff, "&");
        write_log('/tmp/liupeng1.log','get openid start');
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
        $data = curl_get($url);
        $openid = $data['openid'];
//dd($openid);
        write_log('/tmp/liupeng1.log','the openid is '.$openid);
        $userInfo = get_user_info($data);
        write_log('/tmp/liupeng1.log','the userInfo is '.serialize($userInfo));
        return $userInfo;
    }
}

function get_user_info($parameter){
    $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$parameter['access_token']."&openid=".$parameter['openid']."&lang=zh_CN";
    $user_info = curl_get($url);
    return $user_info;
}

write_log('/tmp/liupeng1.log','the code is start');
write_log('/tmp/liupeng1.log','the parameter is :'.serialize($_GET));
//file_put_contents('/tmp/liupeng1.log',date('Y-m-d H:i:s').' the code is start'."\r\n",FILE_APPEND);
GetOpenid();
write_log('/tmp/liupeng1.log','the code is over');
//file_put_contents('/tmp/liupeng1.log',date('Y-m-d H:i:s').' the code is over'."\r\n",FILE_APPEND);

function jump()
{
    $appid = 'wx87fb4896ee814b3d';
    $secret = '4814aea67055c9258fa31ee5f1a3bcfa';
    $code = $_GET['code'];//获取code
    //$state = $_GET['state']; //获取参数
    $weixin =  file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code");//通过code换取网页授权access_token
    $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
    $array = get_object_vars($jsondecode);//转换成数组

    $openid = $array['openid'];//输出openid

    if ($openid) {
        //你的业务逻辑
        //跳转
        echo 'success||||||'.$openid;
        //header('Location:http://www.baidu.com');
    }
}

function get_openid()
{
    $state = 'STATE';
    $appid = 'wx87fb4896ee814b3d';
    $redirect_uri = urlencode('https://www.childrendream.cn/test.php?state=222');//对url处理，此url为访问上面jump方法的url
    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?
    appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=$state#wechat_redirect";
    header('Location:' . $url);
}

function curl_get($url){
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
    write_log('/tmp/liupeng1.log','get openid end :'.$res);
    $data = json_decode($res,true);
    return $data;
}

