<template>
    <Dashboard title="Add Category">
        <BreadcrumbDefault page-title="Add Category" />
        <div>
            <Form />
        </div>
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    permissions: Array,
});
</script>

<style scoped></style>
