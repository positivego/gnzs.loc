import axios from "axios";

export default {

    namespaced: true,

    state: {
        load: false,
        token: '',
        subdomain: '',
        entitys: [],
    },
    
    mutations: {

        setLoad(state, value)
        {
            state.load = value
        },

        addEntity(state, entity)
        {
            state.entitys.push(entity)
        },

        setToken(state, token)
        {
            state.token = token
        },

        setSubdomain(state, subdomain)
        {
            state.subdomain = subdomain
        },

    },

    actions: {

        async formingRequest(context, [payload, available])
        {
            if(payload !== '' && available !== '') {

                context.commit('setLoad', true)

                await axios.post('/api/amo/formingRequest', {
                    payload: payload,
                    available: available,
                    token: context.state.token,
                    subdomain: context.state.subdomain,
                })
                .then(response => {
                    context.commit('addEntity', JSON.parse(response.data.entity))
                })
                .catch(error => console.log(error))

                context.commit('setLoad', false)

            }
        },

        async getToken(context)
        {
            axios.post('/api/amo/getToken', {})
            .then(response => {
                let res = JSON.parse(response.data)
                context.commit('setToken', res.access_token)
                context.commit('setSubdomain', res.base_domain)
            })
            .catch(error => console.log(error))
        }

    }

}