<template>
    <Dashboard title="Show Template">
        <BreadcrumbDefault page-title="Template Details" />
        <DetailButton :label="'templates'" :data="template" />

        <div
            class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
        >
            <h4 class="text-base mb-3 font-bold text-black">
                Template Details
            </h4>
            <table class="w-full text-left">
                <tbody class="text-gray-900">
                    <tr>
                        <th scope="col" class="py-3 px-2">Title</th>

                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2 capitalize">
                            {{ template?.title || "---" }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3 px-2">Content</th>

                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2">
                            <div v-html="template?.content || '---'"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Link :href="route(`admin.templates.index`)">
            <Button label="Back To List" size="small" severity="contrast" />
        </Link>
    </Dashboard>
</template>

<script setup>
import DetailButton from "@/Components/Layouts/DetailButton.vue";
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
import Tag from "primevue/tag";
import { ref } from "vue";
import { useServerity } from "@/composables/useServerity";

const { getSeverity } = useServerity();

defineProps({
    template: Object,
});
</script>

<style lang="scss" scoped></style>
