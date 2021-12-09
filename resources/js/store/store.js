import Vue from "vue";
import Vuex from "vuex";
import amoModule from "./amoModule";

Vue.use(Vuex);

export default new Vuex.Store({

    modules: {
        amo: amoModule
    }

})