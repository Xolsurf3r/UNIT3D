let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 |
 */

mix
  .version()

  .webpackConfig({
    module: {
      // configuration regarding modules
      loaders: [
        { test: /\.js$/, loader: 'babel', query: {compact: false} }
      ]
    }
  })
  /*
   * Sourced asset dependencies via node_modules and JS bootstrapping
   */
  .js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css')

  /*
   * Themes
   *
   * Note: Default wysibb theme is compiled into public/css/app.css from resources/assets/sass/app.scss
   *
   */
  .sass('resources/assets/sass/themes/galactic.scss', 'public/css/themes/galactic.css')
  .sass('resources/assets/sass/themes/dark-blue.scss', 'public/css/themes/dark-blue.css')
  .sass('resources/assets/sass/themes/dark-green.scss', 'public/css/themes/dark-green.css')
  .sass('resources/assets/sass/themes/dark-pink.scss', 'public/css/themes/dark-pink.css')
  .sass('resources/assets/sass/themes/dark-purple.scss', 'public/css/themes/dark-purple.css')
  .sass('resources/assets/sass/themes/dark-red.scss', 'public/css/themes/dark-red.css')
  .sass('resources/assets/sass/themes/dark-teal.scss', 'public/css/themes/dark-teal.css')
  .sass('resources/assets/sass/themes/dark-yellow.scss', 'public/css/themes/dark-yellow.css')
  
  /*
   * Login and TwoStep Auth styles
   *
   * We compile each of these separately since they should only be loaded with the certain views
   *
   * Note: These will likely be reworked into VueJS component(s)
   */
  .sass('resources/assets/sass/main/login.scss', 'public/css/main/login.css')
  .sass('resources/assets/sass/main/twostep.scss', 'public/css/main/twostep.css')
  .babel('resources/assets/js/unit3d/twostep.js', 'public/js/twostep.js')

  /*
   * Here we take all these scripts and compile them into a single 'unit3d.js' file that will be loaded after 'app.js'
   *
   * Note: The order of this array will matter, no different then linking these assets manually in the html
   */
  .babel([
    'resources/assets/js/unit3d/hoe.js',
    'resources/assets/js/unit3d/vendor/livicons.js',
    'resources/assets/js/unit3d/blutopia.js'
  ], 'public/js/unit3d.js')
  
  /*
   * Copy emojione assets
   */
  .copy('node_modules/emojione-assets/png/64', 'public/img/emojione');

// Full API
// mix.js(src, output);
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.less(src, output);
// mix.combine(files, destination);
// mix.copy(from, to);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public');
// mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
// mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
