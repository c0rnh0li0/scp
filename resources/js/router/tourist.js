const touristroutes = [
    {
        path: 'dashboard',
        component: require('./../components/tourist/Dashboard').default
    },
    {
        path: 'offers',
        component: require('./../components/tourist/Offers').default
    },
    {
        path: 'map',
        component: require('./../components/tourist/Map').default
    },
    {
        path: 'tickets',
        component: require('./../components/tourist/Tickets').default
    },
    {
        path: 'profile',
        component: require('./../components/tourist/Profile').default
    },
    {
        path: 'business/:id/:scope',
        component: require('./../components/place/Business').default
    },
];

export default touristroutes;
