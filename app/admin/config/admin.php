<?php

return [

    // 不需要验证登录的控制器
    'no_login_controller' => [
        'login',
        'integral.auth',
        'integral.draw',
        'integral.douorder',
        'integral.exchange',
        'integral.person',
        //'business.cookieAuth',
        'business._cookie_auth',
    ],

    // 不需要验证登录的节点
    'no_login_node'       => [
        'login/index',
        'login/out',
        'integral.auth/index',
        'integral.auth/callback',
        'integral.draw/index',
        'integral.draw/searchContact',
        'integral.douorder/index',
        'integral.douorder/reward',
        'integral.exchange/index',
        'integral.exchange/createAward',
        'integral.person/index',
        'integral.person/add',
        'business.cookieAuth/index',
    ],

    // 不需要验证权限的控制器
    'no_auth_controller'  => [
        'ajax',
        'login',
        'index',
        'integral.auth',
        'integral.draw',
        'integral.douorder',
        'integral.exchange',
        'integral.person',
        'business._cookie_auth'
    ],

    // 不需要验证权限的节点
    'no_auth_node'        => [
        'login/index',
        'login/out',
        'integral.auth/index',
        'integral.auth/callback',
        'integral.draw/index',
        'integral.draw/searchContact',
        'integral.douorder/index',
        'integral.douorder/reward',
        'integral.exchange/index',
        'integral.exchange/createAward',
        'integral.person/index',
        'integral.person/add',
        'business.cookieAuth/index',
    ],
];