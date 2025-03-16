<template>
    <Dashboard title="Messages">
        <BreadcrumbDefault page-title="Messages" />
    </Dashboard>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const { languages } = page.props;
</script>

<style lang="scss" scoped></style>
