let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/shopbox.js', 'public/assets/js')
   .js('resources/assets/js/sweetalert2.js', 'public/assets/js')
   .js('resources/assets/js/module.js', 'public/assets/js')
   .js('resources/assets/js/category_form.js', 'public/assets/js')
   .js('resources/assets/js/attribute_form.js', 'public/assets/js')
   .js('resources/assets/js/product_form.js', 'public/assets/js')
   .js('resources/assets/js/feature_form.js', 'public/assets/js')
   .js('resources/assets/js/brand_form.js', 'public/assets/js')
   .js('resources/assets/js/tax_form.js', 'public/assets/js')
   .js('resources/assets/js/shipping_form.js', 'public/assets/js')
   .js('resources/assets/js/shipping_class_form.js', 'public/assets/js')
   .js('resources/assets/js/store_form.js', 'public/assets/js')
   .js('resources/assets/js/stock.js', 'public/assets/js')
   .js('resources/assets/js/customer.js', 'public/assets/js')
   .js('resources/assets/js/discount.js', 'public/assets/js')
   .js('resources/assets/js/menu.js', 'public/assets/js')
   .js('resources/assets/js/cod.js', 'public/assets/js')
   .js('resources/assets/js/backgrid.js', 'public/assets/js')
   .js('resources/assets/js/checkout.js', 'public/assets/js')
   .js('resources/assets/js/theme_editor.js', 'public/assets/js')
   .js('resources/assets/js/order.js', 'public/assets/js')
   .js('Modules/Page/Assets/webpage.js', 'public/assets/js')
   .js('Modules/Blog/Assets/blog.js', 'public/assets/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/shopbox.scss', 'public/assets/css')
   .sass('resources/assets/sass/sweetalert2.scss', 'public/assets/css')
   .sass('resources/assets/sass/checkout.scss', 'public/assets/css')
   .options({
      processCssUrls: false
   });
