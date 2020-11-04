/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';
import '../css/css-iris/style.css';
import '../css/css-iris/adminpro-custon-icon.css';
import '../css/css-iris/meanmenu.min.css';
import '../css/css-iris/normalize.css';
import '../css/plugin/responsive-calendar.css';
import '../js/iris-js/script_iris'

const $ = require('jquery');
global.$ = global.jQuery = $;
require('Bootstrap')
require('../js/iris-js/jquery-ui.min')
require( '@ckeditor/ckeditor5-build-classic' )
require('../js/iris-js/jquery.meanmenu')
require('../js/iris-js/jquery.validate.min')
require('../js/iris-js/jquery.mCustomScrollbar.concat.min')
require('../js/plugin/responsive-calendar')
// require('../js/iris-js/jquery.sticky')
require('../js/iris-js/main')
require('select2/dist/js/select2.full');
require('../js/iris-js/select2entity.js')
require('datatables.net')
require('datatables.net-dt')
require('bootstrap-datepicker')
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.fr')
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.en-GB')
require('datatables.net-dt/js/dataTables.dataTables.min')
require('tablesorter/dist/js/jquery.tablesorter')
require('tablesorter/dist/js/jquery.tablesorter.widgets')
require('tablesorter/dist/js/widgets/widget-columnSelector.min')
// jQuery.htmlPrefilter = function( html ) {
//     return html;
// };
// const steky require('/assets/js/iris-js/jquery.sticky')


// import '../css/cssIris/responsive.css';
// import '../css/cssIris/font-awesome.min.css';


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
// $(function () {
//     $('[data-toggle="tooltip"]').tooltip()
// })
// $(document).ready(function() {
//     $('[data-toggle="popover"]').popover();
// });

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
