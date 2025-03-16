<template>
    <Dashboard title="Roles">
        <BreadcrumbDefault page-title="Roles" />
        <Table :items="roles.data" :links="roles.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    roles: Array,
});
</script>

<style lang="scss" scoped></style>
