<template>
    <Dashboard title="Languages">
        <BreadcrumbDefault page-title="Languages" />
        <Table :items="languages.data" :links="languages.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    languages: Object,
});
</script>

<style lang="scss" scoped></style>
