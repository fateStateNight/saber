<?php

namespace app\admin\controller\publish;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use EasyAdmin\tool\CommonTool;
use think\App;

/**
 * @ControllerAnnotation(title="微信社群")
 */
class WeixinGroups extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\PublishWeixinGroups();
        
        $this->assign('getSystemAdminList', $this->model->getSystemAdminList());

        $this->assign('getSourceTypeList', $this->model->getSourceTypeList());

        $this->assign('getGroupTypeList', $this->model->getGroupTypeList());

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
            $list = [];
            if(session('admin')['auth_ids'] == 7 || session('admin')['auth_ids'] == 1){
                $count = $this->model
                    ->withJoin('systemAdmin', 'LEFT')
                    ->where($where)
                    ->count();
                if($count > 0){
                    $list = $this->model
                        ->withJoin('systemAdmin', 'LEFT')
                        ->where($where)
                        ->page($page, $limit)
                        ->order($this->sort)
                        ->select()
                        ->toArray();
                    foreach ($list as $key=>$userInfo){
                        $list[$key]['weixin_room_name'] = base64_decode($userInfo['weixin_room_name']);
                    }
                }
            }else{
                $count = $this->model
                    ->withJoin('systemAdmin', 'LEFT')
                    ->where($where)
                    ->where('belong_id','=',session('admin')['id'])
                    ->count();
                if($count > 0){
                    $list = $this->model
                        ->withJoin('systemAdmin', 'LEFT')
                        ->where($where)
                        ->where('belong_id','=',session('admin')['id'])
                        ->page($page, $limit)
                        ->order($this->sort)
                        ->select()
                        ->toArray();
                    foreach ($list as $key=>$userInfo){
                        $list[$key]['weixin_room_name'] = base64_decode($userInfo['weixin_room_name']);
                    }
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
            if($key == 'weixin_room_name'){
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

}