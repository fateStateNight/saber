<?php

namespace app\admin\controller\mall;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="淘礼金商品")
 */
class TaolijinGoods extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\MallTaolijinGoods();

        $this->assign('getSystemTaobaoAccountList', $this->model->getSystemTaobaoAccountList());

        $this->assign('getCampaignTypeList', $this->model->getCampaignTypeList());

        $this->assign('getItemStatusList', $this->model->getItemStatusList());

        $this->assign('getModeList', $this->model->getModeList());

        $this->assign('getStatusList', $this->model->getStatusList());

    }

    
    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFieds')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->withJoin('systemTaobaoAccount', 'LEFT')
                ->where($where)
                ->count();
            $list = $this->model
                ->withJoin('systemTaobaoAccount', 'LEFT')
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
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="添加")
     * 创建淘礼金商品
     * 先调用接口生成淘礼金数据，然后整合数据入库
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [
                'item_id|商品ID' => 'require',
            ];
            //清除多余字段
            unset($post['file']);
            $this->validate($post, $rule);
            //获取淘宝联盟账号信息
            $systemAccountModel = new \app\admin\model\SystemTaobaoAccount();
            $accountInfo = $systemAccountModel->getTaoBaoAccountInfo($post['account_id']);
            //print_r($accountInfo);die;
            //调用接口生成淘礼金商品
            $taolijinData = $this->model->creatTaolijinGoods($accountInfo,$post);
            //判断创建是否成功
            if ($taolijinData['result']['success'] != 1){
                return $this->error($taolijinData['result']['msg_info']);
            }

            //将生成的淘礼金商品信息中的链接转换成口令
            $shortPwdData = $this->model->transferToPwd($accountInfo,$taolijinData['result']['model']['send_url']);
            //口令转换
            $short_link = "0(".mb_substr($shortPwdData['data']['password_simple'],1,-1).")/";

            //将参数与淘礼金数据结合，整理成入库数据
            $post['goods_link'] = $short_link;
            $post['goods_content'] = json_encode($taolijinData['result'],true);
//var_dump($post);die;
            try {
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="商品信息")
     * 获取淘礼金商品信息
     */
    public function getGoodsInfo()
    {
        $item_id = input('get.item_id');
        //判断是否是链接
        $goods_id = '';
        if(strpos($item_id, 'http') !== false){
            $getItemInfo = $this->model->analysisItemId($item_id);
            if($getItemInfo['code'] == 0 && array_key_exists('data', $getItemInfo) && array_key_exists('goodsId', $getItemInfo['data'])){
                $goods_id = $getItemInfo['data']['goodsId'];
            }
        }
        if($goods_id != null){
            $item_id = $goods_id;
        }
        $data = $this->model->getGoodsInfo($item_id);
        $result = [
            'code'  => 0,
            'msg'   => '',
            'count' => 1,
            'data'  => $data,
            'item_id' => $item_id,
        ];
        return json($result);
    }

}