<?php

namespace app\admin\controller\mall;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use EasyAdmin\tool\CommonTool;
use think\App;

/**
 * @ControllerAnnotation(title="积分记录")
 */
class IntegralRecord extends AdminController
{

    use \app\admin\traits\Curd;

    protected $relationSerach = true;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\MallIntegralRecord();
        
        $this->assign('getMallIntegralGoodsList', $this->model->getMallIntegralGoodsList());

        $this->assign('getSystemWeixinUserList', $this->model->getSystemWeixinUserList());

        $this->assign('getIntegralStatusList', $this->model->getIntegralStatusList());

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
                ->alias('mallIntegralRecord')
                ->join('system_weixin_user systemWeixinUser', 'user_id=systemWeixinUser.id', 'LEFT')
                ->join('mall_integral_goods mallIntegralGoods', 'goods_id=mallIntegralGoods.id', 'LEFT')
                ->where($where)
                ->count();
            $list = [];
            if($count > 0){
                $list = $this->model
                    ->withJoin(['systemWeixinUser','mallIntegralGoods'], 'LEFT')
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select()
                    ->toArray();
                foreach($list as $key=>$userData){
                    $list[$key]['systemWeixinUser']['nickname'] = base64_decode($userData['systemWeixinUser']['nickname']);
                }
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

    protected function buildTableParames($excludeFields = [])
    {
        $get = $this->request->get('', null, null);
        $page = isset($get['page']) && !empty($get['page']) ? $get['page'] : 1;
        $limit = isset($get['limit']) && !empty($get['limit']) ? $get['limit'] : 15;
        $filters = isset($get['filter']) && !empty($get['filter']) ? $get['filter'] : '{}';
        $ops = isset($get['op']) && !empty($get['op']) ? $get['op'] : '{}';
        // json转数组
        $filters = json_decode($filters, true);
        $ops = json_decode($ops, true);
        $where = [];
        $excludes = [];

        // 判断是否关联查询
        $tableName = CommonTool::humpToLine(lcfirst($this->model->getName()));

        foreach ($filters as $key => $val) {
            if($key == 'systemWeixinUser.nickname'){
                $val = base64_encode($val);
            }
            if (in_array($key, $excludeFields)) {
                $excludes[$key] = $val;
                continue;
            }
            $op = isset($ops[$key]) && !empty($ops[$key]) ? $ops[$key] : '%*%';
            if ($this->relationSerach && count(explode('.', $key)) == 1) {
                $key = "{$tableName}.{$key}";
            }
            switch (strtolower($op)) {
                case '=':
                    $where[] = [$key, '=', $val];
                    break;
                case '%*%':
                    $where[] = [$key, 'LIKE', "%{$val}%"];
                    break;
                case '*%':
                    $where[] = [$key, 'LIKE', "{$val}%"];
                    break;
                case '%*':
                    $where[] = [$key, 'LIKE', "%{$val}"];
                    break;
                case 'range':
                    [$beginTime, $endTime] = explode(' - ', $val);
                    $where[] = [$key, '>=', strtotime($beginTime)];
                    $where[] = [$key, '<=', strtotime($endTime)];
                    break;
                default:
                    $where[] = [$key, $op, "%{$val}"];
            }
        }
        return [$page, $limit, $where, $excludes];
    }

    //根据积分的状态修改用户剩余积分
    public function modifyIntegralByStatus($integralStatus,$integralValue,$userId)
    {
        //判断添加数据中的积分状态，如果是已消耗则扣除剩余积分，如果是已通过则增加剩余积分，其余不变
        $systemWeiUser = new \app\admin\model\SystemWeixinUser();
        $modifyResult = ['code' => '000', 'msg' => '', 'result' => 'success',];
        switch ($integralStatus){
            case '0':
                //消耗积分
                $modifyResult = $systemWeiUser->modifyIntegralById($userId,-$integralValue);
                break;
            case '1':
                break;
            case '2':
                $modifyResult = $systemWeiUser->modifyIntegralById($userId,$integralValue);
                break;
            case '3':
                break;
            default:
                break;
        }
        return $modifyResult;
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
                $modifyResult = $this->modifyIntegralByStatus($post['integral_status'],$post['integral_value'],$post['user_id']);
                if($modifyResult['result'] == 'success'){
                    $save = $this->model->save($post);
                }else{
                    $this->error($modifyResult['msg']);
                }
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        $row->isEmpty() && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $modifyResult = $this->modifyIntegralByStatus($post['integral_status'],$post['integral_value'],$post['user_id']);
                if($modifyResult['result'] == 'success'){
                    $save = $row->save($post);
                }else{
                    $this->error($modifyResult['msg']);
                }
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="通过")
     */
    public function accept($id,$userId,$integralValue)
    {
        $row = $this->model->find($id);
        $row->isEmpty() && $this->error('数据不存在');

        try {
            $modifyResult = $this->modifyIntegralByStatus('2',$integralValue,$userId);
            if($modifyResult['result'] == 'success'){
                $row->integral_status = 2;
                $save = $row->save();
            }else{
                $this->error($modifyResult['msg']);
            }
        } catch (\Exception $e) {
            $this->error('保存失败');
        }
        $save ? $this->success('保存成功') : $this->error('保存失败');
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="拒绝")
     */
    public function reject($id)
    {
        $row = $this->model->find($id);
        $row->isEmpty() && $this->error('数据不存在');
        try {
            $row->integral_status = 3;
            $save = $row->save();
        } catch (\Exception $e) {
            $this->error('保存失败');
        }
        $save ? $this->success('保存成功') : $this->error('保存失败');
        $this->assign('row', $row);
        return $this->fetch();
    }

}