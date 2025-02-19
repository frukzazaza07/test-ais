<template>
    <v-card>
        <v-card-item>
            <slot name="content"></slot>
        </v-card-item>
    </v-card>
</template>

<script>
export default {
    name: "CardComponent",
    components: {},
    props: {},
    data: () => ({}),
    computed: {},
    watch: {},
    emits: [],
    async mounted() { },
    methods: {},
};
</script>
<style lang="scss" scoped></style>