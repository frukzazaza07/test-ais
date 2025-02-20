<template>
    <v-alert
        v-if="$page?.props?.success?.['alertMessage'] && typeof $page?.props?.success?.['alertMessage'] == 'string'"
        type="success" class="d-flex align-center mb-4 mb-md-6">
        {{ $page?.props?.success?.['alertMessage'] }}
    </v-alert>
</template>

<style lang="scss" scoped>
:deep() {
    .v-alert__prepend {
        align-self: normal;
    }
}
</style>