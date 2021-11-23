<?php

namespace app\admin\controller\business;

use app\admin\model\BusinessShare;
use app\admin\model\BusinessRecord;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use jianyan\excel\Excel;
use think\App;


/**
 * @ControllerAnnotation(title="商家资料")
 */
class Store extends AdminController
{

    use \app\admin\traits\Curd;

    protected $relationSerach = true;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\BusinessStore();

        $this->assign('adminInfo', session('admin'));
        
        $this->assign('getSystemAdminList', $this->model->getSystemAdminList());

        $this->assign('getShareLevelList', $this->model->getShareLevelList());

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
                    ->count();
                $list = $this->model
                    ->withJoin(['systemAdmin'], 'LEFT')
                    //->field(session('admin')['auth_ids'].' AS `business_store`.`priveledge`')
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    //->fetchSql()
                    ->select();
                //echo $list;die;
            }else{
                $count = $this->model
                    ->withJoin(['systemAdmin','businessShare'], 'LEFT')
                    ->where(['businessShare.admin_id'=>session('admin')['id']])//,'businessShare.creater_id'=>session('admin')['id']
                    ->where($where)
                    ->count();
                $list = $this->model
                    ->withJoin(['systemAdmin','businessShare'], 'LEFT')
                    //->alias('businessStore')
                    //->field('businessStore.id,businessStore.title,businessStore.share_level,businessStore.creater_id,businessStore.detail,businessStore.enclosure,businessStore.create_time,`systemAdmin`.`nickname` as `systemAdmin__nickname`,`systemAdmin`.`head_img` as `systemAdmin__head_img`')
                    //->join('system_admin systemAdmin', 'businessStore.creater_id=systemAdmin.id', 'LEFT')
                    //->join('business_share businessShare', 'businessStore.id=businessShare.store_id', 'LEFT')
                    ->where(['businessShare.admin_id'=>session('admin')['id']])
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select();
            }
            if($count > 0){
                foreach($list as &$storeInfo){
                    $storeInfo['priveledge'] = session('admin')['auth_ids'];
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
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                //保存商家信息
                $post['creater_id'] = session('admin')['id'];
                $post['enclosure'] = $post['enclosure']?$this->httpTransfer($post['enclosure']):'';
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            if($save){
                try {
                    //更新商家关联的管理员信息
                    $relationData = [
                        'store_id' => $this->model->id,
                        'admin_id' => $post['creater_id'],
                        'creater_id' => $post['creater_id'],
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                    $relationResult = BusinessShare::create($relationData);
                }catch (\Exception $exp){
                    $this->error('更新权限失败:'.$exp->getMessage());
                }
                //添加记录数据
                $recordData = [
                    'store_id' => $this->model->id,
                    'title' => $post['title'],
                    'qq_number' => $post['qq_number'],
                    'weixin' => $post['weixin'],
                    'dingding' => $post['dingding'],
                    'phone' => $post['phone'],
                    'detail' => $post['detail'],
                    'enclosure' => $post['enclosure'],
                    'create_time' => date('Y-m-d H:i:s'),
                ];
                $this->addStoreRecord($recordData);
            }
            $save && $relationResult ? $this->success('保存成功') : $this->error('保存失败');
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        $shareIdInfo = $this->model->getShareAdminList($id);
        //$recordNum = $this->model->getCountRecordWithStore($id);
        $row->isEmpty() && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();//var_dump($post);die;
            if(!$post){
                $this->error('数据没有改变！');
            }
            $insertIdArr = $adminIdArr = [];

            if(array_key_exists('admin_id', $post)){
                if($post['admin_id'] != null){
                    $insertIdArr = $adminIdArr = explode(',', $post['admin_id']);
                }
                unset($post['admin_id']);
            }
            $rule = [];
            $this->validate($post, $rule);
            $post['enclosure'] = $post['enclosure']?$this->httpTransfer($post['enclosure']):'';
            $post['record_num'] = $row->record_num + 1;
            try {
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }

            //更新内容权限关联表
            //获取当前内容关联表数据
            $relationResult = BusinessShare::where('store_id', '=', $id)
                ->select();
            $ret = true;
            if(!$relationResult->isEmpty()){
                $existIdArr = [];
                $relationData = $relationResult->toArray();
                foreach($relationData as $relateInfo){
                    if(!in_array($relateInfo['admin_id'], $adminIdArr)){
                        //删除一条关系数据
                        $ret = BusinessShare::destroy($relateInfo['id']);
                    }else{
                        $existIdArr[] = $relateInfo['admin_id'];
                    }
                }
                $insertIdArr = array_diff($existIdArr, $adminIdArr);
            }
            if($insertIdArr != null){
                foreach($insertIdArr as $adminId){
                    $insertData = [
                        'store_id' => $id,
                        'admin_id' => $adminId,
                        'creater_id' => session('admin')['id'],
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                    $ret = BusinessShare::create($insertData);
                }
            }
            //添加记录数据
            $recordData = [
                'store_id' => $id,
                'title' => $post['title'],
                'qq_number' => $post['qq_number'],
                'weixin' => $post['weixin'],
                'dingding' => $post['dingding'],
                'phone' => $post['phone'],
                'detail' => $post['detail'],
                'enclosure' => $post['enclosure'],
                'create_time' => date('Y-m-d H:i:s'),
            ];
            $this->addStoreRecord($recordData);
            $save && $ret ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('shareIdInfo', $shareIdInfo);
        $this->assign('row', $row);
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
            //删除内容的同时删除掉关联数据
            $save = $row->delete();
            if($save){
                $ret = BusinessShare::where('store_id', '=', $id)
                    ->delete();
            }
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        $save && $ret ? $this->success('删除成功') : $this->error('删除失败');
    }

    public function addStoreRecord($data)
    {
        try {
            $ret = BusinessRecord::create($data);
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        return $ret;
    }

    public function httpTransfer($str)
    {
        return str_replace('http:', 'https:', $str);
    }




    /**
     * @NodeAnotation(title="导入")
     */
    public function import()
    {
        if(session('admin')['auth_ids'] != 7 && session('admin')['auth_ids'] != 1){
            $this->error('权限不足！');
        }
        $data = [
            'upload_type' => $this->request->post('upload_type'),
            'file'        => $this->request->file('file'),
        ];
        $uploadConfig = sysconfig('upload');
        empty($data['upload_type']) && $data['upload_type'] = $uploadConfig['upload_type'];
        $rule = [
            'upload_type|指定上传类型有误' => "in:{$uploadConfig['upload_allow_type']}",
            'file|文件'              => "require|file|fileExt:{$uploadConfig['upload_allow_ext']}|fileSize:{$uploadConfig['upload_allow_size']}",
        ];
        $this->validate($data, $rule);
        //读取excel数据
        $fileData = Excel::import($data['file']);
        //unset($fileData[1]);
        //var_dump($fileData[0]);
        if($fileData != null){
            //unset($fileData[1]);
            $ret = false;
            foreach ($fileData as $storeInfo){
                $existRet = $this->model->where('title', '=', $storeInfo[0])->find();
                //var_export($existRet['id']);die;
                if(!$existRet && $storeInfo[2] != null){
                    $inserData = [
                        'title' => $storeInfo[0],
                        'phone' => $storeInfo[3],
                        'creater_id' => session('admin')['id'],
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                    $ret = $this->model->insert($inserData);
                }elseif($existRet['phone'] == null && $storeInfo[3] != null){
                    $updateData = [
                        'phone' => $storeInfo[3]
                    ];
                    $ret = $this->model::update($updateData,['id' => $existRet['id']]);
                }elseif($existRet['phone'] != $storeInfo[3] && $storeInfo[3] != null){
                    $detail = $existRet['detail'];
                    $detailArr = json_decode($detail, true);
                    $detailArr['otherPhone'] = $storeInfo[3];
                    $updateData['detail'] = json_encode($detailArr);
                    $ret = $this->model::update($updateData,['id' => $existRet['id']]);
                }
                $this->write_log('/tmp/exportData.log',$storeInfo[0]);
            }
        }
        if($ret){
            $returnData = [
                'code' => 0,
                'result' => 'success',
                'msg' => '导入成功'
            ];
            $this->success('导入成功！',$returnData);
        }else{
            $returnData = [
                'code' => 1,
                'result' => 'failed',
                'msg' => '导入失败'
            ];
            $this->error('导入失败！',$returnData);
        }

    }

    function write_log($filename, $content){
        $newContent = date("Y-m-d H:i:s")." ".$content."\r\n";
        file_put_contents($filename,$newContent,FILE_APPEND);
        return true;
    }


    /**
     * @NodeAnotation(title="更新")
     */
    public function update()
    {
        echo 123;die;
        //获取已有的商家数据
        $storeArr = $this->model->where('id','>=','5905')
            ->select()
            ->toArray();
        //var_dump($storeArr);die;
        //$taobaoApiObj = new \app\admin\model\MallTaolijinGoods();
        $loopnum = 0;
        /*foreach($storeArr as $storeInfo){
            $storeResult = $taobaoApiObj->getShopInfo($storeInfo['title']);
            //var_export($storeResult);die;
            if($storeResult['total_results'] == 0){
                continue;
            }
            $updateData['shop_id'] = $storeResult['results']['n_tbk_shop'][0]['user_id'];
            //var_dump($updateData);die;
            $save = $this->model::update($updateData,['id'=>$storeInfo['id']]);
            //var_dump($save);die;
            $loopnum++;
        }*/
        $shopContactInfo = [];
        foreach($storeArr as $storeInfo){
            $shopContactInfo = $this->getShopContactInfo($storeInfo['shop_id']);
            //var_export($shopContactInfo);die;
            if(!is_array($shopContactInfo)){
                $shopContactInfo = $this->getShopContactInfo($storeInfo['shop_id']);
            }
            if(!is_array($shopContactInfo)){
                continue;
            }
            if(!array_key_exists('message',$shopContactInfo)){
                continue;
            }
            if($shopContactInfo['message'] != 'ok'){
                continue;
            }
            $updateData['detail'] = $shopContactInfo['data'];
            //var_dump($updateData);die;
            $save = $this->model::update($updateData,['id'=>$storeInfo['id']]);
            //var_dump($save);die;
            $loopnum++;
            //usleep(300000);
            echo $storeInfo['title'].'|'.$loopnum."\r\n";
        }
        $save ? $this->success('更新成功'.$loopnum) : $this->error('更新失败'.$loopnum);
    }

    public function getShopContactInfo($shopId)
    {
        if($shopId == null){
            return ['message'=>'error'];
        }
        $url = 'http://node.yiquntui.com/api/contentData?shopid='.$shopId.'&sellerid='.$shopId;
        //$data['shopid'] = '2153863260';
        //$data['sellerid'] = '2153863260';
        //执行请求获取数据
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //https调用
        //curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $header = [
            'Content-Type: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, false);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $output = curl_exec($ch);
        $a = curl_error($ch);
        if(!empty($a)){
            return json_encode(array('code'=>-10003,'msg'=>$a));
        }
        curl_close($ch);
        $result = json_decode($output,true);
        $result = $result?$result:[];
        return $result;
    }


}