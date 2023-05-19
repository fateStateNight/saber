<?php

namespace app\admin\controller\system;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="system_script_task")
 */
class ScriptTask extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\SystemScriptTask();
        
        $this->assign('getTaskStatusList', $this->model->getTaskStatusList());

        $this->assign('getTypeList', $this->model->getTypeList());

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
            if(session('admin')['auth_ids'] == 7 || session('admin')['auth_ids'] == 1){
                $count = $this->model
                    ->withJoin('systemAdmin', 'LEFT')
                    ->where($where)
                    ->where('relation_id', '=', 0)
                    ->count();
                $list = $this->model
                    ->withJoin('systemAdmin', 'LEFT')
                    ->where($where)
                    ->where('relation_id', '=', 0)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select()
                    ->toArray();
            }else{
                $count = $this->model
                    ->withJoin('systemAdmin', 'LEFT')
                    ->where('system_script_task.creater_id', session('admin')['id'])
                    ->where($where)
                    ->where('relation_id', '=', 0)
                    ->count();
                $list = $this->model
                    ->withJoin('systemAdmin', 'LEFT')
                    ->where('system_script_task.creater_id', session('admin')['id'])
                    ->where($where)
                    ->where('relation_id', '=', 0)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select()
                    ->toArray();
            }
            if($count > 0){
                foreach($list as $keyNum=>$data){
                    if($data['type'] == 2){
                        $taskContent = json_decode($data['task_content'], true);
                        if(array_key_exists('file_url', $taskContent) && ($data['create_time'] > date('Y-m-d H:i:s', strtotime('-1 month')))){
                            $list[$keyNum]['file_url'] = $taskContent['file_url'];
                        }else{
                            $list[$keyNum]['file_url'] = '';
                        }
                    }else{
                        $list[$keyNum]['task_content'] = '';
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


    /**
     * @NodeAnotation(title="详情")
     */
    public function detail($id)
    {
        $list = $this->model
            ->where('relation_id', '=', $id)
            ->order(['create_time' => 'asc'])
            ->select();
        $recordData = [];
        if (!$list->isEmpty()) {
            $recordData = $list->toArray();
            foreach($recordData as $keyNum=>$data){
                if($data['type'] == 1){
                    $taskContent = json_decode($data['task_content'], true);
                    $recordData[$keyNum]['task_content'] = "更新 ".$taskContent['start_time']." 到 ".$taskContent['end_time']." 的订单数据";
                }elseif($data['type'] == 2){
                    $taskContent = json_decode($data['task_content'], true);
                    $recordData[$keyNum]['task_content'] = "导出 ".$taskContent['start_time']." 到 ".$taskContent['end_time']." 的订单数据";
                    if(array_key_exists('file_url', $taskContent) && ($data['create_time'] > date('Y-m-d H:i:s', strtotime('-1 month')))){
                        $recordData[$keyNum]['file_url'] = $taskContent['file_url'];
                    }else{
                        $recordData[$keyNum]['file_url'] = '';
                    }

                }
            }
        }
        $this->assign('recordData', $recordData);
        return $this->fetch();
    }


    /**
     * @NodeAnotation(title="创建订单任务")
     */
    public function addOrder()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $content = [
                'start_time' => $post['start_time'],
                'end_time' => $post['end_time']
            ];
            //更新群组信息
            $taskInfo = [
                'title' => $post['title']?$post['title']:'同步订单数据',
                'type' => $post['type'],
                'task_status' => 0,
                'task_content' => json_encode($content),
                'creater_id' => session('admin')['id'],
                'create_time' => date('Y-m-d H:i:s'),
            ];
            try {
                $save = $this->model->save($taskInfo);
            } catch (\Exception $e) {
                $this->error('创建失败');
            }
            if($save){
                //将系统任务剔除
                if($post['type'] == 0){
                    $this->error('创建系统任务成功');
                }
                //将主任务切割为子任务
                $primaryId = $this->model->id;
                $interval_time = 3600*24;//1天为一个时间段
                $loopNum = 0;
                do{
                    $childStartTime = date('Y-m-d H:i:s',strtotime($post['start_time'])+$interval_time*$loopNum);
                    $childStopTime = date('Y-m-d H:i:s',strtotime($post['start_time'])+$interval_time*($loopNum+1));
                    if($childStopTime > $post['end_time']){
                        $childStopTime = $post['end_time'];
                    }
                    $childTaskContent = [
                        'start_time' => $childStartTime,
                        'end_time' => $childStopTime
                    ];
                    $childTaskInfo = [
                        'title' => ($post['title']?$post['title']:'同步订单数据').$loopNum,
                        'type' => $post['type'],
                        'relation_id' => $primaryId,
                        'task_status' => 0,
                        'task_content' => json_encode($childTaskContent),
                        'creater_id' => session('admin')['id'],
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                    $ret = $this->model->insert($childTaskInfo);
                    $loopNum++;
                }
                while($childStopTime < $post['end_time']);
                $this->success('创建成功');
            }else{
                $this->error('创建失败');
            }
        }
        return $this->fetch();
    }


    /**
     * @NodeAnotation(title="删除")
     */
    public function delete($id)
    {
        $row = $this->model->whereIn('id', $id)->select();
        $row->isEmpty() && $this->error('数据不存在');
        try {
            $save = $row->delete();
            //$this->model->where('relation_id', '=', $id)->delete();
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        $save ? $this->success('删除成功') : $this->error('删除失败');
    }

    
}