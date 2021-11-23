<?php

namespace app\admin\controller\publish;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use EasyAdmin\tool\CommonTool;
use jianyan\excel\Excel;
use think\App;
use think\facade\Db;

/**
 * @ControllerAnnotation(title="publish_customer")
 */
class Customer extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\PublishCustomer();
        
    }

    /**
     * @NodeAnotation(title="导入")
     */
    public function import()
    {
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
        //var_dump($fileData);die;
        if($fileData != null){
            $dataHeader = $fileData[1];
            foreach($dataHeader as $keyNum=>$headerName){
                if($headerName == '收货人'){
                    $userNameKey = $keyNum;
                }elseif($headerName == '手机/电话'){
                    $phonekey = $keyNum;
                }elseif($headerName == '省市区'){
                    $addrStrKey = $keyNum;
                }elseif($headerName == '详细地址'){
                    $addressKey = $keyNum;
                }elseif($headerName == '订单支付时间'){
                    $createTimeKey = $keyNum;
                }
            }
            unset($fileData[1]);
            if($fileData != null){
                foreach($fileData as $customerArr){
                    $addrStr = $customerArr[$addrStrKey];
                    list($province,$city,$area) = explode('-', $addrStr);
                    $inserData = [
                        'user_name' => $customerArr[$userNameKey],
                        'phone' => $customerArr[$phonekey],
                        'province' => $province,
                        'city' => $city,
                        'area' => $area,
                        'address' => $customerArr[$addressKey],
                        'create_time' => $customerArr[$createTimeKey],
                    ];
                    $dataNum = $this->model->where('phone','=',$customerArr[$phonekey])->count();
                    if($dataNum <= 0){
                        //$ret = $this->model->save($inserData);
                        $ret = $this->model->insert($inserData);
                    }
                }
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

    /**
     * @NodeAnotation(title="导出")
     */
    public function export()
    {
        list($page, $limit, $where) = $this->buildTableParames();
        $tableName = $this->model->getName();
        $tableName = CommonTool::humpToLine(lcfirst($tableName));
        $prefix = config('database.connections.mysql.prefix');
        $dbList = Db::query("show full columns from {$prefix}{$tableName}");
        $header = [];
        foreach ($dbList as $vo) {
            $comment = !empty($vo['Comment']) ? $vo['Comment'] : $vo['Field'];
            if (!in_array($vo['Field'], $this->noExportFileds)) {
                $header[] = [$comment, $vo['Field']];
            }
        }
        $list = $this->model
            ->where($where)
            ->limit(100000)
            ->order('id', 'desc')
            ->select()
            ->toArray();
        $fileName = time();
        var_dump($header);die;
        return Excel::exportData($list, $header, $fileName, 'xlsx');
    }
    
}