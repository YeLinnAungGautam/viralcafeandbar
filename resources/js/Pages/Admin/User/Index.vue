<template>
    <Dashboard title="Users">
        <BreadcrumbDefault page-title="Users" />
        <Table :items="users.data" :links="users.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    users: Array,
});
</script>

<style lang="scss" scoped></style>
