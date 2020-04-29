const touristroutes = [
    {
        path: 'dashboard',
        component: require('./../components/tourist/Dashboard').default,
        meta: { requiresAuth: true, type: 'tourist', transitionName: 'fade' }
    },
    {
        path: 'offers',
        component: require('./../components/tourist/Offers').default,
        meta: { requiresAuth: true, type: 'tourist', transitionName: 'fade' }
    },
    {
        path: 'map',
        component: require('./../components/tourist/Map').default,
        meta: { requiresAuth: true, type: 'tourist', transitionName: 'fade' }
    },
    {
        path: 'tickets',
        component: require('./../components/tourist/Tickets').default,
        meta: { requiresAuth: true, type: 'tourist', transitionName: 'fade' }
    },
    {
        path: 'profile',
        component: require('./../components/tourist/Profile').default,
        meta: { requiresAuth: true, type: 'tourist', transitionName: 'fade' }
    },
    {
        path: 'business/:id/:scope',
        component: require('./../components/place/Business').default,
        meta: { requiresAuth: true, type: 'tourist', transitionName: 'fade' }
    },
];

export default touristroutes;
