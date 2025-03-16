<template>
    <Dashboard title="Add Role">
        <BreadcrumbDefault page-title="Add Role" />
        <Form :permissions="permissions" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    permissions: Array,
});
</script>

<style scoped></style>
