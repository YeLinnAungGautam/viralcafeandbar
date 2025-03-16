<template>
    <Dashboard title="Edit Product">
        <BreadcrumbDefault page-title="Edit Product" />
        <Form
            :editValue="product"
            :attributes="attributes"
            :categories="categories"
        />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    product: Object,
    attributes: Array,
    categories: Array,
});
</script>

<style scoped></style>
