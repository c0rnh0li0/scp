import router from "./router";

axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrf;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.localStorage.getItem('token');
axios.interceptors.response.use(function (response) {
    console.log('global axios success', response)
    return response;
}, function (error) {
    router.push('/' + arguments[0].response.status)
});
