<template>
    <Dashboard title="Edit Customer">
        <BreadcrumbDefault page-title="Edit Customer" />
        <Form  :editValue="customer" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({

    customer: Object,
});
</script>

<style scoped></style>
