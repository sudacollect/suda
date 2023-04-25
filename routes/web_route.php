<?php
//site
Route::group([
    'as'         => 'site.',
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
        Route::get('/media/view/{id}',$controller_prefix.'Media\MediasController@showMedia');
        
    });
    
    $controller_prefix_site = $controller_prefix."Site\\";
    
    
    
    Route::get('/', $controller_prefix_site.'HomeController@index');
    Route::get('/home', $controller_prefix_site.'HomeController@index');
    Route::get('/index', $controller_prefix_site.'HomeController@index');
    Route::get('/error', $controller_prefix_site.'HomeController@errors');
    
    
    // Page
    Route::get('/pages', $controller_prefix_site.'PageController@showAll');
    Route::get('/page/list', $controller_prefix_site.'PageController@showAll');
    Route::get('/page/{id}/{preview_str?}', $controller_prefix_site.'PageController@index');
    
    // Articles
    Route::get('/articles', $controller_prefix_site.'ArticleController@showAll');
    Route::get('/article/{id}/{preview_str?}', $controller_prefix_site.'ArticleController@index');

    // Category
    Route::get('/category/{slug}', $controller_prefix_site.'ArticleController@showCategory');

    // Tag
    Route::get('/tag/{tag_name}', $controller_prefix_site.'ArticleController@showTag');

    
    Sudacore::getExtendWebRoutes();
    
    //load routes
    if(file_exists(base_path('routes/site.php'))){
        require base_path('routes/site.php');
    }
    
});