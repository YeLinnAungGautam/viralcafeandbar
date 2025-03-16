<template>
    <Dashboard title="Edit Notification">
        <BreadcrumbDefault page-title="Edit Notification" />
        <Form :editValue="promotion" :tags="tags" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    promotion: Object,
    tags: Array,
});
</script>

<style scoped></style>
