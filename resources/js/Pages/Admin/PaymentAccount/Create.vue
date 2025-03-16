<template>
    <Dashboard title="Add Payment Account">
        <BreadcrumbDefault page-title="Add Payment Account" />
        <Form :banks="banks" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";
defineProps({
    banks: Object,
});
</script>

<style scoped></style>
