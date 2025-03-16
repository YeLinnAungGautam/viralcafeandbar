<template>
    <Dashboard title="Edit Payment Account">
        <BreadcrumbDefault page-title="Edit Payment Account" />
        <Form :editValue="paymentaccount" :banks="banks" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    paymentaccount: Object,
    banks: Array,
});
</script>

<style scoped></style>
