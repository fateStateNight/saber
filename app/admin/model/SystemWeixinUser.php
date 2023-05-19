<?php

namespace app\admin\model;

use app\common\model\TimeModel;
use think\Log;

class SystemWeixinUser extends TimeModel
{

    protected $name = "system_weixin_user";

    protected $deleteTime = "delete_time";

    
    
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }

    public function publishWeixinGroups()
    {
        return $this->belongsTo('\app\admin\model\PublishWeixinGroups', 'group_id', 'id');
    }

    public function getPublishWeixinGroupsList()
    {
        return \app\admin\model\PublishWeixinGroups::column('title', 'id');
    }

    public function getWeiUserInfoById($Id)
    {
        $userInfo = SystemWeixinUser::find($Id);
        if($userInfo == null){
            return null;
        }
        $userArr = $userInfo->toArray();
        $userArr['nickname'] = base64_decode($userArr['nickname']);
        return $userArr;
    }

    public function getWeiUserInfoByOpenId($openId)
    {
        $result = SystemWeixinUser::where(['openid'=>$openId])->find();
        if ($result == null){
            return null;
        }
        $userInfo = $result->toArray();
        $userInfo['nickname'] = base64_decode($userInfo['nickname']);
        return $userInfo;
    }

    //修改微信用户表中剩余积分值
    public function modifyIntegralById($Id,$modifyValue)
    {
        $userInfo = SystemWeixinUser::find($Id);
        if($userInfo == null){
            return [
                'code' => '100',
                'msg' => 'the user is null',
                'result' => 'error',
            ];
        }
        $userArr = $userInfo->toArray();
        if($userArr['integral'] < 0){
            return [
                'code' => '101',
                'msg' => 'the integral of user is null',
                'result' => 'error',
            ];
        }
        $currentIntegral = $userArr['integral']+$modifyValue;
        $userInfo->integral = $currentIntegral;
        $result = $userInfo->save();
        if($result){
            //更新会话数据信息
            //todo
            /*$weiUserInfo = session('weiUser');
            file_put_contents('/tmp/liupeng1.log',json_encode($weiUserInfo),FILE_APPEND);
            $weiUserInfo['integral'] = $currentIntegral;
            file_put_contents('/tmp/liupeng1.log',json_encode($weiUserInfo),FILE_APPEND);
            session('weiUser',$weiUserInfo);*/
            return [
                'code' => '000',
                'msg' => '',
                'result' => 'success',
            ];
        }else{
            return [
                'code' => '102',
                'msg' => 'modify data is error',
                'result' => 'error',
            ];
        }
    }



}