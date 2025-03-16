<template>
    <Dashboard title="Show Page">
        <BreadcrumbDefault page-title="Page Details" />
        <DetailButton
            @changes="(sw) => (switchLanguage = sw)"
            :label="'pages'"
            :data="page"
        />

        <div
            class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
        >
            <h4 class="text-base mb-3 font-bold text-black">Page Details</h4>

            <table class="w-full text-left">
                <tbody class="text-gray-900">
                    <tr>
                        <th scope="col" class="py-3 px-2 w-50">Title</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2 capitalize">
                            {{
                                page.translates[switchLanguage]?.title || "---"
                            }}
                        </td>
                    </tr>

                    <tr>
                        <th scope="col" class="py-3 px-2">Page Text</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2">
                            <div
                                v-html="
                                    page.translates[switchLanguage]
                                        ?.page_text || '---'
                                "
                            ></div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3 px-2">Excerpt</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2">
                            <div
                                v-html="
                                    page.translates[switchLanguage]?.excerpt ||
                                    '---'
                                "
                            ></div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3 px-2">Status</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2">
                            <Tag
                                class="capitalize"
                                :value="page.status"
                                :severity="getSeverity(page.status)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Link :href="route(`admin.pages.index`)">
            <Button label="Back To List" size="small" severity="contrast" />
        </Link>
    </Dashboard>
</template>

<script setup>
import Tag from "primevue/tag";
import Button from "primevue/button";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import DetailButton from "@/Components/Layouts/DetailButton.vue";
import { useServerity } from "@/composables/useServerity";

const { getSeverity } = useServerity();
defineProps({
    page: Object,
});
const switchLanguage = ref("en");
</script>

<style lang="scss" scoped></style>
