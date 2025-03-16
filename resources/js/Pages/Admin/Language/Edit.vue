<template>
    <Dashboard title="Edit Language">
        <BreadcrumbDefault page-title="Edit Language" />
        <Form :editValue="language" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    language: Object,
});
</script>

<style scoped></style>
