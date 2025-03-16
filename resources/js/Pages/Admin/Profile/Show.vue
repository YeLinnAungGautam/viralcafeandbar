<template>
    <Dashboard title="Edit Profile">
        <BreadcrumbDefault page-title="Edit Profile" />
        <Form :profile="profile" />
    </Dashboard>
</template>

<script setup>
import Form from "./Partials/Form.vue";

defineProps({
    profile: Object,
});
</script>

<style scoped></style>
