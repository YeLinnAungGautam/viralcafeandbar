<template>
    <Dashboard title="Categories">
        <BreadcrumbDefault page-title="Categories" />
        <Table :items="categories.data" :links="categories.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    categories: Object,
});
</script>

<style lang="scss" scoped></style>
