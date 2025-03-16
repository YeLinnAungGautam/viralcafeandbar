<template>
    <Dashboard title="Add Notification">
        <BreadcrumbDefault page-title="Add Notification" />
        <Form :tags="tags" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";
defineProps({
    tags: Object,
});
</script>

<style scoped></style>
