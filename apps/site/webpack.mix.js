const mix = require('laravel-mix');

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

mix
    .options({ processCssUrls: false })

mix.styles([
        'public/frontend/css/reponsive.css',
        'public/frontend/css/boostrap.css',
        // 'public/common/toastr/toastr.min.css',
        // 'public/frontend/external/select2/select2.min.css',
        // 'public/frontend/external/fontawesome/css/all.css',
        // 'public/frontend/external/slick/slick.min.css',
        'public/frontend/external/sweetalert/css/sweetalert.css',
        'public/frontend/css/styles.css',
        'public/frontend/css/new_style.css',
    ],
    'public/frontend/css/all.css');

mix.scripts(
    [
        'public/frontend/js/jquery.min.js',
        'public/backend/global_assets/js/plugins/tables/datatables/datatables.min.js',
        'public/frontend/js/boostrap.js',
        'public/frontend/js/main.js',
        'public/frontend/js/custom.js',
        'public/backend/global_assets/js/plugins/notifications/bootbox.min.js',
        'public/backend/global_assets/js/plugins/notifications/pnotify.min.js',
        'public/frontend/js/jquery.validate.min.js',
        'public/frontend/external/select2/select2.min.js',
        'public/frontend/external/slick/slick.min.js',
        'public/frontend/external/sweetalert/js/sweetalert.min.js',
        'public/frontend/js/custom.js',
        'public/common/toastr/toastr.min.js',
        'public/common/js/ajax-form.js',
        'public/common/js/contact.js',
        'public/common/js/subscribe-email.js',
        'public/frontend/external/lazy/lazysizes.min.js',
    ],
    'public/frontend/js/all.js'
);

mix.js('resources/js/editor-admin.js', 'public/backend/js')
    .copy('node_modules/tinymce/skins', 'public/backend/js/wysiwyg/skins')
    .copy('resources/js/vi_VN.js', 'public/backend/js');

if (mix.inProduction()) {
    mix.version();
}
