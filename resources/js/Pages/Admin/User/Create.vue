<template>
    <Dashboard title="Add User">
        <BreadcrumbDefault page-title="Add User" />
        <Form :roles="roles" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";
defineProps({
    roles: Array
})
</script>

<style scoped></style>
