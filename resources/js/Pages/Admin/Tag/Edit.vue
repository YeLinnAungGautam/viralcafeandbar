<template>
    <Dashboard title="Edit Tag">
        <BreadcrumbDefault page-title="Edit Tag" />
        <Form :editValue="tag" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    tag: Object,
});
</script>

<style scoped></style>
