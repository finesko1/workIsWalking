import './bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';
import router from "./router/index.js";
import { createPinia } from 'pinia';
const pinia = createPinia();

createApp(App).use(router).use(pinia).mount('#app');

