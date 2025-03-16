<template>
    <Dashboard title="Activity Logs">
        <BreadcrumbDefault page-title="Activity Logs" />
        <Table :items="activityLogs.data" :links="activityLogs.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    activityLogs: Object,
});
</script>

<style lang="scss" scoped></style>
