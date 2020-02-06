const adminroutes = [
    {
        path: '',
        component: require('./../components/admin/Dashboard').default
    },
    {
        path: 'contracts',
        component: require('./../components/admin/Contracts').default
    },
    {
        path: 'places',
        component: require('./../components/admin/Places').default
    },
    {
        path: 'people',
        component: require('./../components/admin/People').default
    },
    {
        path: 'tickets',
        component: require('./../components/admin/Tickets').default
    },
    {
        path: 'lookups',
        component: require('./../components/admin/Lookups').default
    },
    {
        path: 'tokens',
        component: require('./../components/admin/Tokens').default
    },
];

export default adminroutes;
