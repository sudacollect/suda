const mix = require('laravel-mix');

mix.options({
    postCss: [
        require('autoprefixer'),
        require('postcss-discard-comments')({
            removeAll: true
        })
    ],
    cssNano: {
        discardComments: {removeAll: true},
    },
    terser: {
        terserOptions: {
            output: {
                comments: false
            },
            format: {
                comments: false,
            }
        },
        extractComments: false,
    }
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


/*
|--------------------------------------------------------------------------
| formstone
|--------------------------------------------------------------------------
|
| description
| enable pack when upgrade version
|
*/


// mix.copyDirectory('./node_modules/formstone/dist','resources/assets/vendors/formstone');

// mix.styles([
//     './resources/assets/vendors/formstone/css/themes/light.css',
//     './resources/assets/vendors/formstone/css/upload.css'
// ],'resources/assets/vendors/formstone/css/formstone.css');


// mix.scripts([
//     './resources/assets/vendors/formstone/js/core.js',
//     './resources/assets/vendors/formstone/js/upload.js'
// ],'resources/assets/vendors/formstone/js/formstone.js');


/*
|--------------------------------------------------------------------------
| fileupload
|--------------------------------------------------------------------------
|
| description
| enable pack when upgrade version
|
*/

// mix.js([
//     'resources/assets/vendors/jqueryfileupload/js/vendor/jquery.ui.widget.js',
//     'resources/assets/vendors/jqueryfileupload/js/jquery.iframe-transport.js',
//     'resources/assets/vendors/jqueryfileupload/js/jquery.fileupload.js'
// ],'resources/assets/js/jquery.fileupload.js');



/*
|--------------------------------------------------------------------------
| croppie
|--------------------------------------------------------------------------
|
| description
|
*/

// mix.copyDirectory('./node_modules/croppie','publish/assets/js/croppie');


/*
|--------------------------------------------------------------------------
| core
|--------------------------------------------------------------------------
|
| description
|
*/


// mix.copy('node_modules/popper.js/dist/popper.js.map', 'publish/assets/js');

// mix.js('resources/assets/js/plugins/editor.js', 'publish/assets/js/plugins');
// mix.copy('resources/assets/js/plugins/summernote-ext-media.js', 'publish/assets/js/plugins');



// mix.js('resources/assets/js/plugins/upload.js', 'publish/assets/js/plugins');
// mix.js('resources/assets/js/plugins/uploadavatar.js', 'publish/assets/js/plugins');

// mix.js('resources/assets/js/global.js', 'publish/assets/js');

// mix.copyDirectory('resources/assets/vendors/ionicons', 'publish/assets/fonts/vendor/ionicons');

// dashboard
mix.js('resources/assets/js/app.js', 'publish/assets/js/app.js');
mix.options({ processCssUrls: false }).sass('resources/assets/sass/app.scss', 'publish/assets/css');

// front-site
// mix.js('resources/assets/js/app_site.js', 'publish/assets/js/app_site.js');
// mix.options({ processCssUrls: false }).sass('resources/assets/sass/app_site.scss', 'publish/assets/css');


// vendor js
// mix.js('resources/assets/js/app.vendor.js', 'publish/assets/js/app.vendor.js');



/*
|--------------------------------------------------------------------------
| DASHBOARD THEME
|--------------------------------------------------------------------------
|
| description
|
*/


// mix.styles(['./publish/theme/admin/default/design/style.css'],'./publish/theme/admin/default/design/style.min.css');
// mix.styles(['./publish/theme/admin/coffe/design/style.css'],'./publish/theme/admin/coffe/design/style.min.css');
// mix.styles(['./publish/theme/admin/simpleblue/design/style.css'],'./publish/theme/admin/simpleblue/design/style.min.css');
// mix.styles(['./publish/theme/admin/colorbar/design/style.css'],'./publish/theme/admin/colorbar/design/style.min.css');

/*
|--------------------------------------------------------------------------
| MediaManager
|--------------------------------------------------------------------------
|
| description
|
*/

// MediaManager
// mix.js('resources/assets/js/mediamanager.js', './publish/assets/js/mediamanager.js');

// mix.sass('./media-manager/resources/assets/sass/manager.scss', './publish/assets/mediamanager/style.css')
//     .copyDirectory('./media-manager/resources/assets/dist', './publish/assets/mediamanager')


/*
|--------------------------------------------------------------------------
| DEV SETTING
|--------------------------------------------------------------------------
| 
| only for local dev/test
| auto build file to public/vendor folder
|
*/

// mix.options({ processCssUrls: false }).sass('resources/assets/sass/app.scss', '../public/vendor/suda/assets/css/');


// dashboard css
mix.copy('publish/assets/css/app.css', '../public/vendor/suda/assets/css/app.css');

// site css
mix.copy('publish/assets/css/app_site.css', '../public/vendor/suda/assets/css/app_site.css');


// core js
mix.copy('publish/assets/js/app.js', '../public/vendor/suda/assets/js/app.js');

// vendor js
// mix.copy('publish/assets/js/app.vendor.js', '../public/vendor/suda/assets/js/app.vendor.js');
