import '../scss/index.scss';

import { createRouter, createWebHistory } from 'vue-router';
import { createApp } from 'vue';
import App from './components/App';
import Home from './components/Home';
import SignIn from './components/SignIn';
import SignUp from './components/SignUp';
import About from './components/About';
import NotFound from './components/NotFound';

const routes = [
  { path: '/', component: Home },

  { path: '/sign-in', component: SignIn },
  { path: '/sign-up', component: SignUp },

  { path: '/about', component: About },

  { path: '/:pathMatch(.*)*', component: NotFound },
];

const router = createRouter({
  history: createWebHistory(),
  routes
})

const app = createApp(App);
app.use(router);
app.mount('#app');
