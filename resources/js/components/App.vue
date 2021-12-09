<template>
    <div class="form">

        <div class="entitys__list">
            <div class="entity" v-for="entity in $store.state.amo.entitys" :key="entity.name">
                {{ entity.id }} - {{ entity.name }}
            </div>
        </div>

        <div class="form__conteiner">

            <div class="form__item">
            
                <div class="title"><p>Наименование</p></div>

                <input type="text" v-model="payload.name" placeholder="Введите наименование">

            </div>

            <div class="form__item">

                <div class="title"><p>Выберите тип</p></div>

                <select v-model="available">
                    <option value="">Не выбрано</option>
                    <option value="leads">Сделка</option>
                    <option value="contacts">Контакт</option>
                    <option value="companies">Компания</option>
                </select>

            </div>

            <div class="form__item">

                <div class="button__item" :class="{active: available !== ''}">
                    <div class="button" @click="formingRequest([payload, available])"><p>Сохранить</p></div>
                    <div class="placeholder" v-if="$store.state.amo.load === true"><div class="spinner"></div></div>
                </div>

            </div>

        </div>

    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    
    name: 'App',

    data: () => ({

        available: '',
        payload: {
            name: '',
        },

    }),

    methods: {
        ...mapActions({
            formingRequest: 'amo/formingRequest',
        })
    },

}
</script>