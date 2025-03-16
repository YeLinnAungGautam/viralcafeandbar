<template>
    <Dashboard title="Edit Role">
        <BreadcrumbDefault page-title="Edit Role" />
        <Form :permissions="permissions" :editValue="role" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    permissions: Array,
    role: Object,
});
</script>

<style scoped></style>
