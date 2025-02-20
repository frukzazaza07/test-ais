<template>
    <AdminProductFormComponent title="แก้ไขสินค้า" :select="select" form-type="edit" :data="data?.data">
    </AdminProductFormComponent>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminProductFormComponent from "./Form.vue";
export default {
    name: "AdminProductฎกระPage",
    layout: AuthenticatedLayout,
    components: {
        AdminProductFormComponent,
    },
    props: {
        data: {
            type: Object,
            default: null
        },
        select: {
            type: Object,
            default: {
                productCategory: []
            },
        },
    },
    data() {
        return {};
    },
    computed: {},
    mounted() { },
    methods: {},
};
</script>
