import '../scss/index.scss';

import { createRouter, createWebHistory } from 'vue-router';
import { createApp } from 'vue';
import App from './components/App';
import Home from './components/routes/Home';
import SignIn from './components/routes/SignIn';
import SignUp from './components/routes/SignUp';
import About from './components/routes/About';
import NotFound from './components/routes/NotFound';

const routes = [
  { path: '/', name: 'home', component: Home },

  { path: '/sign-in', name: 'sign-in', component: SignIn },
  { path: '/sign-up', name: 'sign-up', component: SignUp },
  { path: '/sign-out', redirect: '/' },

  { path: '/about', name: 'about', component: About },

  { path: '/:pathMatch(.*)*', component: NotFound },
];

const router = createRouter({
  history: createWebHistory(),
  routes
})

const app = createApp(App);
app.use(router);
app.mount('#app');
