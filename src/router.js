import { createRouter, createWebHashHistory } from 'vue-router'

import Home from './components/Home.vue';
import About from './components/About.vue';
import Comic from './components/Comic.vue';
import Comics from './components/Comics.vue';
import Results from './components/Results.vue';

const routes = [
  {
    name: 'Home',
    path: '/',
    component: Home
  },
  {
    name: 'About',
    path: '/about',
    component: About
  },
  {
    name: 'Comic',
    path: '/comic/:id',
    component: Comic
  },
  {
    name: 'EntityComics',
    path: '/:entity/:id/comics',
    component: Comics
  },
  {
    name: 'EntityResults',
    path: '/search/:entity',
    component: Results
  },
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
