import Vue from 'vue'
import VueRouter from 'vue-router'
import Admin from '../components/admin/Index.vue'
import Example from '../components/Example.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/admin',
    name: 'admin',
    component: Admin
  },
  {
    path: '/about',
    name: 'about',
    component: Example
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
