<?php
/**
 * theme.php
 * 模板风格管理
 * date 2017-12-11 10:48:55
 * author suda <dev@panel.cc>
 * @copyright Suda. All Rights Reserved.
 */


/**
 * 模板内容的组成：
 * 1. 配置信息和预览图.     theme.php/screenshot.png
 * 2. 样式和图片文件目录.   design
 * 3. 模板页面目录.        views
 * 4. 模板可扩展的.        helpers.php 当前模板可用的函数库(可选)
 * 5. #TODO 模板可扩展的控制器等
 */


return[
    
    'default'=>[
        'name'=>'default',
        'description'=>'default theme for website',
        'version'=>'1.0',
        'suda_version'=>'>1.0',//=表示只是配当前版本,>表示必须大于某个版本,<表示必须小于某个版本
        'theme_url'=>'https://panel.cc', //主题在线地址
        'author'=>'suda',
        'author_email'=>'dev@panel.cc',
        'author_url'=>'https://panel.cc',
        'widgets'=>[
            'sidebar'=>[
                'name'=>'sidebar',
                'max'=>3,
            ],
            'content'=>[
                'name'=>'content',
                'max'=>3,
            ]
        ]
    ]
    
];