<?php

// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | PHP交流群: 763822524
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zhongshaofa/EasyAdmin
// +----------------------------------------------------------------------

namespace app\admin\middleware;

use app\admin\service\SystemLogService;
use EasyAdmin\tool\CommonTool;

/**
 * 系统操作日志中间件
 * Class SystemLog
 * @package app\admin\middleware
 */
class SystemLog
{

    public function handle($request, \Closure $next)
    {
        if ($request->isAjax()) {
            $method = strtolower($request->method());
            if (in_array($method, ['post', 'put', 'delete'])) {
                $url = $request->url();
                $ip = CommonTool::getRealIp();
                $params = $request->param();
                if (isset($params['s'])) {
                    unset($params['s']);
                }
                $data = [
                    'admin_id'    => session('admin.id'),
                    'url'         => $url,
                    'method'      => $method,
                    'ip'          => $ip,
                    'content'     => json_encode($params, JSON_UNESCAPED_UNICODE),
                    'useragent'   => substr($_SERVER['HTTP_USER_AGENT'],0,100),//不能超过系统字段长度
                    'create_time' => time(),
                ];
                SystemLogService::instance()->save($data);
            }
        }
        return $next($request);
    }

}