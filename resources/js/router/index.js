import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store/index';
import AdminRoutes from './admin'

Vue.use(VueRouter)

const routes = [
    {
        path: '/admin',
        name: 'admin',
        component: require('../components/admin/Index').default,
        children: AdminRoutes,
        meta: { requiresAuth: true, type: 'admin' }
    },
    {
        path: '/about',
        name: 'about',
        component: require('../components/Example').default,
        meta: { requiresAuth: false }
    },
    //{ path: '/error', name: 'error', component: require('../components/Error').default, meta: { requiresAuth: false } },
    { path: '*', component: require('../components/Error').default, meta: { requiresAuth: false } },
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

router.beforeEach((to, from, next) => {
    console.log('router paths', from, to);

    if (to.matched.some(record => record.meta.requiresAuth == false)) {
        console.log('router no auth', to);
        next()
    }
    else if (to.matched.some(record => record.meta.requiresAuth)) {
        console.log('router yes auth', to);
        if (!store.getters.isAuthed) {
            next({
                path: '/error'
            })
        } else next()
    }
    else  {
        console.log('router just next');
        next()
    }
});

export default router
