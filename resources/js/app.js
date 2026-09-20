import './bootstrap';

import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';
import './alertas';
import './tema';

import Alpine from 'alpinejs';

window.bootstrap = bootstrap;
window.Alpine = Alpine;

Alpine.start();
