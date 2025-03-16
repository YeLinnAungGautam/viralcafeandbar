<template>
    <Dashboard title="Edit Membership">
        <BreadcrumbDefault page-title="Edit Membership" />
        <Form :editValue="membership" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({

    membership: Object,
});
</script>

<style scoped></style>
