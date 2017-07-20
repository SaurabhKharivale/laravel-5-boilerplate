import Vue from 'vue';
import Vuex from 'vuex';
import dashboard from './dashboard.js';

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        dashboard,
    }
});

export default store;
