<template>
  <CardComponent>
    <template #content>
      <DataTableComponent
        v-model:currentPage="table.page"
        v-model:search="table.search"
        v-bind="{ dataTable, paginate, textField }"
        :action="action"
        @update:currentPage="handlePageChange"
        @update:submitSearch="handleSubmitSearch"
      >
        <template #item.id="{ index }">
          {{ $helpers.setSequence(index, data.meta) }}
        </template>
        <template #item.price="{ item }">
          <div class="text-right">{{ $helpers.currencyTh(item.price || 0) }}</div>
        </template>
        <template #item.action="{ item }">
          <DataTableActionComponent
            :editHref="
              route('admin.product.edit', {
                product: item.id,
              })
            "
            @deleteClick="handleDataDelete(item)"
          >
            <template #append>
              <v-btn color="success" @click="handleGenerateQrcode(item)"> Qrcode </v-btn>
            </template>
          </DataTableActionComponent>
        </template>
      </DataTableComponent>
    </template>
  </CardComponent>

  <DialogComponent
    v-model="dialog.modelValue"
    :message="dialog"
    @onConfirm="handleDelete"
  ></DialogComponent>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import DataTableComponent from '@/Components/DataTable.vue'
import CardComponent from '@/Components/Card.vue'
import DataTableActionComponent from '@/Components/DataTableAction.vue'
import DialogComponent from '@/Components/Dialog.vue'
export default {
  name: 'AdminProductListPage',
  layout: AuthenticatedLayout,
  components: {
    DataTableComponent,
    CardComponent,
    DataTableActionComponent,
    DialogComponent,
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
          align: 'center',
          key: 'id',
        },
        {
          title: 'หมวดหมู่ไทย',
          sortable: false,
          align: 'center',
          key: 'category.nameTh',
        },
        {
          title: 'หมวดหมู่อังกฤษ',
          sortable: false,
          align: 'center',
          key: 'category.nameEn',
        },
        {
          title: 'ชื่อไทย',
          sortable: false,
          align: 'center',
          key: 'nameTh',
        },
        {
          title: 'ชื่ออังกฤษ',
          sortable: false,
          align: 'center',
          key: 'nameEn',
        },
        {
          title: 'Serial Number',
          sortable: false,
          align: 'center',
          key: 'serialNumber',
        },
        {
          title: 'ราคา',
          sortable: false,
          align: 'center',
          key: 'price',
        },
        {
          title: 'Action',
          sortable: false,
          align: 'center',
          key: 'action',
          width: '200px',
        },
      ],
      serverItems: [],
      loading: true,
      page: 1,
      pageValue: 1, // for ux on search
      search: '',
      searchValue: '', // ป้องกันอยู่หน้า 5 แต่ serach ข้อมูลได้แค่หน้า2
    },
    action: {
      create: {
        route: {
          name: 'admin.product.create',
          label: 'เพิ่มสินค้า',
        },
      },
    },
    dataDelete: null,
    dialog: {
      modelValue: false,
      body: 'ต้องการลบข้อมูลของ :atribute?',
      agree: 'Confirm',
      disagree: 'Cancel',
    },
  }),
  computed: {
    dataTable() {
      return {
        headers: this.table.headers,
        items: this.data.data,
        'items-per-page': this.data.meta.per_page,
        loading: this.table.loading,
      }
    },
    paginate() {
      return { length: this.data.meta.last_page }
    },
    textField() {
      return { rules: [...this.$helpers.rules.validateUppercaseString()] }
    },
    queryParams() {
      return { search: this.table.searchValue || null, page: this.table.pageValue || 1 }
    },
  },
  watch: {},
  async mounted() {
    this.table.loading = false
    this.table.page = this.data.meta.current_page
    this.table.pageValue = this.data.meta.current_page
    this.table.search = this.requestData.search
    this.table.searchValue = this.requestData.search
  },
  methods: {
    handleGetData() {
      this.table.loading = true
      this.$inertia.get(route(route().current(), { ...this.queryParams }))
    },
    handlePageChange(page) {
      this.table.pageValue = page
      this.handleGetData()
    },
    handleSubmitSearch() {
      this.table.searchValue = this.table.search
      this.table.pageValue = 1
      this.handleGetData()
    },
    handleDataDelete(item) {
      this.dialog.body = this.dialog.body.replace(
        ':atribute',
        `${item.nameTh} ${item.serialNumber}`,
      )
      this.dialog.modelValue = true
      this.dataDelete = item
    },
    handleDelete(confirm) {
      if (!confirm) {
        this.dataDelete = null
        return
      }
      this.$inertia.delete(
        route('admin.product.destroy', { ...this.queryParams, product: this.dataDelete.id }),
      )
    },
    async handleGenerateQrcode(item) {
      const response = await axios({
        url: route('admin.product.generate-qrcode', { product: item.id }),
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: localStorage.getItem('api-token'),
        },
        data: {},
        method: 'POST',
      })
      console.log(response.data.data.data.base64)
    },
  },
}
</script>
<style lang="scss" scoped></style>
