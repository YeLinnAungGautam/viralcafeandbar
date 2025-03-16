<template>
    <Dashboard title="Localizations">
        <BreadcrumbDefault page-title="Localizations" />
        <Table :items="localizations.data" :links="localizations.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    localizations: Object,
});
</script>

<style lang="scss" scoped></style>
