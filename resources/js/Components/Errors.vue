<template>
    <v-alert v-if="$page?.props?.errors?.alertMessage && Object.keys($page?.props?.errors?.alertMessage).length"
        type="warning" class="d-flex align-center mb-4 mb-md-6">
        <!-- <v-list lines="one"> -->
        <template v-if="typeof $page.props.errors?.alertMessage == 'object'"
            v-for="(value, index) of Object.entries($page.props.errors?.alertMessage)" :key="index">
            <v-list-item v-for="(error, errorIndex) of value[1]" :key="errorIndex">
                {{ error }}
            </v-list-item>
        </template>
        <template v-else>
            {{ $page.props.errors?.alertMessage }}
        </template>
    </v-alert>
</template>

<style lang="scss" scoped>
:deep() {
    .v-alert__prepend {
        align-self: normal;
    }
}
</style>