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
    | BASIC
    |--------------------------------------------------------------------------
    |
    */
    
    //show copyright of suda
    'show_copyright'=>true,
    
    # auto_mobile => false,

    // auto_mobile=true, auto add prefix to url on phone browser
    'mobile_prefix'=>'mobile',

    // auto_mobile=true,url with except prefix would not add mobile_prefix
    'except_mobile_prefix'=>[],
    
    // force https
    'force_secure'=>null,
    
    
    // cdn host for static files
    'static_host'=>'',
    
    // image upload
    'image' => [
        'storage'=>'local', //storage
        'size'=>[
            'small'=>200, //resize
            'medium'=>400,
        ]
    ],

    // locale language
    'locale'=>'zh_CN',

    // default assets path
    'core_assets_path'=>'/vendor/suda/assets',

    /*
    |--------------------------------------------------------------------------
    | THEME
    |--------------------------------------------------------------------------
    |
    | set default theme for website.
    |
    */
    
    'theme'=>[
        'site'  =>'default', //pc website
        'mobile'=>'default', //mobile website
    ],

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    |
    | admin_loginname has three options: username, email, phone
    |
    */
    
    // dashboard login path
    'admin_path' => 'admin',

    // login name
    'admin_loginname'=>'email',
    
    // login host
    'admin_host' => '',
    
    
    // an option to dashboard cache
    'admin_cache' => 'file',
    
    // default menu name, related to database's menu setting
    'default_menu' => 'suda',

    // enable pro style for sidebar.
    // 'sidemenu_style' => 'pro',
    
    //default widget for dashboard homepage.
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
    
    // Controller path.
    // WARNING: DO NOT CHANGE OR DELETE THIS.
    'controllers'=>[
        'namespace'=>[
            'admin'=>'Admin',
            'site'=>'Site',
            'mobile'=>'Mobile',
        ]
    ],
    
    //media models
    /**
     * types_model append to media upload types relate to table model
     * It's about which application/module when you upload you image or file.
     * You can use this attribute to create permission or different functions
     * 
     * 
     * default types is: page,article,media,setting,editor,operate,user,upload
     * you can't rewrite or cover the default types.
     * 
     */
    'media'=>[
        'types_model'=>[
            // 'page'=>'Gtd\Suda\Models\Page',
            // 'article'=>'Gtd\Suda\Models\Article',
        ]
    ],
    
];
