<template>
    <Dashboard title="Payments Methods">
        <BreadcrumbDefault page-title="Payments Methods" />

        <Table :items="payments.data" :links="payments.links" />
    </Dashboard>
</template>

<script setup>
import Table from "./Partials/Table.vue";

defineProps({
    payments: Object,
});
</script>
