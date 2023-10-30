const mix_ = require('laravel-mix');

var _asset = './assets/';

mix_.setPublicPath('./dist')
    .js([
        _asset + 'js/plugins/bootstrap/transition.js',
        _asset + 'js/plugins/bootstrap/alert.js',
        _asset + 'js/plugins/bootstrap/button.js',
        _asset + 'js/plugins/bootstrap/carousel.js',
        _asset + 'js/plugins/bootstrap/collapse.js',
        _asset + 'js/plugins/bootstrap/dropdown.js',
        _asset + 'js/plugins/bootstrap/modal.js',
        _asset + 'js/plugins/bootstrap/tooltip.js',
        _asset + 'js/plugins/bootstrap/popover.js',
        _asset + 'js/plugins/bootstrap/scrollspy.js',
        _asset + 'js/plugins/bootstrap/tab.js',
        _asset + 'js/plugins/bootstrap/affix.js',
        _asset + 'js/_main.js'
    ], 'js/main.min.js')
    .scripts(_asset + 'js/modernizr.custom.js', 'dist/js/modernizr.js')
    .less(_asset + 'less/app.less', 'dist/css/main.min.css')
    .options({
        processCssUrls: false
    })
    .styles([
        _asset + 'fonts/fontello/css/fontello.css',
        _asset + 'css/GentiumBookBasic.css'
    ], 'dist/css/fonts.css')
    .styles(_asset + 'fonts/fontello/css/fontello-ie7.css', 'dist/css/fontello-ie7.css')
    .styles(_asset + 'css/jquery-ui.min.css', 'dist/css/jquery-ui.min.css')
    .styles(_asset + 'css/ie7.css', 'dist/css/ie7.css')
    .styles(_asset + 'css/ie7and8.css', 'dist/css/ie7and8.css')
    .styles(_asset + 'css/old-ie.css', 'dist/css/old-ie.css')
    .copy(_asset + 'img/*', 'dist/img/')
    .copy(_asset + 'fonts/fontello/font/*', 'dist/font/')
    .copy(_asset + 'fonts/proximanova/font/*', 'dist/css/');


if (mix_.inProduction()) {
    mix_.version();
} else {
    mix_.sourceMaps();
}
