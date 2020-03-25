const adminroutes = [
    {
        path: '/admin/dashboard',
        component: require('./../components/admin/Dashboard').default
    },
    {
        path: '/admin/contracts',
        component: require('./../components/admin/Contracts').default
    },
    {
        path: '/admin/places',
        component: require('./../components/admin/Places').default
    },
    {
        path: '/admin/people',
        component: require('./../components/admin/People').default
    },
    {
        path: '/admin/tickets',
        component: require('./../components/admin/Tickets').default
    },
    {
        path: '/admin/lookups',
        component: require('./../components/admin/Lookups').default
    },
    {
        path: '/admin/tokens',
        component: require('./../components/admin/Tokens').default
    },
];

export default adminroutes;
