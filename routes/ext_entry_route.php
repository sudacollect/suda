<?php

Route::group([
    'as'         => 'entry_extension.',
    'domain'     => Sudacore::extHost(),
    'middleware' => 'extadmin/extension',
    'prefix'     => $extension_admin_path,
], function ($router) {
    
    $suda_controller_path = "\\Gtd\\Suda\\Http\\Controllers";
    $controller_prefix = $suda_controller_path."\\Extension\\";

    Route::get('passport/login',$controller_prefix.'LoginController@showLoginForm')->name('ext_login');
    Route::post('passport/login',$controller_prefix.'LoginController@login');
    Route::post('logout',$controller_prefix.'LoginController@logout');

    Route::get('profile', $controller_prefix.'User\ProfileController@showProfile');
    Route::post('profile/save', $controller_prefix.'User\ProfileController@saveProfile');
    Route::get('email', $controller_prefix.'User\ProfileController@editEmail');
    Route::get('profile/password', $controller_prefix.'User\ProfileController@editPassword');
    Route::post('profile/changepassword', $controller_prefix.'User\ProfileController@savePassword');

    Route::get('/', $controller_prefix.'EntryController@index');
    Route::get('entry/extensions', $controller_prefix.'EntryController@index');
    Route::get('entry/extension/{extension_slug}', $controller_prefix.'EntryController@detail');
    // Route::get('entry/extension/{extension_slug}/logo', $controller_prefix.'EntryController@getLogo');


    // medias load modal
    Route::get('medias/load-modal/{media_type}', $controller_prefix.'MediasController@loadModal');
    Route::get('medias/modal/{media_type}', $controller_prefix.'MediasController@modal');

    // medias upload route
    Route::post('medias/upload/image/{media_type?}', $controller_prefix.'MediasController@uploadMedia');
    // delete
    Route::post('medias/remove/image/{media_type?}', $controller_prefix.'MediasController@removeMedia');
});

//admin.extension
Route::group([
    'as'         => 'entry_extension.',
    'domain'     => Sudacore::extHost(),
    'middleware' => 'extadmin/extension',
    'prefix'     => $extension_admin_path.'/extension',
], function ($router) use ($adminNamespace) {
    
    Sudacore::getExtendAdminRoutes();

});