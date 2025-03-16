<template>
    <Dashboard title="Add Product">
        <BreadcrumbDefault page-title="Add Product" />
        <Form
            :attributeOptions="attributeOptions"
            :attributes="attributes"
            :categories="categories"
        />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    attributes: Array,
    categories: Array,
    attributeOptions: Array,
});
</script>

<style scoped></style>
