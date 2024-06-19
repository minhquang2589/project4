import './bootstrap';
import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import store from './store/index.js';
import vScrollAnimation from './directives/v-scroll-animation.js';

const app = createApp(App);
app.directive('scroll-animation', vScrollAnimation);
app.use(router);
app.use(store);
app.mount('#app');
