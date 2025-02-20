<template>
  <v-dialog :model-value="modelValue" v-bind="{ ...defaultConfigDialog }">
    <v-card
      :text="message.body || defaultConfigCard.body"
      :title="message.title || defaultConfigCard.title"
    >
      <template v-slot:actions>
        <v-spacer></v-spacer>

        <v-btn
          @click="($emit('update:modelValue', false), $emit('onConfirm', false))"
          color="warning"
          variant="flat"
        >
          {{ message.disagree || defaultConfigCard.disagree }}
        </v-btn>

        <v-btn
          @click="($emit('update:modelValue', false), $emit('onConfirm', true))"
          color="primary"
          variant="flat"
        >
          {{ message.agree || defaultConfigCard.agree }}
        </v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'DialogComponent',
  components: {},
  props: {
    message: {
      type: Object,
      default: {
        title: 'Confirm ลบข้อมูล',
        body: '',
        disagree: '',
        agree: '',
      },
    },
    modelValue: {
      type: Boolean,
      default: false,
    },
  },
  data: () => ({
    defaultConfigDialog: {
      maxWidth: '600',
      persistent: true,
    },
    defaultConfigCard: {
      title: 'Confirm ลบข้อมูล',
      body: '',
      disagree: 'Cancel',
      agree: 'Confirm',
    },
  }),
  computed: {},
  watch: {},
  emits: ['update:modelValue', 'onConfirm'],
  async mounted() {},
  methods: {},
}
</script>
<style lang="scss" scoped></style>
