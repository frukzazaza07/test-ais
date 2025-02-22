<template>
  <div class="d-flex justify-center ga-3">
    <Link @click="$emit('editClick')" :href="editHref ? editHref : null">
      <v-btn color="primary" size="small"> Edit </v-btn>
    </Link>
    <v-btn color="error" size="small" @click="$emit('deleteClick')"> Delete </v-btn>
    <slot name="append"></slot>
  </div>
</template>

<script>
export default {
  name: 'DataTableActionComponent',
  components: {},
  props: {
    editHref: {
      type: String,
      default: null,
    },
  },
  data: () => ({}),
  computed: {},
  watch: {},
  emits: ['editClick', 'deleteClick'],
  async mounted() {},
  methods: {},
}
</script>
<style lang="scss" scoped></style>
