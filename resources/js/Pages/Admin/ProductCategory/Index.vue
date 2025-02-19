<template>
    <CardComponent>
        <template #content>
            <DataTableComponent v-model:currentPage="table.page" v-model:search="table.search"
                v-bind="{ dataTable, paginate, textField }" @update:currentPage="handlePageChange"
                @update:submitSearch="handleSubmitSearch">
                <template #item.id="{ index }">
                    {{ $helpers.setSequence(index, data.meta) }}
                </template>
            </DataTableComponent>
        </template>
    </CardComponent>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTableComponent from '@/Components/DataTable.vue';
import CardComponent from '@/Components/Card.vue';
export default {
    name: "ProductListPage",
    layout: AuthenticatedLayout,
    components: {
        DataTableComponent,
        CardComponent
    },
    props: {
        data: {
            type: Object,
            default: () => [],
        },
        requestData: {
            type: Object,
            default: () => [],
        },
    },
    data: () => ({
        table: {
            headers: [
                {
                    title: '#',
                    sortable: false,
                    key: 'id',
                },
                {
                    title: 'หมวดหมู่ไทย',
                    sortable: false,
                    key: 'nameTh',
                },
                {
                    title: 'หมวดหมู่อังกฤษ',
                    sortable: false,
                    key: 'nameEn',
                },
            ],
            serverItems: [],
            loading: true,
            page: 1,
            pageValue: 1, // for ux on search
            search: '',
            searchValue: '', // ป้องกันอยู่หน้า 5 แต่ serach ข้อมูลได้แค่หน้า2
        },
    }),
    computed: {
        dataTable() {
            return { headers: this.table.headers, items: this.data.data, 'items-per-page': this.data.meta.per_page, loading: this.table.loading }
        },
        paginate() {
            return { length: this.data.meta.last_page }
        },
        textField() {
            return { rules: [...this.$helpers.validateUppercaseString()] }
        },
        queryParams() {
            return { search: this.table.searchValue || null, page: this.table.pageValue || 1 }
        }
    },
    watch: {},
    async mounted() {
        this.table.loading = false
        this.table.page = this.data.meta.current_page
        this.table.search = this.requestData.search
    },
    methods: {
        handleGetData() {
            this.table.loading = true
            this.$inertia.get(
                route(
                    route().current(),
                    { ...this.queryParams },
                ),
            )
        },
        handlePageChange(page) {
            this.table.pageValue = page
            this.handleGetData()
        },
        handleSubmitSearch(search) {
            this.table.searchValue = this.table.search
            this.table.pageValue = 1
            this.handleGetData()
        }
    },
};
</script>
<style lang="scss" scoped></style>
