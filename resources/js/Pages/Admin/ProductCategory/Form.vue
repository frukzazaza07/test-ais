<template>
    <CardComponent>
        <template #content>
            <FormComponent ref="formRef" :action="action" :title="title" class="mt-6" @submit.prevent="submit">
                <template #input>
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field v-model="form.nameTh"
                                :rules="[...ruleRequired, ...this.$helpers.rules.max(100), ...this.$helpers.rules.validateThaiCharString()]"
                                label="ชื่อไทย"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-text-field v-model="form.nameEn"
                                :rules="[...ruleRequired, ...this.$helpers.rules.max(100), ...this.$helpers.rules.validateEngCharString()]"
                                label="ชื่ออังกฤษ"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-text-field v-model="form.prefixSerialNumber"
                                :rules="[...ruleRequired, ...this.$helpers.rules.max(10), ...this.$helpers.rules.validateUppercaseString(false)]"
                                label="Prefix Product Serial Number" :readonly="form.id ? true : false"
                                :hint="form.id ? 'ไม่สามารถแก้ไขได้ เนื่องจากมีการใช้งานใน Product Serial Number' : null"></v-text-field>
                        </v-col>
                    </v-row>
                </template>
            </FormComponent>
        </template>
    </CardComponent>

</template>

<script>
import FormComponent from '@/Components/Form.vue'
import { useForm } from '@inertiajs/vue3';
import CardComponent from '@/Components/Card.vue';

export default {
    name: "AdminProductCategoryFormComponent",
    components: {
        FormComponent,
        CardComponent
    },
    props: {
        data: {
            type: Object,
            default: {},
        },
        formType: {
            type: String,
            default: 'create',
        },
        title: {
            type: String,
            default: '',
        },
    },
    data: () => ({
        form: useForm({
            nameTh: '',
            nameEn: '',
            prefixSerialNumber: '',
        }),
        action: {
            route: {
                beforeRoute: 'admin.product-category.index'
            }
        },

    }),
    computed: {
        ruleRequired() {
            return [...this.$helpers.rules.required()]
        },
        urlSubmit() {
            return this.formType == 'edit' ? route('admin.product-category.update', { product_category: this.form.id }) : route('admin.product-category.store')
        }
    },
    watch: {},
    emits: [
        'formSubmit',
    ],
    async mounted() {
        if (this.formType == 'edit' && this.data) {
            this.form = useForm({ ...this.data, _method: 'PUT', pcId: this.data.id })
        }
    },
    methods: {
        async submit() {
            const valid = await this.$refs.formRef.vFormRef.validate();
            if (!valid.valid) return;
            this.form.post(this.urlSubmit);
        },
    },
};
</script>
<style lang="scss" scoped></style>