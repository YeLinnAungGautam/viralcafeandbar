<template>
    <Dashboard title="Products">
        <BreadcrumbDefault page-title="Products" />
        <Table :items="products.data" :links="products.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    products: Object,
});
</script>

<style lang="scss" scoped></style>
