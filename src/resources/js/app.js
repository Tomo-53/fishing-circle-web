import './bootstrap';

import Alpine from 'alpinejs';
import galleryLightbox from './gallery-lightbox';

window.Alpine = Alpine;

Alpine.data('galleryLightbox', galleryLightbox);

Alpine.start();

// トップページ用（#opening 等が無ければ no-op）
import './welcome.js';
