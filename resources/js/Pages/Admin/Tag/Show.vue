<template>
    <Dashboard title="Show Tag">
        <BreadcrumbDefault page-title="Tag Details" />
        <DetailButton
            @changes="(sw) => (switchLanguage = sw)"
            :label="'tags'"
            :data="tag"
        />

        <div
            class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
        >
            <h4 class="text-base mb-3 font-bold text-black">Tag Details</h4>
            <table class="w-full text-left">
                <tbody class="text-gray-900">
                    <tr>
                        <th scope="col" class="py-3 px-2">Name</th>

                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2 capitalize">
                            {{ tag.translates[switchLanguage]?.name || "---" }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3 px-2">Status</th>
                        <td scope="col" class="py-2 w-10">-</td>

                        <td scope="col" class="py-3 px-2">
                            <Tag
                                class="capitalize"
                                :value="tag.status"
                                :severity="getSeverity(tag.status)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Link :href="route(`admin.tags.index`)">
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
    tag: Object,
});
const switchLanguage = ref("en");
</script>

<style lang="scss" scoped></style>
