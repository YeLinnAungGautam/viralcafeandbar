<template>
    <Dashboard title="Edit Category">
        <BreadcrumbDefault page-title="Edit Category" />
        <div >
            <Form :editValue="category" />
        </div>
    </Dashboard>
</template>

<script setup>
    import Form from "./Partials/Form.vue";

    defineProps({
        category: Object,
    });
</script>

<style scoped></style>
