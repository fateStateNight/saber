<?php


namespace app\admin\controller\mall;


use app\admin\model\MallGoods;
use app\admin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\admin\controller\mall
 * @ControllerAnnotation(title="商城商品管理")
 */
class Goods extends AdminController
{

    use Curd;

    protected $relationSerach = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new MallGoods();

        $this->assign('adminInfo', session('admin'));

        $this->assign('getStatusList', $this->model->getPublishStatus());
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
            if(session('admin')['auth_ids'] == 9){
                //判断为招商角色
                $count = $this->model
                    ->withJoin(['cate','creater','admin'], 'LEFT')
                    ->where(['creater.id'=>session('admin')['id']])
                    ->where($where)
                    ->count();
                $list = $this->model
                    ->withJoin(['cate','creater','admin'], 'LEFT')
                    ->where(['creater.id'=>session('admin')['id']])
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select();
            }else{
                //elseif(session('admin')['auth_ids'] == 7 || session('admin')['auth_ids'] == 10)
                //判断为超级管理员或者推广管理员
                $count = $this->model
                    ->withJoin(['cate','creater','admin'], 'LEFT')
                    ->where($where)
                    ->count();
                $list = $this->model
                    ->withJoin(['cate','creater','admin'], 'LEFT')
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select();
            }

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
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $post['creater_id'] = session('admin')['id'];
                $post['status'] = 0;
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            if($save){
                $this->sendDingDingMsg($post);
                $this->success('保存成功');
            }else{
                $this->error('保存失败');
            }
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="推广操作")
     */
    public function publish($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                //$post['total_stock'] = $row->total_stock + $post['stock'];
                //$post['stock'] = $row->stock + $post['stock'];
                $post['admin_id'] = session('admin')['id'];
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('推广失败');
            }
            $save ? $this->success('推广成功') : $this->error('推广失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    //钉钉提醒
    public function sendDingDingMsg($goodsInfo)
    {
        //获取任务信息
        $name = session('admin')['nickname'];
        $message = "### ".$goodsInfo['title']."\n  商品负责人：".$name."\n  "
            ."\n  商品链接：<".$goodsInfo['goods_link'].">\n  "
            ."\n  优惠券链接：<".$goodsInfo['coupon_link'].">\n  "
            ."\n  ![商品图片](".$goodsInfo['images'].")\n  "
            ."\n  券后价：".$goodsInfo['discount_price']."\n  "
            ."\n  备注：".$goodsInfo['remark']."\n  ";
        $webhook = "https://oapi.dingtalk.com/robot/send?access_token=d61f7604aa6dfc87d2052d19ed4b29d24463ffdbfe6257068e4e6fad0cf1d4c5";
        $data = array (
            'msgtype' => 'markdown',
            'markdown' => array(
                'title' => $goodsInfo['title'],
                'text' => $message,
            ),
            'at' => array (
                'isAtAll' => false,
            )
        );
        $data_string = json_encode($data);
        $result = $this->request_by_curl($webhook, $data_string);
        return $result;
    }

    public function request_by_curl($remote_server, $post_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}