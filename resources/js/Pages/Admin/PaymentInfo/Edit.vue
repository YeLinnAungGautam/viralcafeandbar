<template>
    <Dashboard title="Edit Payment Bank">
        <BreadcrumbDefault page-title="Edit Payment Bank" />
        <Form :editValue="paymentinfo" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    paymentinfo: Object,
});
</script>

<style scoped></style>
