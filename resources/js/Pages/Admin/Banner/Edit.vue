<template>
    <Dashboard title="Edit Banner">
        <BreadcrumbDefault page-title="Edit Banner" />
        <Form :editValue="banner" :products="products" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    banner: Object,
    products: Array,
});
</script>

<style scoped></style>
