<template>
    <Dashboard title="Tag Lines">
        <BreadcrumbDefault page-title="Tag Lines" />
        <Table :items="taglines.data" :links="taglines.links" />
    </Dashboard>
</template>

<script setup>
import Table from "./Partials/Table.vue";

defineProps({
    taglines: Object,
    tags: Object,
});
</script>
