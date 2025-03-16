<template>
    <Dashboard title="Payment Accounts">
        <BreadcrumbDefault page-title="Payment Accounts" />
        <Table :items="paymentaccounts.data" :links="paymentaccounts.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    paymentaccounts: Object,
});
</script>
