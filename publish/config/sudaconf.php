<?php
/**
 * SUDACONF.PHP
 * DESCRIPTION
 * DATE 2017-10-27 13:21:09
 * AUTHOR SUDA <HELLO@SUDA.GTD.XYZ>
 * @COPYRIGHT GTD. ALL RIGHTS RESERVED.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    |
    | ADMIN_LOGINNAME HAS THREE OPTIONS: USERNAME, EMAIL, PHONE
    |
    */

    // PASSWORD SALT
    'password_link'     => 'zp',
    
    // DASHBOARD LOGIN PATH
    'admin_path'        => 'admin',

    // LOGIN NAME
    'admin_loginname'   => 'email',
    
    // LOGIN HOST
    'admin_host'        => '',
    
    
    // AN OPTION TO DASHBOARD CACHE
    'admin_cache'       => 'file',
    
    // DEFAULT MENU NAME, RELATED TO DATABASE'S MENU SETTING
    'default_menu'      => 'suda',

    // ENABLE PRO STYLE FOR SIDEBAR
    // 'sidebar_pro' => true,
    
    'apps' => ['admin','site','mobile'],

    //DEFAULT WIDGET FOR DASHBOARD HOMEPAGE
    'widget'=>[
        'dashaboard'=>[
            'start'     =>\Gtd\Suda\Widgets\Start::class,
            'quickin'   =>\Gtd\Suda\Widgets\Quickin::class,
            'news'      =>\Gtd\Suda\Widgets\News::class,
            // 'dashhelp'=>\Gtd\Suda\Widgets\DashHelp::class,
        ],
        'entry'=>[
            'extension' =>\Gtd\Suda\Widgets\Entry\Extension::class,
        ],
    ],
    
    // CONTROLLER PATH.
    // WARNING: DO NOT CHANGE OR DELETE THIS.
    'controllers'=>[
        'namespace'=>[
            'admin' => 'Admin',
            'site'  => 'Site',
            'mobile'=> 'Mobile',
        ]
    ],
    
    //MEDIA MODELS
    /**
     * TYPES_MODEL APPEND TO MEDIA UPLOAD TYPES RELATE TO TABLE MODEL
     * IT'S ABOUT WHICH APPLICATION/MODULE WHEN YOU UPLOAD YOU IMAGE OR FILE.
     * YOU CAN USE THIS ATTRIBUTE TO CREATE PERMISSION OR DIFFERENT FUNCTIONS
     *  
     * DEFAULT TYPES IS: PAGE,ARTICLE,MEDIA,SETTING,EDITOR,OPERATE,USER,UPLOAD
     * YOU CAN'T REWRITE OR COVER THE DEFAULT TYPES.
     */
    'media'=>[
        'types_model'   => [
            // 'page'=>'Gtd\Suda\Models\Page',
            // 'article'=>'Gtd\Suda\Models\Article',
        ],
        'disk'  => 'public',
        'subdir_type' => 'date',
        'size'=>[
            'small'     => 200,
            'medium'    => 400,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | BASIC
    |--------------------------------------------------------------------------
    |
    */
    
    // CDN HOST FOR STATIC FILES
    'static_host'       => '',

    // FORCE HTTPS
    'force_secure'      => null,

    # auto_mobile => false,

    // AUTO_MOBILE = TRUE, AUTO ADD PREFIX TO URL ON PHONE BROWSER
    'mobile_prefix'         => 'mobile',

    // AUTO_MOBILE = TRUE,URL WITH EXCEPT PREFIX WOULD NOT ADD MOBILE_PREFIX
    'except_mobile'  => [],
    
    // LOCALE LANGUAGE
    'locale'                => 'zh_CN',

    // DEFAULT ASSETS PATH
    'core_assets_path'      => '/vendor/suda/assets',


    /*
    |--------------------------------------------------------------------------
    | THEME
    |--------------------------------------------------------------------------
    |
    | SET DEFAULT THEME FOR WEBSITE.
    |
    */
    
    'theme'=>[
        'site'  =>'default', //pc website
        'mobile'=>'default', //mobile website
    ],

    /*
    |--------------------------------------------------------------------------
    | EXTENSION
    |--------------------------------------------------------------------------
    | EXTENSION ENTRY
    | SEPARATE LOGIN FOR EXTENSION MANAGER
    */

    // DONOTMODIFY
    'extension_dir' => 'extensions',
    
    // EXTENSION HOST
    'extension_host' => '',

    // EXTENSION MANAGER LOGIN
    'extension_admin_path' => 'appcenter',
    'extension_login_name' => 'email',

];
