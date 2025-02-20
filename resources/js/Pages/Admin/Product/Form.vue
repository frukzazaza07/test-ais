<template>
    <CardComponent>
        <template #content>
            <FormComponent ref="formRef" :action="action" :title="title" class="mt-6" @submit.prevent="submit">
                <template #input>
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-autocomplete v-model="form.pcId" :rules="[...ruleRequired]"
                                :items="select.productCategory" label="หมวดหมู่"></v-autocomplete>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-text-field v-model="form.nameTh"
                                :rules="[...ruleRequired, ...this.$helpers.rules.max(100)]"
                                label="ชื่อไทย"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-text-field v-model="form.nameEn"
                                :rules="[...ruleRequired, ...this.$helpers.rules.max(100)]"
                                label="ชื่ออังกฤษ"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-text-field v-model.number="form.price"
                                :rules="[...ruleRequired, ...this.$helpers.rules.max(11)]" label="ราคา"></v-text-field>
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
    name: "AdminProductFormComponent",
    components: {
        FormComponent,
        CardComponent
    },
    props: {
        data: {
            type: Object,
            default: {},
        },
        select: {
            type: Object,
            default: {
                productCategory: []
            },
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
            pcId: null,
            nameTh: '',
            nameEn: '',
            price: '',
        }),
        action: {
            route: {
                beforeRoute: 'admin.product.index'
            }
        },

    }),
    computed: {
        ruleRequired() {
            return [...this.$helpers.rules.required()]
        }
    },
    watch: {},
    emits: [
        'formSubmit',
    ],
    async mounted() {
        if (this.formType == 'edit' && this.data) {
            this.form = useForm(this.data)
        }
    },
    methods: {
        async submit() {
            const valid = await this.$refs.formRef.vFormRef.validate();
            if (!valid.valid) return;
            this.form.post(route('admin.product.store'), {
                // onFinish: () => { console.log('xxx') },
            });
        },
    },
};
</script>
<style lang="scss" scoped></style>