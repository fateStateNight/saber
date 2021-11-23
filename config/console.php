<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'curd'      => 'app\common\command\Curd',
        'node'      => 'app\common\command\Node',
        'OssStatic' => 'app\common\command\OssStatic',
        'transfer'      => 'app\command\Transfer',
        'synchronous'      => 'app\command\Synchronous',
        'taolijinOrderResult'      => 'app\command\TaoLiJinOrderResult',
        'test'      => 'app\command\Test',
        'decrpyCode'    => 'app\command\DecrpyCode',
    ],
];
