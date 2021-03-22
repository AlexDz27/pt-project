import '../scss/index.scss';

import { createRouter, createWebHistory } from 'vue-router';
import { createApp } from 'vue';
import App from './components/App';
import Home from './components/Home';
import Foo from "./components/Foo";

const routes = [
  { path: '/', component: Home },
  { path: '/foo', component: Foo }
];

const router = createRouter({
  history: createWebHistory(),
  routes
})

const app = createApp(App);
app.use(router);
app.mount('#app');
