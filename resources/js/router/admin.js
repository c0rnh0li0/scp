const adminroutes = [
    {
        path: 'dashboard',
        component: require('./../components/admin/Dashboard').default,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
    {
        path: 'settings',
        component: require('./../components/admin/Settings').default,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
    {
        path: 'contracts',
        component: require('./../components/admin/Contracts').default,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
    {
        path: 'places',
        component: require('./../components/admin/Places').default,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
    {
        path: 'people',
        component: require('./../components/admin/People').default,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
    {
        path: 'tickets',
        component: require('./../components/admin/Tickets').default,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
    {
        path: 'offers',
        component: require('./../components/admin/Offers').default,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
    {
        path: 'lookups',
        component: require('./../components/admin/Lookups').default,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
    {
        path: 'tokens',
        component: require('./../components/admin/Tokens').default,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
];

export default adminroutes;
