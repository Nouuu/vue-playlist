import Vue from 'vue'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify';
import axios from 'axios';

Vue.config.productionTip = false;

axios.defaults.withCredentials = true;

Vue.prototype.$http = axios;

// Vue.prototype.$api_url = 'http://localhost/';
Vue.prototype.$api_url = 'https://api.metal.nospy.fr/';

new Vue({
    router,
    vuetify,
    render: h => h(App)
}).$mount('#app')
