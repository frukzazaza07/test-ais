<template>
  <v-form ref="vFormRef" @submit.prevent="$emit('formSubmit')">
    <v-row justify="space-between" class="mb-4">
      <v-col cols="auto" class="align-center">
        <h5 class="text-h5 pb-0">{{ $props.title }}</h5>
      </v-col>
      <v-col cols="auto" class="align-center">
        <Link
          v-if="action?.route?.beforeRoute"
          class="text-white"
          :href="route(action.route.beforeRoute)"
        >
          <v-btn color="info"> Back </v-btn>
        </Link>
      </v-col>
    </v-row>
    <slot name="input"></slot>
    <v-row justify="end">
      <v-col cols="12" md="auto" color="primary">
        <v-btn type="submit" color="primary">Submit</v-btn>
      </v-col>
    </v-row>
  </v-form>
</template>

<script>
import Errors from './Errors.vue'

export default {
  name: 'FormComponent',
  components: {
    Errors,
  },
  props: {
    title: {
      type: String,
      default: '',
    },
    action: {
      type: Object,
      default: {},
    },
  },
  data: () => {
    return {
      vFormRef: null,
    }
  },
  computed: {},
  watch: {},
  emits: ['formSubmit'],
  async mounted() {
    this.vFormRef = this.$refs.vFormRef
  },
  methods: {},
}
</script>
<style lang="scss" scoped></style>
