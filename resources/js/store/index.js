import Vue from 'vue'
import Vuex from 'vuex'

import forms from './modules/forms'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        forms,
    },
})