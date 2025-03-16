<template>
    <Dashboard title="Edit User">
        <BreadcrumbDefault page-title="Edit User" />
        <Form :editValue="user" :roles="roles" />

    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    user: Array,
    roles: Array
});
</script>

<style scoped></style>
