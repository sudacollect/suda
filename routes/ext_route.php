<?php

//admin.extension
Route::group([
    'as'         => 'admin_extension.',
    'domain'     => Sudacore::adminHost(),
    'middleware' => 'admin/extension',
    'prefix'     => $admin_path.'/extension',
], function ($router) use ($adminNamespace) {
    
    Sudacore::getExtendAdminRoutes();

});