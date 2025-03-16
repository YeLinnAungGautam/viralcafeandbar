<template>
    <Dashboard title="Edit Tag Line">
        <BreadcrumbDefault page-title="Edit Tag Line" />
        <Form :editValue="tagline" :tags="tags" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    tagline: Object,
    tags: Array,
});
</script>

<style scoped></style>
