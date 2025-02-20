<template>
  <AdminProductCategoryFormComponent
    title="เพิ่มหมวดหมู่สินค้า"
  ></AdminProductCategoryFormComponent>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AdminProductCategoryFormComponent from './Form.vue'
export default {
  name: 'AdminProductCategoryCreatePage',
  layout: AuthenticatedLayout,
  components: {
    AdminProductCategoryFormComponent,
  },
  props: {},
  data() {
    return {}
  },
  computed: {},
  mounted() {},
  methods: {},
}
</script>
