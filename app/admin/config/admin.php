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

return [

    // 不需要验证登录的控制器
    'no_login_controller' => [
        'login',
        'integral.auth',
        'integral.draw',
        'integral.exchange',
        'integral.person',
    ],

    // 不需要验证登录的节点
    'no_login_node'       => [
        'login/index',
        'login/out',
        'integral.auth/index',
        'integral.draw/index',
        'integral.exchange/index',
        'integral.exchange/createAward',
        'integral.person/index',
        'integral.person/add',
    ],

    // 不需要验证权限的控制器
    'no_auth_controller'  => [
        'ajax',
        'login',
        'index',
        'integral.auth',
        'integral.draw',
        'integral.exchange',
        'integral.person',
    ],

    // 不需要验证权限的节点
    'no_auth_node'        => [
        'login/index',
        'login/out',
        'integral.auth/index',
        'integral.draw/index',
        'integral.exchange/index',
        'integral.exchange/createAward',
        'integral.person/index',
        'integral.person/add',
    ],
];