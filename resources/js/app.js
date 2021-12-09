import Vue from "vue";
import store from "./store/store.js";
import App from "./components/App.vue";
import axios from "axios";

Vue.prototype.axios = axios

const app = new Vue({
    store,
    render: h => h(App)
}).$mount('#app')