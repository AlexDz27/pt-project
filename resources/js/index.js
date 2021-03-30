import '../scss/index.scss';

import { createRouter, createWebHistory } from 'vue-router';
import { createApp } from 'vue';
import App from './components/App';
import Home from './components/routes/Home';
import SignIn from './components/routes/SignIn';
import SignUp from './components/routes/SignUp';
import ForgotPassword from './components/routes/ForgotPassword';
import ResetPassword from './components/routes/ResetPassword';
import SignInGoogle from './components/routes/SignInGoogle';
import Search from './components/routes/Search';
import Location from './components/routes/Location';
import About from './components/routes/About';
import AccessDenied from './components/routes/AccessDenied';
import NotFound from './components/routes/NotFound';

const routes = [
  { path: '/', name: 'home', component: Home },

  { path: '/sign-in', name: 'sign-in', component: SignIn },
  { path: '/sign-up', name: 'sign-up', component: SignUp },
  { path: '/sign-out', redirect: '/' },
  { path: '/forgot-password', name: 'forgot-password', component: ForgotPassword },
  { path: '/reset-password/:token', name: 'reset-password', component: ResetPassword },
  { path: '/sign-in/google', name: 'sign-in-google', component: SignInGoogle },

  { path: '/search', name: 'search', component: Search },

  { path: '/locations/:id', name: 'location', component: Location },

  { path: '/about', name: 'about', component: About },

  // Access denied
  { path: '/access-denied', name: 'access-denied', component: AccessDenied },

  // 404
  { path: '/:pathMatch(.*)*', component: NotFound },
];

const router = createRouter({
  history: createWebHistory(),
  routes
})

const app = createApp(App);
app.use(router);
app.mount('#app');
