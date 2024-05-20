<?php

use Gtd\Suda\Events\Routing;
use Gtd\Suda\Events\RoutingAfter;


Route::get('/sudapower', function () {
    return base64_decode('cG93ZXJlZCBieSBTdWRh');
});


Route::group([
    'as' => 'sudaroute.'
], function () {
    event(new Routing());
    
    $adminNamespace     = "\\".config('sudaconf.namespace.admin');
    $siteNamespace      = "\\".config('sudaconf.namespace.site');
    $apiNamespace       = "\\".config('sudaconf.namespace.api','Api');
    
    $admin_path = config('sudaconf.admin_path','admin');
    $extension_admin_path = config('sudaconf.extension_admin_path','appcenter');

    
    include_once(suda_path('routes/admin_route.php'));
    include_once(suda_path('routes/ext_route.php'));
    include_once(suda_path('routes/ext_entry_route.php'));
    include_once(suda_path('routes/web_route.php'));
    include_once(suda_path('routes/api_route.php'));
    
    event(new RoutingAfter());
});




