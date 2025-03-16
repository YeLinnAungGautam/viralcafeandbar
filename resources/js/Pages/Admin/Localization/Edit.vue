<template>
    <Dashboard title="Edit Localization">
        <BreadcrumbDefault page-title="Edit Localization" />
        <Form :editValue="localization" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    localization: Object,
});
</script>

<style scoped></style>
