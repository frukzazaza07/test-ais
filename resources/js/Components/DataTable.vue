<template>
    <div>
        <v-row justify="end" v-if="showSearch">
            <v-col cols="12" md="3">
                <v-form class="" @submit.prevent="$emit('update:submitSearch', search)">
                    <v-text-field :model-value="search" @update:model-value="
                        (v) => $emit('update:search', v)
                    "></v-text-field>
                </v-form>
            </v-col>
        </v-row>
        <v-data-table v-bind="{ ...dataTableDefault, ...$props.dataTable }">
            <template v-for="(val, index) of $props.dataTable?.headers" v-slot:[`header.${val.key}`]="{ column }">
                <slot v-if="$slots[`header.${val.key}`]" :name="`header.${val.key}`"></slot>
                <div v-else class="font-weight-bold">
                    {{ column.title }}
                </div>
            </template>

            <template v-for="(val, index) of $props.dataTable?.headers"
                v-slot:[`item.${val.key}`]="{ item, internalItem, index }">
                <slot v-if="$slots[`item.${val.key}`]" :name="`item.${val.key}`" :item="item" :index="index"></slot>
                <div v-else class="font-weight-bold">
                    {{ internalItem?.columns?.[val.key] || '' }}
                </div>
            </template>

            <template v-slot:bottom> </template>
        </v-data-table>
        <div class="d-flex justify-end">
            <v-pagination v-bind="{ ...paginateDefault, ...$props.paginate }" :model-value="currentPage"
                @update:model-value="
                    (v) => $emit('update:currentPage', v)
                "></v-pagination>
        </div>
    </div>
</template>
<script>
export default {
    name: "DataTableComponent",
    components: {},
    props: {
        dataTable: {
            type: Object,
            default: {},
        },
        paginate: {
            type: Object,
            default: {},
        },
        currentPage: {
            type: Number,
            default: 1,
        },
        search: {
            type: Number,
            default: '',
        },
        showSearch: {
            type: Boolean,
            default: true,
        },
        search: {
            type: String,
            default: '',
        },

    },
    data: () => ({
        dataTableDefault: {
            'items-per-page': 20,
            'no-data-text': "ไม่พบข้อมูล",
            class: 'mt-4',
        },
        paginateDefault: {
            'total-visible': "7",
            'class': "my-4",
        },
    }),
    computed: {},
    watch: {},
    emits: [
        "update:search",
        "update:currentPage",
        "update:submitSearch",
    ],
    async mounted() { },
    methods: {},
};
</script>
<style lang="scss" scoped></style>
