<template>
    <Dashboard title="Templates">
        <BreadcrumbDefault page-title="Templates" />

        <Table :items="templates.data" :links="templates.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    templates: Object,
});
</script>

<style lang="scss" scoped></style>
