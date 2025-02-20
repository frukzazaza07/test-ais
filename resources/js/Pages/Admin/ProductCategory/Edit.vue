<template>
  <AdminProductCategoryFormComponent
    title="แก้ไขหมวดหมู่สินค้า"
    form-type="edit"
    :data="data?.data"
  >
  </AdminProductCategoryFormComponent>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AdminProductCategoryFormComponent from './Form.vue'
export default {
  name: 'AdminProductฎกระPage',
  layout: AuthenticatedLayout,
  components: {
    AdminProductCategoryFormComponent,
  },
  props: {
    data: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {}
  },
  computed: {},
  mounted() {},
  methods: {},
}
</script>
