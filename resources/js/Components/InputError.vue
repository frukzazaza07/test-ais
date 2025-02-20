<script setup>
defineProps({
  message: {
    type: String,
  },
})
</script>
<style scoped src="@css/tailwind.css"></style>
<template>
  <div v-show="message">
    <p class="text-sm text-red-600">
      {{ message }}
    </p>
  </div>
</template>
