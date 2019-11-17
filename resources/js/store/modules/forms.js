import {
    GET_FORMS,
    GET_FORM,
    CREATE_FORM
}  from '../types/forms'
import {
    DATA_REQUEST,
    DATA_ERROR,
    DATA_SUCCESS
} from '../types/data'
    
import formsRepository from '../../repositories/forms'

import Vue from 'vue'

const state = {
    forms: [],
    activeForm: null,
    errors: null,
    status: ''
}

const getters = {
    getForms: state => state.forms,
    formsLoaded: state => state.forms.length,
    getActiveForm: state => state.activeForm,
    activeFormLoaded: state => !!state.activeForm,
    getErrorMessage: state => Object.values(state.errors.data.errors).join('. '),
    getStatus: state => state.status,
}

const actions = {
    [GET_FORMS]: ({commit}) => {
        commit(DATA_REQUEST)
        formsRepository.list()
            .then(response => {
                commit(GET_FORMS, response.data)
            })
            .catch(error => {
                commit(DATA_ERROR, error)
            })
    },
    [GET_FORM]: ({commit}, id) => {
        commit(DATA_REQUEST)
        formsRepository.find(id)
            .then(response => {
                commit(GET_FORM, response.data)
            })
            .catch(error => {
                commit(DATA_ERROR, error)
            })
    },
    [CREATE_FORM]: ({commit, dispatch}, payload) => {
        commit(DATA_REQUEST)
        return formsRepository.create(payload)
            .then(response => {
                commit(DATA_SUCCESS)
                dispatch(GET_FORMS)
                return response
            })
    }
}

const mutations = {
    [DATA_REQUEST]: (state) => {
        state.status = 'loading'
    },
    [DATA_ERROR]: (state, error) => {
        state.errors = error.response
        state.status = 'error'
    },
    [DATA_SUCCESS]: (state) => {
        state.status = 'success'
    },
    [GET_FORMS]: (state, response) => {
        state.status = 'success'
        Vue.set(state, 'forms', response.data)
    },
    [GET_FORM]: (state, response) => {
        state.status = 'success'
        Vue.set(state, 'activeForm', response.data)
    }
}

export default {
    state,
    getters,
    actions,
    mutations,
}