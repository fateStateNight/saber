<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::view('index', 'company@index');

Route::view('index-2', 'company@index-2');

Route::view('index-3', 'company@index-3');

Route::view('/about', 'company@about');

Route::view('/blog-classic', 'company@blog-classic');

Route::view('/blog-details', 'company@blog-details');

Route::view('/blog-grid', 'company@blog-grid');

Route::view('/contact-us', 'company@contact-us');

Route::view('/service', 'company@service');

Route::view('/work', 'company@work');

Route::view('/work-details', 'company@work-details');

Route::view('/', 'company@index');

Route::view('/company-map', 'company@company-map');

Route::view('/map', 'company@map');

/*Route::view('/', 'index@welcome', [
    'version' => time(),
    'data'    => [
        'description'        => '内部后台管理系统',
        'system_description' => '别瞎用哦！',
    ],
    'navbar'  => [
        [
            'name'   => '首页',
            'active' => true,
            'href'   => '',
            'target' => '_self',
        ],
        [
            'name'   => '文档',
            'active' => false,
            'href'   => '',
            'target' => '_blank',
        ],
        [
            'name'   => '测试',
            'active' => false,
            'href'   => '',
            'target' => '_blank',
        ],
        [
            'name'   => '测试',
            'active' => false,
            'href'   => '',
            'target' => '_blank',
        ],
    ],
    'feature' => [
        [
            'name'        => '内置权限管理',
            'description' => '内置基于auth的权限系统，使用注解方式自动更新权限节点，无需手动维护。',
        ],
        [
            'name'        => '表格&表单的二次封装',
            'description' => '对layui的数据表格和表单进行二次封装，开发起来更舒服流畅。',
        ],
        [
            'name'        => '上传&附件管理',
            'description' => '内置封装上传方法以及上传的附件管理，支持上传到本地以及OSS，可以在此基础上自己去扩展。',
        ],
        [
            'name'        => '快速生成CURD模块',
            'description' => '完善的命令行开发模式, 一键生成控制器、模型、视图、JS等文件, 使开发速度快速提升。',
        ],
        [
            'name'        => '公众号&小程序模块',
            'description' => '待开发。。。',
        ],
        [
            'name'        => '插件管理模块',
            'description' => '待开发。。。',
        ],
    ],
]);*/