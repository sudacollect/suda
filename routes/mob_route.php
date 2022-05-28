<?php
$mobile_prefix = config('sudaconf.mobile_prefix','mobile');
    
//mobile
Route::group([
    'as'         => 'mobile.',
    'middleware' => 'web',
    'namespace' => $mobileNamespace,
    'prefix' => $mobile_prefix,
], function ($router) {
    
    $suda_controller_path = "\\Gtd\\Suda\\Http\\Controllers";
    $controller_prefix = $suda_controller_path."\\";
    $controller_prefix_mobile = $controller_prefix."Mobile\\";
    
    Route::get('/', $controller_prefix_mobile.'HomeController@index');
    Route::get('/home', $controller_prefix_mobile.'HomeController@index');
    
    Route::get('/error', $controller_prefix_mobile.'HomeController@errors');
    
    
    Sudacore::getExtendMobileRoutes();
    
    if(file_exists(base_path('routes/mobile.php'))){
        require base_path('routes/mobile.php');
    }
    
});