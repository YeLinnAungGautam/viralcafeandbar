<template>
    <Dashboard title="Add Role">
        <BreadcrumbDefault page-title="Add Role" />
        <Form :editValue="permission" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    permission: Object,
});
</script>

<style scoped></style>
