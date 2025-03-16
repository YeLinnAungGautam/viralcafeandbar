<template>
    <Dashboard title="Permissions">
        <BreadcrumbDefault page-title="Permissions" />
        <Table :items="permissions.data" :links="permissions.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    permissions: Object,
});
</script>

<style lang="scss" scoped></style>
