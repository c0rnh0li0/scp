import Vue from 'vue'
import store from './../store/index';
import VueRouter from 'vue-router'
import AdminRoutes from './admin';
import PlaceRoutes from './place';
import TouristRoutes from './tourist';

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: require('../components/Home').default,
        meta: { requiresAuth: false }
    },
    {
        path: '/admin',
        component: require('../components/admin/Index').default,
        children: AdminRoutes,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
    {
        path: '/place',
        component: require('../components/place/Index').default,
        children: PlaceRoutes,
        meta: { requiresAuth: true, type: 'place', transitionName: 'fade' }
    },
    {
        path: '/home',
        component: require('../components/tourist/Index').default,
        children: TouristRoutes,
        meta: { requiresAuth: true, type: 'tourist', transitionName: 'fade' }
    },
    {
        path: '/offline',
        name: 'offline',
        component: require('../components/Offline').default,
        meta: { requiresAuth: false }
    },
    {
        path: '/about',
        name: 'about',
        component: require('../components/Example').default,
        meta: { requiresAuth: false }
    },
    { path: '/404', name: '404', component: require('../components/errors/Error404').default, meta: { requiresAuth: false } },
    { path: '/401', name: '401', component: require('../components/errors/Error401').default, meta: { requiresAuth: false } },
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

const isContractValid = function(contract) {
    if (!contract)
        return false
    if (!contract.valid || !contract.paid)
        return false
    if (new Date() > new Date(Date.parse(contract.expires_at)))
        return false
    if (new Date() < new Date(Date.parse(contract.start_at)))
        return false

    return true
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth == false)) {
        next()
    }
    else if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.state.session.user) {
            if (store.state.type == to.meta.type) {
                if (store.state.type == 'place' && !isContractValid(store.state.session.contract)) {
                    if (to.name == 'contract')
                        next()
                    else
                        next({
                            path: '/place/contract'
                        })
                }
                else next()
            }
            else
                next({
                    path: '/401'
                })
        }
        else
            next()
    }
    else  {
        next()
    }
});

export default router
