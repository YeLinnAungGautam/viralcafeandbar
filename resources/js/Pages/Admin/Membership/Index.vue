<template>
    <Dashboard title="Memberships">
        <BreadcrumbDefault page-title="Memberships" />
        <Table :items="memberships.data" :links="memberships.links" />
    </Dashboard>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Table from "./Partials/Table.vue";

defineProps({
    memberships: Object,
});
</script>
