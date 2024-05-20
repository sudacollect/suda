<?php
//site
Route::group([
    'as'         => 'web.',
    'middleware' => 'web',
    'namespace' => $siteNamespace,
], function ($router) {
    
    $suda_controller_path = "\\Gtd\\Suda\\Http\\Controllers";
    $controller_prefix = $suda_controller_path."\\";
    
    //suda keeped
    Route::group([
        'as'         => 'sudarun.',
        'prefix'     => 'sudarun',
    ], function ($router) use ($controller_prefix) {
        
        // install license
        Route::get('setup/license', $controller_prefix.'SetupController@index');
        Route::post('setup/license', $controller_prefix.'SetupController@setLicense');
        
        // theme error page
        Route::get('/theme/error', $controller_prefix.'Controller@themeError');
        
        // http status page
        Route::get('/status/{code}', $controller_prefix.'Controller@status');
        
        //ajax错误
        Route::get('/error/ajax', $controller_prefix.'Controller@errorAjax')->name('error.ajax');

        // public media url
        // Route::get('/media/view/{id}',$controller_prefix.'Media\MediasController@showMedia');
        
    });
    
    $site_prefix = $controller_prefix."Site\\";
    
    Route::get('/', $site_prefix.'HomeController@index');
    Route::get('/home', $site_prefix.'HomeController@index');
    Route::get('/index', $site_prefix.'HomeController@index');
    Route::get('/error', $site_prefix.'HomeController@errors');
    
    
    // Page
    Route::get('/pages', $site_prefix.'PageController@showAll');
    Route::get('/page/list', $site_prefix.'PageController@showAll');
    Route::get('/page/{id}/{preview_str?}', $site_prefix.'PageController@index');
    
    // Articles
    Route::get('/articles', $site_prefix.'ArticleController@showAll');
    Route::get('/article/{id}/{preview_str?}', $site_prefix.'ArticleController@index');

    // Category
    Route::get('/category/{slug}', $site_prefix.'ArticleController@showCategory');

    // Tag
    Route::get('/tag/{tag_name}', $site_prefix.'ArticleController@showTag');

    
    Sudacore::getExtendWebRoutes();
    
    //load routes
    if(file_exists(base_path('routes/site.php'))){
        require base_path('routes/site.php');
    }
    
});