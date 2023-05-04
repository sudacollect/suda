<?php

use Gtd\Suda\Events\Routing;
use Gtd\Suda\Events\RoutingAfter;
use Illuminate\Filesystem\Filesystem;


Route::get('/sudapower', function () {
    return base64_decode('cG93ZXJlZCBieSBTdWRh');
});


Route::group([
    'as' => 'sudaroute.'
], function () {
    event(new Routing());
    
    $adminNamespace     = "\\".config('sudaconf.controllers.namespace.admin');
    $siteNamespace      = "\\".config('sudaconf.controllers.namespace.site');
    $mobileNamespace    = "\\".config('sudaconf.controllers.namespace.mobile');
    $apiNamespace       = "\\".config('sudaconf.controllers.namespace.api','Api');
    
    $admin_path = config('sudaconf.admin_path','admin');
    $extension_admin_path = config('sudaconf.extension_admin_path','appcenter');

    
    include_once(suda_path('routes/admin_route.php'));
    include_once(suda_path('routes/ext_route.php'));
    include_once(suda_path('routes/ext_entry_route.php'));
    include_once(suda_path('routes/web_route.php'));
    include_once(suda_path('routes/mob_route.php'));
    include_once(suda_path('routes/api_route.php'));
    
    event(new RoutingAfter());
});




