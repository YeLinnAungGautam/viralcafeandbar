<template>
    <Dashboard title="Edit Payment Method">
        <BreadcrumbDefault page-title="Edit Payment Method" />
        <Form :editValue="payment" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    payment: Object,
});
</script>

<style scoped></style>
