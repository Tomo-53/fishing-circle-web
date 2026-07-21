import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// トップページ用（#opening 等が無ければ no-op）
import './welcome.js';
