<template>
    <Dashboard title="Banners">
        <BreadcrumbDefault page-title="Banners" />
        <Table :items="banners.data" :links="banners.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    banners: Object,
});
</script>

<style lang="scss" scoped></style>
