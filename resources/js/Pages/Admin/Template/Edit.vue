<template>
    <Dashboard title="Edit Template">
        <BreadcrumbDefault page-title="Edit Template" />
        <Form :editValue="template" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    template: Object,
});
</script>

<style scoped></style>
