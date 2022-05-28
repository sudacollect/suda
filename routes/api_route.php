<?php
// api route
Route::group([
    'middleware' => 'api',
    'namespace' => $apiNamespace,
    'prefix' => 'api',
    'domain'     => Sudacore::apiHost(),
], function ($router) {
    
    Sudacore::getExtendApiRoutes();
    
    if(file_exists(base_path('routes/api.php'))){
        require base_path('routes/api.php');
    }
});