<template>
    <v-empty-state headline="Whoops, 404" title="Page not found" image="/error.png">
        The page you were looking for does not exist
        <Link class="text-primary font-weight-bold" :href="beforeRoute"> Back </Link>
    </v-empty-state>
</template>
<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Link } from '@inertiajs/vue3';

export default {
    name: "NotFoundPage",
    layout: GuestLayout,
    components: {
        Link,
    },
    props: {
        beforeRoute: {
            type: String,
        },
    },
    computed: {},
    watch: {},
    async mounted() { },
    methods: {},
};
</script>
<style lang="scss" scoped></style>
