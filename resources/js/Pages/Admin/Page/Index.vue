<template>
    <Dashboard title="Pages">
        <BreadcrumbDefault page-title="Pages" />
        <Table :items="pages.data" :links="pages.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    pages: Array,
});
</script>
