<template>
    <Dashboard title="Payment Banks">
        <BreadcrumbDefault page-title="Payment Banks" />
        <Table :items="paymentinfos.data" :links="paymentinfos.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    paymentinfos: Object,
});
</script>
