<?php

<<<<<<< HEAD
=======
// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | PHP交流群: 763822524
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zhongshaofa/EasyAdmin
// +----------------------------------------------------------------------

>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
return [

    // 不需要验证登录的控制器
    'no_login_controller' => [
        'login',
        'integral.auth',
        'integral.draw',
<<<<<<< HEAD
        'integral.douorder',
        'integral.exchange',
        'integral.person',
        //'business.cookieAuth',
        'business._cookie_auth',
=======
        'integral.exchange',
        'integral.person',
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
    ],

    // 不需要验证登录的节点
    'no_login_node'       => [
        'login/index',
        'login/out',
        'integral.auth/index',
<<<<<<< HEAD
        'integral.auth/callback',
        'integral.draw/index',
        'integral.douorder/index',
        'integral.douorder/reward',
=======
        'integral.draw/index',
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
        'integral.exchange/index',
        'integral.exchange/createAward',
        'integral.person/index',
        'integral.person/add',
<<<<<<< HEAD
        'business.cookieAuth/index',
=======
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
    ],

    // 不需要验证权限的控制器
    'no_auth_controller'  => [
        'ajax',
        'login',
        'index',
        'integral.auth',
        'integral.draw',
<<<<<<< HEAD
        'integral.douorder',
        'integral.exchange',
        'integral.person',
        'business._cookie_auth'
=======
        'integral.exchange',
        'integral.person',
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
    ],

    // 不需要验证权限的节点
    'no_auth_node'        => [
        'login/index',
        'login/out',
        'integral.auth/index',
<<<<<<< HEAD
        'integral.auth/callback',
        'integral.draw/index',
        'integral.douorder/index',
        'integral.douorder/reward',
=======
        'integral.draw/index',
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
        'integral.exchange/index',
        'integral.exchange/createAward',
        'integral.person/index',
        'integral.person/add',
<<<<<<< HEAD
        'business.cookieAuth/index',
=======
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
    ],
];