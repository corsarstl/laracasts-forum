<template>
    <button class="btn"
            :class="classes"
            @click="subscribe" v-text="status"></button>
</template>

<script>
    export default {
        props: ['active'],

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-default'];
            },

            status() {
                return this.active ? 'Unsubscribe' : 'Subscribe';
            }
        },

        methods: {
            subscribe() {
                axios[
                    (this.active ? 'delete' : 'post')
                    ](location.pathname + '/subscriptions');

                this.active = ! this.active;
            }
        }
    }
</script>