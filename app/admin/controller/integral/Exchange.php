<?php

// +----------------------------------------------------------------------
// | App Integral Exchange
// +----------------------------------------------------------------------
// | 与微信公众平台对接
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | 希望顺利吧
// +----------------------------------------------------------------------

namespace app\admin\controller\integral;


use app\admin\model\IntegralExchange;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\Db;

/**
 * Class Exchange
 * @package app\admin\controller\integral
 */
class Exchange extends AdminController
{

    //use \app\admin\traits\Curd;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new IntegralExchange();
    }

    public function init()
    {
        //var_dump(session('weiUser',null));
        //var_dump(session('?weiUser'));
        //session('weiUser',session('weiUser')[0]);
        //var_dump(session('weiUser'));
        //die;
        $systemWeiUserModel = new \app\admin\model\SystemWeixinUser();
        if(session('?weiUser')){
            $weiUserInfo = $systemWeiUserModel->getWeiUserInfoByOpenId(session('weiUser')['openid']);
            $this->assign('weiUserInfo', $weiUserInfo);
            return $weiUserInfo;
        }
        $openInfo = $this->model->getWeiOpenid();
        //用户数据为空的时候重新获取微信用户信息并入库
        $weiUserInfo = $systemWeiUserModel->getWeiUserInfoByOpenId($openInfo['openid']);
        if ($weiUserInfo == null){
            $url = 'https://'.$_SERVER['SERVER_NAME'].'/manage/integral.auth';;
            Header("Location: $url");
            exit();
        }
        if($weiUserInfo['status'] == 0){
            $this->error('对不起，您的账号异常！');
        }
        session('weiUser',$weiUserInfo);
        /*$weiUserInfo = [
            'id' => 1,
            'nickname' => '哟呵',
            'openid' => 'o7kes6s_9t8QS8M3GRevsX4utCAg',
            'sex' => '1',
            'province' => '湖北',
            'city' => '武汉',
            'headimgurl' => 'https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqIvTBDbRkpia1EiabIwN6aTqibCXrWhQObs87jLlFBpdFbuhS43U1mTQiav2xzLOIVhbPH63VKkZXBag/132',
            'integral' => 20,
        ];*///var_dump($weiUserInfo);die;
        $this->assign('weiUserInfo', $weiUserInfo);
        return $weiUserInfo;
    }

    public function index()
    {
        $this->init();
        $integralRecordObj = new \app\admin\model\MallIntegralRecord();
        $awardList = $integralRecordObj->getMallSellGoodsList(1);
        $this->assign('awardList',$awardList);
        return $this->fetch();
    }

    
    //生成兑换的礼品
    public function createAward()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [
                'item_id|商品ID' => 'require',
            ];
            $this->validate($post, $rule);
            $systemWeiUserModel = new \app\admin\model\SystemWeixinUser();
            $weiUserInfo = $systemWeiUserModel->getWeiUserInfoById($post['user_id']);
            if($weiUserInfo['integral'] < $post['goods_integral']){
                $this->error('您的积分不足！');
            }
            //手动设定联盟账号ID
            $account_id = 12;
            //获取淘宝联盟账号信息
            $systemAccountModel = new \app\admin\model\SystemTaobaoAccount();
            $accountInfo = $systemAccountModel->getTaoBaoAccountInfo($account_id);
            //调用接口生成淘礼金商品
            $taolijinCreateObj = new \app\admin\model\MallTaolijinGoods();
            $parameter = [
                'account_id' => $account_id,
                'campaign_type' => 'MKT',
                'item_id' => $post['item_id'],
                'total_num' => 1,
                'per_user_num' => 1,
                'per_face' => $post['goods_price']?$post['goods_price']:0,
                'send_start_time' => date('Y-m-d H:i:s'),
                'send_end_time' => date('Y-m-d H:i:s',strtotime('1 hour')),
                'use_start_time' => date('Y-m-d H:i:s'),
                'use_end_time' => date('Y-m-d H:i:s',strtotime('1 hour')),
            ];
            $taolijinData = $taolijinCreateObj->creatTaolijinGoods($accountInfo,$parameter);
            if ($taolijinData['result']['success'] != 1){
                $this->error($taolijinData['result']['msg_info']);
                return false;
            }
            //将生成的淘礼金商品信息中的链接转换成口令
            $shortPwdData = $taolijinCreateObj->transferToPwd($accountInfo,$taolijinData['result']['model']['send_url']);
            //口令转换
            $short_link = "0(".mb_substr($shortPwdData['data']['password_simple'],1,-1).")/";
            //将参数与淘礼金数据结合，整理成入库数据
            $parameter['goods_link'] = $short_link;
            $parameter['goods_content'] = json_encode($taolijinData['result'],true);
            $parameter['title'] = $post['title'];
            $parameter['image'] = $post['image'];
            $parameter['mode'] = 3;
            try {
                //$save = $taolijinCreateObj->save($parameter);
                $createId = $taolijinCreateObj->insertGetId($parameter);
            } catch (\Exception $e) {
                $this->error('创建失败:'.$e->getMessage());
            }

            //新增积分记录
            $integralRecord = [
                'goods_id' => $post['goods_id'],
                'taolijin_goods_id' => $createId,//
                'record_title' => '兑换'.$post['title'].'商品',
                'integral_status' => 0,
                'user_id' => $post['user_id'],
                'integral_value' => $post['goods_integral'],
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ];
            $integralRecordObj = new \app\admin\model\MallIntegralRecord();
            try {
                //$recordResult = $integralRecordObj->save($integralRecord);
                $recordId = $integralRecordObj->insertGetId($integralRecord);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            //修改用户剩余积分
            $systemWeiUser = new \app\admin\model\SystemWeixinUser();
            $modifyResult = $systemWeiUser->modifyIntegralById($post['user_id'],-$post['goods_integral']);
            if($modifyResult['result'] == 'success' && $createId && $recordId){
                $result = [
                    'shortLink' => $short_link,
                    'title' => $post['title'],
                    'image' => $post['image']
                ];
                $this->success('兑换成功',$result);
            }else{
                $this->error('兑换失败');
            }
        }
        return $this->fetch();
    }


}