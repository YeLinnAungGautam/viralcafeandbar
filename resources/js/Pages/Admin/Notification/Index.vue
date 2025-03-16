<template>
    <Dashboard title="Notifications">
        <BreadcrumbDefault page-title="Notifications" />
        <Table :items="promotions.data" :links="promotions.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    promotions: Object,
    tags: Object,
});
</script>
