require('./jquery.1.8.3.min');
require('./bootstrap');
require('./jquery-scrolltofixed');
require('./jquery.easing.1.3');
require('./jquery.isotope');
const WOW = require('./wow.min');
require('./classie');
require('./featherlight.min');
require('./contactform');
require('./bottom');


window.wow = new WOW.WOW({
    animateClass: 'animated',
    offset: 100,
    live: false
});
window.wow.init();
