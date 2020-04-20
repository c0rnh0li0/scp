import Vue from 'vue'

Vue.config.silent = false
Vue.config.productionTip = false

Vue.config.errorHandler = function (err, vm, info) {
    console.log('global error handler: ')
    console.log('err: ', err)
    console.log('vm: ', vm)
    console.log('info: ', info)
}

Vue.config.warnHandler = function (msg, vm, trace) {
    // `trace` is the component hierarchy trace
    console.log('global warn handler: ')
    console.log('msg: ', msg)
    console.log('vm: ', vm)
    console.log('trace: ', trace)
}