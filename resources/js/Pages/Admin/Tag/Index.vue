<template>
    <Dashboard title="Tags">
        <BreadcrumbDefault page-title="Tags" />

        <Table :items="tags.data" :links="tags.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    tags: Object,
});
</script>

<style lang="scss" scoped></style>
