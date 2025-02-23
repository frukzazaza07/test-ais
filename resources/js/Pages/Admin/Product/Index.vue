<template>
  <CardComponent>
    <template #content>
      <DataTableComponent
        v-model:currentPage="table.page"
        v-model:search="table.search"
        v-bind="{ dataTable, paginate, textField }"
        :action="dataTableTitleAction"
        @update:currentPage="handlePageChange"
        @update:submitSearch="handleSubmitSearch"
      >
        <template #actionAppendContainer>
          <div>
            <v-btn color="blue" @click="handleImportFile"> Import Products </v-btn>
            <input
              class="d-none"
              ref="importFileInputRef"
              @change="handleFileChange"
              type="file"
              accept=".csv"
            />
            <v-form ref="vFormRef" @submit.prevent="handleUploadFile">
              <v-file-input
                v-model="form.importFile"
                accept=".csv"
                :prepend-icon="null"
                :rules="[...($helpers.rules?.validateFileType() || [])]"
              >
              </v-file-input>
              <v-btn ref="btnSubmit" class="d-none" type="submit"></v-btn>
            </v-form>
          </div>
          <v-btn color="secondary" @click="handleExportFile"> Export Products </v-btn>
        </template>
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

  <DialogAlertComponent
    v-model="dialog.modelValue"
    :message="dialog"
    @onConfirm="handleDelete"
  ></DialogAlertComponent>

  <DialogAlertComponent
    v-model="qrcode.modelValue"
    :message="qrcode.message"
    :customConfigDialog="qrcode.customConfigDialog"
    :customConfigCard="qrcode.customConfigCard"
  >
    <template #cardText>
      <v-img :src="qrcode.base64"></v-img>
    </template>
    <template #cardAction>
      <v-btn
        color="primary"
        variant="outlined"
        @click="() => (qrcode.modelValue = !qrcode.modelValue)"
        >close</v-btn
      >
    </template>
  </DialogAlertComponent>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import DataTableComponent from '@/Components/DataTable.vue'
import CardComponent from '@/Components/Card.vue'
import DataTableActionComponent from '@/Components/DataTableAction.vue'
import DialogAlertComponent from '@/Components/DialogAlert.vue'
import { useForm } from '@inertiajs/vue3'
export default {
  name: 'AdminProductListPage',
  layout: AuthenticatedLayout,
  components: {
    DataTableComponent,
    CardComponent,
    DataTableActionComponent,
    DialogAlertComponent,
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
  setup() {
    return {
      formDefault: useForm({
        importFile: null,
      }),
    }
  },
  data() {
    return {
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
            width: '300px',
          },
        ],
        serverItems: [],
        loading: true,
        page: 1,
        pageValue: 1, // for ux on search
        search: '',
        searchValue: '', // ป้องกันอยู่หน้า 5 แต่ serach ข้อมูลได้แค่หน้า2
      },
      dataTableTitleAction: {
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
      qrcode: {
        modelValue: false,
        base64: '',
        customConfigDialog: {
          width: '340px',
        },
        customConfigCard: {
          width: '340px',
        },
        message: {
          title: ' ',
          body: ' ',
        },
      },
      form: this.formDefault,
    }
  },
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
  watch: {
    '$page.props': {
      handler(newValue, oldValue) {
        this.handleQrcode(newValue)
      },
      deep: true, // Watch nested changes
      immediate: true, // Run immediately
    },
  },
  async mounted() {
    this.table.loading = false
    this.table.page = this.data.meta.current_page
    this.table.pageValue = this.data.meta.current_page
    this.table.search = this.requestData.search
    this.table.searchValue = this.requestData.search

    this.handleQrcode(this.$page.props)
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
      this.$inertia.post(route('admin.product.generate-qrcode', { product: item.id }))
    },
    handleQrcode(newValue) {
      if (newValue?.success?.qrcodeBase64 && newValue?.success?.qrcodeBase64 != '') {
        this.qrcode.base64 = newValue?.success?.qrcodeBase64
        this.qrcode.modelValue = true
      }
    },
    handleImportFile() {
      this.$refs?.importFileInputRef?.click()
    },
    handleExportFile() {
      const link = document.createElement('a')
      link.href = route('admin.product.export', { ...this.queryParams })
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    },
    handleFileChange(event) {
      const files = event.target.files
      if (files && files.length > 0) {
        this.form.importFile = files[0]
        setTimeout(() => {
          this.$refs.btnSubmit?.$el.click()
        }, 200)
      } else {
        this.form.importFile = null
      }
    },
    async handleUploadFile() {
      const valid = await this.$refs.vFormRef?.validate()
      if (!valid.valid) return
      this.$inertia.post(route('admin.product.import', { ...this.queryParams }), {
        file: this.form.importFile,
      })
    },
  },
}
</script>
<style lang="scss" scoped>
:deep() {
  .v-file-input {
    .v-input__control {
      .v-field {
        display: none;
      }
    }

    .v-input__details {
      padding-inline: 0px;
    }
  }
}
</style>
