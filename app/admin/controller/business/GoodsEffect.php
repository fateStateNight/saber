<?php

namespace app\admin\controller\business;

use app\admin\model\BusinessGoods;
use app\admin\model\BusinessScene;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Cache;

/**
 * @ControllerAnnotation(title="商品推广效果")
 */
class GoodsEffect extends AdminController
{

    use \app\admin\traits\Curd;

    public $goodsModel;

    public $sceneModel;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\BusinessGoodsEffect();

        $this->goodsModel = new \app\admin\model\BusinessGoods();

        $this->sceneModel = new \app\admin\model\BusinessScene();
    }
    /**
     * @NodeAnotation(title="商品推广效果统计")
     */
    public function index()
    {
        $id= $this->request->get('id');
        $goodsRow = $this->goodsModel->find($id)->toArray();
        $urlArr = $this->model->getParams($goodsRow['auctionUrl']);
        $goodsRow['itemId'] = $urlArr['id'];
        $diffDay = abs(round((strtotime($goodsRow['endTime'])-strtotime($goodsRow['startTime'])) / 86400));
        $goodsRow['diffDay'] = $diffDay;
        $this->assign('goodsRow', $goodsRow);

        $effectData = $this->model->where('goods_id',$id)->select()->toArray();
        //判断是否更新效果数据，根据第一条数据判断，如果与当前时间相差半小时，则重新更新数据
        if($effectData != null){
            $diffTime = time() - strtotime($effectData[0]['update_time']);
            if($diffTime > 1800){
                $this->updateGoodsPublishEffect($id);
                $effectData = $this->model->where('goods_id',$id)->select()->toArray();
            }
        }else{
            $this->updateGoodsPublishEffect($id);
            $effectData = $this->model->where('goods_id',$id)->select()->toArray();
        }
        $this->assign('effectData',$effectData);

        /*if ($this->request->isAjax()) {
            if (input('selectFieds')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }*/
        return $this->fetch();
    }

    //更新推广效果数据
    public function updateGoodsPublishEffect($goodsId)
    {
        //获取淘宝联盟账号信息
        $accountInfo = $this->sceneModel->getALiAccountInfo();
        //检测联盟账号登录是否有效
        if($accountInfo == []){
            return false;
        }
        //初始化在线账号变量
        $accountArr = [];
        foreach($accountInfo as $key=>$account){
            $onlineInfo = $this->sceneModel->getOnlineAccountInfo($account['token'], $account['cookies']);
            if($onlineInfo && array_key_exists('data', $onlineInfo)){
                $accountArr = $account;
            }
        }
        if($accountArr == null){
            return false;
        }
        //获取指定的商品数据
        $goodsInfo = $this->goodsModel
            ->withJoin('businessScene', 'LEFT')
            ->where('business_goods.id',$goodsId)
            ->select()->toArray();
        $ret = $this->model->sycGoodsPublishEffectInfoToDB($accountArr, $goodsInfo);
        return $ret;
    }

    //返回当前的毫秒时间戳
    public function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

    
}




