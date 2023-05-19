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


use app\admin\model\IntegralPerson;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="个人中心")
 * Class Person
 * @package app\admin\controller\integral
 */
class Person extends AdminController
{

    //use \app\admin\traits\Curd;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];

    public $integralRecord;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new IntegralPerson();
        $this->integralRecord = new \app\admin\model\MallIntegralRecord();
    }

    public function index()
    {
        if (!session('?weiUser')){
            $url = 'https://'.$_SERVER['SERVER_NAME'].'/manage/integral.exchange/index.html';
            Header("Location: $url");
            exit();
        }
        $systemWeiUserModel = new \app\admin\model\SystemWeixinUser();
        $weiUserInfo = $systemWeiUserModel->getWeiUserInfoByOpenId(session('weiUser')['openid']);
        $this->assign('userInfo', $weiUserInfo);
        /*$this->assign('userInfo', [
            'nickname' => '哟呵',
            'openid' => 'o7kes6s_9t8QS8M3GRevsX4utCAg',
            'sex' => '1',
            'province' => '湖北',
            'city' => '武汉',
            'headimgurl' => 'https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqIvTBDbRkpia1EiabIwN6aTqibCXrWhQObs87jLlFBpdFbuhS43U1mTQiav2xzLOIVhbPH63VKkZXBag/132',
            'integral' => 20,
        ]);
        $weiUserInfo[0]['id'] = 1;*/
        //获取积分记录列表
        $recordList = $this->integralRecord->getIntegralRecordRecent($weiUserInfo['id']);//
        $recordData = $this->integralRecord->getApplyRecordNum($weiUserInfo['id']);
        $recordArr = $this->dealWithIntegralData($recordData);
        $this->assign('recordList',$recordList);
        $this->assign('applyNum',$recordArr[1]);
        $this->assign('consumeNum',$recordArr[0]);

        $sellGoodsList = $this->integralRecord->getMallSellGoodsList(0);
        //组合下拉选项数据
        $goodsSelect = [];
        $goodsData = [];
        if($sellGoodsList != null){
            foreach($sellGoodsList as $goodsInfo){
                $goodsSelect[$goodsInfo['id']] = $goodsInfo['title'];
                $goodsData[$goodsInfo['id']] = $goodsInfo;
            }
        }
        $this->assign('goodsSelect',$goodsSelect);
        $this->assign('goodsData',json_encode($goodsData,true));
        return $this->fetch();
    }

    //处理积分状态记录数据
    private function dealWithIntegralData($recordData){
        if($recordData == null){
            return [0,0,0,0];
        }
        $newRecordData = [0,0,0,0];
        foreach($recordData as $key=>$record){
            $newRecordData[$record['integral_status']] = $record['recordNum'];
        }
        return $newRecordData;
    }


    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $this->integralRecord->save($post);
                //$save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('申请失败:'.$e->getMessage());
            }
            $save ? $this->success('申请成功',[],'integral.person/index') : $this->error('申请失败');
        }
        /*$weiUserInfo = session('weiUser');
        $this->assign('userInfo', $weiUserInfo);*/
        /*$this->assign('userInfo', [
            'id' => 1,
            'nickname' => '哟呵',
            'openid' => 'o7kes6s_9t8QS8M3GRevsX4utCAg',
            'sex' => '1',
            'province' => '湖北',
            'city' => '武汉',
            'headimgurl' => 'https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqIvTBDbRkpia1EiabIwN6aTqibCXrWhQObs87jLlFBpdFbuhS43U1mTQiav2xzLOIVhbPH63VKkZXBag/132',
            'integral' => 20,
        ]);*/
        /*$sellGoodsList = $this->integralRecord->getMallSellGoodsList(0);
        //组合下拉选项数据
        $goodsSelect = [];
        $goodsData = [];
        if($sellGoodsList != null){
            foreach($sellGoodsList as $goodsInfo){
                $goodsSelect[$goodsInfo['id']] = $goodsInfo['title'];
                $goodsData[$goodsInfo['id']] = $goodsInfo;
            }
        }
        $this->assign('goodsSelect',$goodsSelect);
        $this->assign('goodsData',json_encode($goodsData,true));*/
        return $this->fetch();
    }

}