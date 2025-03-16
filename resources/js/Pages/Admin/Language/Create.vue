<template>
    <Dashboard title="Add Language">
        <BreadcrumbDefault page-title="Add Language" />
        <Form />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    attributes: Array,
    categories: Array,
    attributeOptions: Array,
});
</script>

<style scoped></style>
