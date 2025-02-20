<template>
  <AdminProductFormComponent title="เพิ่มสินค้า" :select="select"></AdminProductFormComponent>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AdminProductFormComponent from './Form.vue'
export default {
  name: 'AdminProductCreatePage',
  layout: AuthenticatedLayout,
  components: {
    AdminProductFormComponent,
  },
  props: {
    select: {
      type: Object,
      default: {
        productCategory: [],
      },
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
