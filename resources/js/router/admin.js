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
        component: { render: h => h('router-view') },
        children: [
            {
                path: 'categories',
                component: require('./../components/admin/lookups/categories/Crud').default,
                meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
            },
            {
                path: 'countries',
                component: require('./../components/admin/lookups/countries/Crud').default,
                meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
            },
            {
                path: 'cities',
                component: require('./../components/admin/lookups/cities/Crud').default,
                meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
            },
            {
                path: 'genders',
                component: require('./../components/admin/lookups/genders/Crud').default,
                meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
            },
            {
                path: 'languages',
                component: require('./../components/admin/lookups/languages/Crud').default,
                meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
            },
            {
                path: 'usertypes',
                component: require('./../components/admin/lookups/usertypes/Crud').default,
                meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
            },
            {
                path: 'valutes',
                component: require('./../components/admin/lookups/valutes/Crud').default,
                meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
            },
        ],
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
    {
        path: 'tokens',
        component: require('./../components/admin/Tokens').default,
        meta: { requiresAuth: true, type: 'admin', transitionName: 'fade' }
    },
];

export default adminroutes;
