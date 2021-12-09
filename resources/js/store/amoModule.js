import axios from "axios";

export default {

    namespaced: true,

    state: {
        load: false,
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
        }

    },

    actions: {

        async formingRequest(context, [payload, available])
        {
            if(payload !== '' && available !== '') {

                context.commit('setLoad', true)

                await axios.post('/api/amo/formingRequest', {
                    payload: payload,
                    available: available,
                })
                .then(response => {
                    context.commit('addEntity', JSON.parse(response.data.entity))
                })
                .catch(error => console.log(error))

                context.commit('setLoad', false)

            }
        }

    }

}