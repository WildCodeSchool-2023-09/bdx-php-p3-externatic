import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import '@fontsource-variable/jost';
import imagePath from './images/registration.svg';
import 'bootstrap-icons/font/bootstrap-icons.css';

let html = `<img src="${imagePath}" alt="business meeting">`;
//import './styles/footer.scss'

// start the Stimulus application
import './bootstrap';
require ('bootstrap');

