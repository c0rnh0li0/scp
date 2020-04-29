const placeroutes = [
    {
        path: 'scan',
        component: require('./../components/place/Scan').default,
        meta: { requiresAuth: true, type: 'place', transitionName: 'fade' }
    },
    {
        path: 'dashboard',
        component: require('./../components/place/Dashboard').default,
        meta: { requiresAuth: true, type: 'place', transitionName: 'fade' }
    },
    {
        path: 'profile',
        component: require('./../components/place/Profile').default,
        meta: { requiresAuth: true, type: 'place', transitionName: 'fade' }
    },
    {
        path: 'offers',
        component: require('./../components/place/Offers').default,
        meta: { requiresAuth: true, type: 'place', transitionName: 'fade' }
    },
    {
        path: 'business/:id/:scope',
        component: require('./../components/place/Business').default,
        meta: { requiresAuth: true, type: 'place', transitionName: 'fade' }
    },
];

export default placeroutes;
