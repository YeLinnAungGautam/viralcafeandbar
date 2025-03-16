<template>
    <Dashboard title="Orders">
        <BreadcrumbDefault page-title="Orders" />
        <Table :items="orders.data" :links="orders.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    orders: Object,
});
</script>
