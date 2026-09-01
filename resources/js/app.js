import './bootstrap';

import 'bootstrap/dist/js/bootstrap.bundle.min.js';

import Alpine from 'alpinejs';
import fotosDropzone from './fotos-dropzone';

window.fotosDropzone = fotosDropzone;
window.Alpine = Alpine;

Alpine.start();
