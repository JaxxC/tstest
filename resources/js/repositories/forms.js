export default {
    find(id) {
        return axios({url: `/api/form/${id}`, method: 'GET' })
    },
    list() {
        return axios({url: `/api/forms`, method: 'GET' })
    },
    create(payload) {
        return axios({url: '/api/form', data:payload, method: 'POST' })
    }
}