const placeroutes = [
    {
        path: 'scan',
        component: require('./../components/place/Scan').default
    },
    {
        path: 'statistics',
        component: require('./../components/place/Dashboard').default
    },
    {
        path: 'profile',
        component: require('./../components/place/Profile').default
    },
    {
        path: 'offers',
        component: require('./../components/place/Offers').default
    },
    {
        path: 'business/:id/:scope',
        component: require('./../components/place/Business').default
    },
];

export default placeroutes;
