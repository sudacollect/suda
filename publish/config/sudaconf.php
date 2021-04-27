<?php
/**
 * sudaconf.php
 * description
 * date 2017-10-27 13:21:09
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */


return [

    /*
    |--------------------------------------------------------------------------
    | 系统初始化设置
    |--------------------------------------------------------------------------
    |
    | 1. 是否自动识别移动端 2. 是否显示版权 3. 静态资源加速 4. 图片存储设置
    |
    */
    
    //显示版权
    'with_copyright'=>true,
    
    //移动端路由prefix
    'mobile_prefix'=>'mobile',
    
    //当auto_mobile时，可配置不自动跳转的
    'except_prefix'=>[],
    
    //强制https
    'force_secure'=>null,
    
    
    //图片静态资源分离
    'static_host'=>'',
    
    //图片设置
    'image' => [
        'storage'=>'local',
        'size'=>[
            'small'=>200,
            'medium'=>400,
        ]
    ],

    //默认中文配置.
    'locale'=>'zh_CN',

    //默认的静态文件路径
    'assets_path'=>'/vendor/suda/assets',

    /*
    |--------------------------------------------------------------------------
    | 模板配置
    |--------------------------------------------------------------------------
    |
    | 设置PC和移动端的默认样式
    |
    */
    
    'theme'=>[
        'site'  =>'default',
        'mobile'=>'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | 管理面板设置
    |--------------------------------------------------------------------------
    |
    | admin_loginname 登录控制面板的用户名参数，可以是 username, email, phone
    | admin_path 控制面板的访问路径，可以自定义
    | controllers 默认的控制器目录
    |
    */
    
    //登录方式
    'admin_loginname'=>'email',
    
    //控制台域名
    'admin_host' => '',
    //控制台路径
    'admin_path' => 'admin',
    
    //后台缓存方式(可以和前台区分)
    'admin_cache' => 'file',
    
    //默认菜单组
    'default_menu' => 'suda',

    //菜单专业样式
    // 'sidemenu_style' => 'pro',
    
    'widget'=>[
        'dashaboard'=>[
            'start'=>\Gtd\Suda\Widgets\Start::class,
            'quickin'=>\Gtd\Suda\Widgets\Quickin::class,
            'news'=>\Gtd\Suda\Widgets\News::class,
            'dashhelp'=>\Gtd\Suda\Widgets\DashHelp::class,
        ],
        'entry'=>[
            'extension'=>\Gtd\Suda\Widgets\Entry\Extension::class,
        ],
    ],
    
    //控制器的路径 App\Http\Controllers\
    'controllers'=>[
        'namespace'=>[
            'admin'=>'Admin',
            'site'=>'Site',
            'mobile'=>'Mobile',
        ]
    ],
    
    //media配置
    /**
     * types_model => ['page'=>'Gtd\Suda\Models\Page'] typetable关联的model,默认关联到 App\Models\Page
     */
    'media'=>[
        'types_model'=>[
            'page'=>'Gtd\Suda\Models\Page',
            'article'=>'Gtd\Suda\Models\Article',
        ]
    ],
    
];
