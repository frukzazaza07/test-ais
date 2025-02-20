<template>
    <div>
        <v-row justify="space-between" v-if="showSearch">
            <v-col cols="12" md="auto">
                <Link v-if="action?.create" class="text-white" :href="route(action.create.route?.name)">
                <v-btn color="primary">
                    {{ action.create.route?.label }}
                </v-btn>
                </Link>
                <slot name="actionContainer"></slot>
            </v-col>
            <v-col cols="12" md="3">
                <v-form class="" @submit.prevent="$emit('update:submitSearch', search)">
                    <v-text-field v-bind="{ ...$props.textField }" :model-value="search" @update:model-value="
                        (v) => $emit('update:search', v)
                    "></v-text-field>
                </v-form>
            </v-col>
        </v-row>
        <v-data-table v-bind="{ ...dataTableDefault, ...$props.dataTable }">
            <template v-for="(val) of $props.dataTable?.headers" v-slot:[`header.${val.key}`]="{ column }">
                <slot v-if="$slots[`header.${val.key}`]" :name="`header.${val.key}`"></slot>
                <div v-else class="font-weight-bold">
                    {{ column.title }}
                </div>
            </template>

            <template v-for="(val) of $props.dataTable?.headers"
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
import { Link } from '@inertiajs/vue3';
export default {
    name: "DataTableComponent",
    components: {
        Link,
    },
    props: {
        dataTable: {
            type: Object,
            default: {},
        },
        paginate: {
            type: Object,
            default: {},
        },
        textField: {
            type: Object,
            default: {},
        },
        action: {
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
