import Vue from 'vue'
import VueRouter from 'vue-router'
import AdminRoutes from './admin';

Vue.use(VueRouter)

const routes = [
    {
        path: '/admin',
        component: require('../components/admin/Index').default,
        children: AdminRoutes
    },
    {
        path: '/about',
        name: 'about',
        component: require('../components/Example' +
            '').default
    },
    { path: '/404', name: '404', component: require('../components/404').default, },
    /*{ path: '*', redirect: '/404' },*/
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

export default router
