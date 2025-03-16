<template>
    <Dashboard title="Customers">
        <BreadcrumbDefault page-title="Customers" />
        <Table :items="customers.data" :links="customers.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    customers: Object,
});
</script>

<style lang="scss" scoped></style>
