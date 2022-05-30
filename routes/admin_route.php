<?php
use Gtd\Suda\Events\RoutingAdmin;
use Gtd\Suda\Events\RoutingAdminAfter;

//admin
Route::group([
    'as'         => 'admin.',
    'middleware' => 'admin',
    'prefix'     => $admin_path,
    'namespace'  => $adminNamespace,
    'guard'      => 'operate',
], function ($router) use ($adminNamespace) {
    
    event(new RoutingAdmin());
    
    $custom_controller_path = "\\App\\Http\\Controllers";
    $suda_controller_path = "\\Gtd\\Suda\\Http\\Controllers";
    
    $controller_prefix = $suda_controller_path.$adminNamespace."\\";
    
    
    Route::get('/', function () {
        return redirect('/'.config('sudaconf.admin_path','admin').'/index');
    })->name('root');
    
    // Login
    Route::get('/passport/login',$controller_prefix.'Passport\LoginController@showLoginForm')->name('login');
    Route::post('/passport/login',$controller_prefix.'Passport\LoginController@login');
    
    // Register
    Route::get('/passport/register',$controller_prefix.'Passport\LoginController@showLoginForm')->name('register');
    Route::post('/passport/register', $controller_prefix.'Passport\RegisterController@register');

    // reset password
    Route::get('/passport/password/reset',$controller_prefix.'Passport\ForgotPasswordController@showLinkRequestForm');
    
    Route::post('/logout',$controller_prefix.'Passport\LoginController@logout');
    
    // error page
    Route::get('/error', $controller_prefix.'HomeController@errorPage')->name('admin.error');
    Route::get('/forbidden', $controller_prefix.'HomeController@forbidden');
    
    /*========= page ========*/
    Route::get('/page', $controller_prefix.'PageController@index')->name('page');
    Route::get('/page/list', $controller_prefix.'PageController@index')->name('page_list');
    Route::get('/page/{list}/by/{sort}', $controller_prefix.'PageController@index')->name('page_list_by_sort');
    Route::get('/page/list/deleted', $controller_prefix.'PageController@deletedList')->name('page_list_deleted');
    
    Route::get('/page/create', $controller_prefix.'PageController@create')->name('page_new');
    Route::get('/page/update/{id}', $controller_prefix.'PageController@update')->name('page_edit');
    Route::post('/page/save', $controller_prefix.'PageController@save')->name('page_save');
    Route::post('/page/delete/{id}', $controller_prefix.'PageController@deletePage')->name('page_delete');

    // page sort
    Route::post('/page/editsort/{id}', $controller_prefix.'PageController@editSort');
    
    Route::post('/page/restore/{id}', $controller_prefix.'PageController@restorePage')->name('page_restore');

    // force delete
    Route::post('/page/forcedelete/{id}', $controller_prefix.'PageController@forceDelete')->name('page_force_delete');

    // page preview
    Route::get('/page/preview/{id}', $controller_prefix.'PageController@preview')->name('page_preview');
    
    // page modalbox
    Route::get('/page/modalbox/{view?}/{name}', $controller_prefix.'PageController@modalBox');


    // page search
    Route::post('/page/search', $controller_prefix.'PageController@search');
    
    /*========= Article ========*/
    Route::get('/articles', $controller_prefix.'ArticleController@index')->name('article');
    Route::get('/articles/{view?}', $controller_prefix.'ArticleController@index')->name('article_list');
    Route::get('/articles/{view?}/{sorted?}', $controller_prefix.'ArticleController@index')->name('article_list_by_sort');
    
    Route::get('/article/create', $controller_prefix.'ArticleController@create')->name('article_new');
    Route::get('/article/update/{id}', $controller_prefix.'ArticleController@update')->name('article_edit');
    Route::post('/article/save', $controller_prefix.'ArticleController@save')->name('article_save');
    Route::post('/article/delete/{id}', $controller_prefix.'ArticleController@delete')->name('article_delete');

    Route::post('/article/editsort/{id}', $controller_prefix.'ArticleController@editSort');

    Route::post('/article/restore/{id}', $controller_prefix.'ArticleController@restoreItem')->name('article_restore');
    Route::post('/article/forcedelete/{id}', $controller_prefix.'ArticleController@forceDelete')->name('article_force_delete');

    // search
    Route::post('/article/search', $controller_prefix.'ArticleController@search');
    Route::post('/article/filter', $controller_prefix.'ArticleController@processFilter');

    // preview
    Route::get('/article/preview/{id}', $controller_prefix.'ArticleController@preview')->name('article_preview');
    
    Route::get('/article/categories', $controller_prefix.'Article\CategoryController@getList')->name('article_category');
    Route::get('/article/category/add/{id?}', $controller_prefix.'Article\CategoryController@create')->name('article_category_add');
    Route::get('/article/category/update/{id}', $controller_prefix.'Article\CategoryController@update')->name('article_category_add');
    Route::post('/article/category/delete/{id}', $controller_prefix.'Article\CategoryController@delete')->name('article_category_delete');
    
    Route::post('/article/category/save', $controller_prefix.'Article\CategoryController@save')->name('article_category_save');

    Route::post('/article/category/editsort/{id}', $controller_prefix.'Article\CategoryController@editSort');
    Route::post('/article/category/toggle/{id}', $controller_prefix.'Article\CategoryController@editToggle');
    
    // aticle tag
    Route::get('/article/tags', $controller_prefix.'Article\TagController@getList')->name('article_tag');
    Route::get('/article/tag/add', $controller_prefix.'Article\TagController@create')->name('article_tag_create');
    Route::get('/article/tag/update/{id}', $controller_prefix.'Article\TagController@update')->name('article_tag_update');
    Route::post('/article/tag/delete/{id}', $controller_prefix.'Article\TagController@delete')->name('article_tag_delete');
    Route::post('/article/tag/save', $controller_prefix.'Article\TagController@save')->name('article_tag_save');
    Route::post('/article/tag/editsort/{id}', $controller_prefix.'Article\TagController@editSort');
    Route::get('/article/tags/deleted', $controller_prefix.'Article\TagController@deletedList')->name('article_tag_deleted');
    Route::get('/article/tag/restore/{id}', $controller_prefix.'Article\TagController@restore')->name('article_tag_restore');
    Route::post('/article/tag/forcedelete/{id}', $controller_prefix.'Article\TagController@deleteForce')->name('article_tag_delete_force');
    
    // tag search
    Route::any('/tags/search/{returnJson?}', $controller_prefix.'Taxonomy\TagController@getTagsByName');

    /*========= basic route ========*/

    Route::get('/index', $controller_prefix.'HomeController@index')->name('index');
    Route::get('/dashboard', $controller_prefix.'HomeController@index')->name('dashboard');
    Route::get('/phpinfo', $controller_prefix.'HomeController@getCgiinfo');
    
    Route::get('/certificate', $controller_prefix.'HomeController@certificateInfo')->name('license');
    Route::get('/updateinfo', $controller_prefix.'HomeController@updateInfo');
    //Route::get('/product/update/version', $controller_prefix.'HomeController@getUpdate');
    
    Route::get('/serverinfo', $controller_prefix.'HomeController@serverInfo');
    Route::get('/serverinfo/phpinfo', $controller_prefix.'HomeController@getPhpInfo');

    // setting
    Route::get('/setting', $controller_prefix.'HomeController@settings')->name('setting_system');
    Route::get('/setting/site', $controller_prefix.'HomeController@settings')->name('setting_system');
    Route::post('/setting/site', $controller_prefix.'HomeController@saveSettings');
    Route::get('/setting/logo', $controller_prefix.'HomeController@logo');
    Route::post('/setting/logo', $controller_prefix.'HomeController@saveLogo');
    
    Route::get('/setting/dashboard_info', $controller_prefix.'HomeController@dashaboardLogin');
    Route::post('/setting/dashboard_info', $controller_prefix.'HomeController@saveDashaboardLogin');
    
    Route::get('/setting/browser', $controller_prefix.'HomeController@setBrowser');
    Route::post('/setting/browser', $controller_prefix.'HomeController@saveBrowser');
    
    Route::get('/setting/seo', $controller_prefix.'HomeController@setSeo');
    Route::post('/setting/seo', $controller_prefix.'HomeController@saveSeo');

    // switch language
    Route::get('/setting/switch-language/{lang}', $controller_prefix.'LangController@switchLang');
    
    // dashboard style
    Route::get('/style/dashboard', $controller_prefix.'StyleController@dashboardStyle')->name('setting_dashboard');
    Route::post('/style/dashboard', $controller_prefix.'StyleController@saveDashboardStyle');
    Route::post('/style/dashboard/save', $controller_prefix.'StyleController@setStyle');
    
    // dashboard layout
    Route::get('/style/dashboard.layout', $controller_prefix.'StyleController@dashboardLayout')->name('setting_dashboard_layout');
    Route::post('/style/dashboard.layout/save', $controller_prefix.'StyleController@saveDashboardLayout');

    Route::get('/style/preview/{theme}', $controller_prefix.'StyleController@previewStyle');

    // sidebar menu
    Route::post('/style/sidemenu/{style}', $controller_prefix.'StyleController@sidemenu');
    
    
    // media
    Route::get('/media/hidden',               $controller_prefix.'MediasController@getHiddens')->name('media_file');
    Route::get('/media/files',               $controller_prefix.'MediasController@files')->name('media_file');
    Route::get('/media/images',               $controller_prefix.'MediasController@images')->name('media_file');

    Route::get('/media/setting',               $controller_prefix.'MediasController@setting')->name('media_setting');
    Route::post('/media/setting/save',         $controller_prefix.'MediasController@settingSave')->name('media_setting_save');


    Route::get('/media/{view?}',               $controller_prefix.'MediasController@getAll')->name('media');

    Route::get('/media/update/{id}',   $controller_prefix.'MediasController@editMedia')->name('media_update');
    Route::post('/media/update',   $controller_prefix.'MediasController@updateMedia');
    Route::post('/media/delete/{id}',   $controller_prefix.'MediasController@deleteMedia');

    // batch tag
    Route::get('/medias/batchtag',   $controller_prefix.'MediasController@batchTag')->name('media_retag');
    Route::post('/medias/batchtag/save',   $controller_prefix.'MediasController@batchTagSave')->name('media_retag');

    Route::post('/medias/hiddenbatch',   $controller_prefix.'MediasController@hiddenBatchMedia');
    Route::post('/medias/showbatch',   $controller_prefix.'MediasController@showBatchMedia');

    Route::post('/medias/deletebatch',   $controller_prefix.'MediasController@deleteBatchMedia');
    
    // rebuild thumbs
    Route::get('/media/rebuild/{id}',   $controller_prefix.'MediasController@rebuildMedia')->name('media_rebuild');

    // #TODO make route more clearly and simply.
    // upload route

    Route::post('/component/loadlayout/{layout}/{type}',    $suda_controller_path.'\\'.'ComponentController@loadLayout');

    // medias load modal
    Route::get('/medias/modal/{type}',               $controller_prefix.'Media\MediasController@modal');
    
    // medias upload route
    Route::post('/medias/upload/image/{type?}', $controller_prefix.'Media\MediasController@uploadImage');

    // delete
    Route::post('/medias/remove/image/{type?}', $controller_prefix.'Media\MediasController@removeImage');


    // media tags
    Route::get('/mediatags',               $controller_prefix.'Media\TagController@getList')->name('mediatags');
    Route::get('/mediatags/add', $controller_prefix.'Media\TagController@create')->name('mediatags_create');
    Route::get('/mediatags/update/{id}', $controller_prefix.'Media\TagController@update')->name('mediatags_update');
    Route::post('/mediatags/delete/{id}', $controller_prefix.'Media\TagController@delete')->name('mediatags_delete');
    
    Route::post('/mediatags/save', $controller_prefix.'Media\TagController@save')->name('mediatags_save');

    Route::post('/mediatags/editsort/{id}', $controller_prefix.'Media\TagController@editSort');
    
    
    
    // theme
    Route::get('/theme/{app?}', $controller_prefix.'ThemeController@index')->name('appearance_theme');
    
    Route::get('/themes/{app_path}/{theme_path}/screenshot', $controller_prefix.'ThemeController@getScreenshot');
    Route::post('/theme/updatecache/{app}', $controller_prefix.'ThemeController@updateCache');
    Route::post('/theme/settheme', $controller_prefix.'ThemeController@setTheme');
    
    
    // widgets
    Route::get('/widget/{app?}/{theme?}', $controller_prefix.'WidgetController@index')->name('appearance_widget');
    Route::post('/widget/{slug}/save', $controller_prefix.'WidgetController@saveWidget');
    Route::post('/widget/sort/order', $controller_prefix.'WidgetController@sortOrder');
    Route::post('/widget/remove', $controller_prefix.'WidgetController@removeWidget');
    Route::post('/widget/updatecache', $controller_prefix.'WidgetController@updateCache');
    
    
    // User
    Route::get('/user/list', $controller_prefix.'User\UserController@index')->name('user_list');
    Route::get('/user/add', $controller_prefix.'User\UserController@add');
    Route::get('/user/edit/{id}', $controller_prefix.'User\UserController@edit');
    Route::post('/user/save', $controller_prefix.'User\UserController@saveUser');
    Route::post('/user/delete/{id}', $controller_prefix.'User\UserController@deleteUser');
    
    // register rule
    Route::get('/user/rule/register', $controller_prefix.'User\UserController@ruleRegister')->name('user_register_rule');
    Route::post('/user/rule/save/{type}', $controller_prefix.'User\UserController@ruleSave');
    
    // Role
    Route::get('/user/roles', $controller_prefix.'User\RoleController@index')->name('setting_operate_role');
    Route::get('/user/roles/add', $controller_prefix.'User\RoleController@add');
    Route::get('/user/roles/edit/{id}', $controller_prefix.'User\RoleController@edit');
    Route::post('/user/roles/save', $controller_prefix.'User\RoleController@saveRole');
    Route::post('/user/roles/delete/{id}', $controller_prefix.'User\RoleController@delete');

    // role permissions
    Route::get('/user/roles/showsys/{id}', $controller_prefix.'User\RoleController@showSys');
    Route::post('/user/roles/savesys', $controller_prefix.'User\RoleController@saveSys');

    // role permissions of extensions
    Route::get('/user/roles/showexts/{id}', $controller_prefix.'User\RoleController@showExts');
    Route::post('/user/roles/saveexts', $controller_prefix.'User\RoleController@saveExts');

    //Route::get('/user/roles/setexts/{id}', $controller_prefix.'User\RoleController@setExts');

    Route::get('/user/roles/extDetail/{id}/{slug}', $controller_prefix.'User\RoleController@getExtDetail');


    // Organization
    Route::get('/user/organization', $controller_prefix.'User\OrganizationController@getList')->name('setting_operate_org');;
    Route::get('/user/organization/add/{pid?}', $controller_prefix.'User\OrganizationController@create');
    Route::get('/user/organization/edit/{id}', $controller_prefix.'User\OrganizationController@update');
    Route::post('/user/organization/save', $controller_prefix.'User\OrganizationController@save');
    Route::post('/user/organization/delete/{id}', $controller_prefix.'User\OrganizationController@delete');

    Route::post('/user/organization/editsort/{id}', $controller_prefix.'User\OrganizationController@editSort');

    // operate
    Route::get('/manage/operates', $controller_prefix.'User\OperateController@index')->name('setting_operate');
    Route::get('/manage/operates/add', $controller_prefix.'User\OperateController@add');
    Route::get('/manage/operates/edit/{id}', $controller_prefix.'User\OperateController@edit');

    Route::post('/manage/operates/save', $controller_prefix.'User\OperateController@saveOperate');
    Route::post('/manage/operates/delete/{id}/{force?}', $controller_prefix.'User\OperateController@deleteOperate');

    // soft delete
    Route::get('/manage/operates/{deleted}', $controller_prefix.'User\OperateController@index');
    Route::post('/manage/operates/restore/{id}', $controller_prefix.'User\OperateController@restore');

    // profile
    Route::get('/profile', $controller_prefix.'User\ProfileController@showProfile');
    Route::post('/profile/save', $controller_prefix.'User\ProfileController@saveProfile');
    Route::get('/email', $controller_prefix.'User\ProfileController@editEmail');
    Route::get('/profile/password', $controller_prefix.'User\ProfileController@editPassword');
    Route::post('/profile/changepassword', $controller_prefix.'User\ProfileController@savePassword');
    
    /*
    |--------------------------------------------------------------------------
    | tool
    |--------------------------------------------------------------------------
    |
    | description
    |
    */
    
    Route::get('/menu', $controller_prefix.'Menu\MenuController@menus')->name('tool_menu');
    Route::get('/menu/add', $controller_prefix.'Menu\MenuController@editMenu')->name('tool_menu_add');
    Route::get('/menu/edit/{id}', $controller_prefix.'Menu\MenuController@editMenu')->name('tool_menu_edit');
    Route::post('/menu/save', $controller_prefix.'Menu\MenuController@saveMenu')->name('tool_menu_save');
    Route::post('/menu/delete/{id}', $controller_prefix.'Menu\MenuController@deleteMenu')->name('tool_menu_delete');
    
    Route::get('/menu/items/{id}', $controller_prefix.'Menu\MenuController@items')->name('tool_menu_items');
    Route::get('/menu/item/add/{id}', $controller_prefix.'Menu\MenuController@addItem')->name('tool_menu_item_add');
    Route::get('/menu/item/edit/{id}', $controller_prefix.'Menu\MenuController@editItem')->name('tool_menu_item_edit');
    Route::post('/menu/item/save', $controller_prefix.'Menu\MenuController@saveItem')->name('tool_menu_item_save');
    Route::post('/menu/item/delete/{menu_id}/{id}', $controller_prefix.'Menu\MenuController@deleteItem')->name('tool_menu_item_delete');
    
    Route::post('/menu/order', $controller_prefix.'Menu\MenuController@sortItems')->name('tool_menu_order');
    
    //compass
    Route::get('/compass', $controller_prefix.'Compass\AboutController@index')->name('tool_compass');
    
    Route::get('/compass/commands', $controller_prefix.'Compass\AboutController@commands')->name('tool_compass_commands');
    Route::post('/compass/commands', ['uses' => $controller_prefix.'Compass\AboutController@commands',  'as' => 'tool_compass_commands_post']);
    
    Route::get('/compass/faq', $controller_prefix.'Compass\AboutController@faq')->name('tool_compass_faq');
    Route::get('/compass/about', $controller_prefix.'Compass\AboutController@about')->name('tool_compass_about');
    
    Route::get('/compass/demo', $controller_prefix.'Compass\AboutController@demopage');
    
    // extensions
    Route::get('/manage/extension', $controller_prefix.'Extension\ExtensionController@index')->name('tool_extend');
    Route::get('/manage/extension/{status}', $controller_prefix.'Extension\ExtensionController@index')->name('tool_extend');
    Route::get('/manage/extension/{status}', $controller_prefix.'Extension\ExtensionController@index')->name('tool_extend_disabled');
    Route::get('/manage/extension/{extension_slug}/logo', $controller_prefix.'Extension\ExtensionController@getExtensionLogo')->name('tool_extend_logo');
    
    Route::post('/manage/extension/{extension_slug}/install/', $controller_prefix.'Extension\ExtensionController@toInstall')->name('tool_extend_install');
    Route::post('/manage/extension/{extension_slug}/refresh/', $controller_prefix.'Extension\ExtensionController@flushExtension')->name('tool_extend_flush');
    Route::post('/manage/extension/{extension_slug}/uninstall/', $controller_prefix.'Extension\ExtensionController@toUninstall')->name('tool_extend_uninstall');
    
    Route::post('/manage/extension/updatecache', $controller_prefix.'Extension\ExtensionController@flushExtensions')->name('tool_extend_updatecache');
    
    Route::post('/manage/extension/{extension_slug}/setQuickin/', $controller_prefix.'Extension\ExtensionController@setQuickin')->name('tool_extend_setquickin');
    
    Route::post('/manage/extensionsort', $controller_prefix.'Extension\ExtensionController@resort')->name('tool_extend_resort');

    // Chinese districts data
    Route::get('/areadata/json', $controller_prefix.'Compass\DistrictController@areaJson')->name('district_data');
    

    // extensions dashboard
    Route::get('/entry/extensions', $controller_prefix.'Extension\EntryController@showExtensions')->name('entry_extensions');
    
    Route::get('/entry/extension/{extension_slug}', $controller_prefix.'Extension\EntryController@index')->name('entry_extension');
    
    // load routes
    if(file_exists(base_path('routes/admin.php'))){
        require base_path('routes/admin.php');
    }
    
    
    event(new RoutingAdminAfter());

});