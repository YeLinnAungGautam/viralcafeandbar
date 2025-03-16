<template>
    <Dashboard title="Edit Page">
        <BreadcrumbDefault page-title="Edit Page" />
        <Form :editValue="page" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({

    page: Object,
});
</script>

<style scoped></style>
