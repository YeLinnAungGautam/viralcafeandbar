<template>
    <Dashboard title="Show Category">
        <BreadcrumbDefault page-title="Category Details" />
        <DetailButton
            @changes="(sw) => (switchLanguage = sw)"
            :label="'categories'"
            :data="category"
        />

        <!-- <div class="col-span-1">
            <div
                class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <h4 class="text-base mb-3 font-bold text-black">Images</h4>

                <div class="grid grid-cols-3 gap-3">
                    <Image
                        :src="category.upload.url"
                        :alt="category.upload.file_name"
                        preview
                    />
                </div>
            </div> -->
        <!-- </div> -->

        <div
            class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
        >
            <h4 class="text-base mb-3 font-bold text-black">
                Category Details
            </h4>
            <table class="w-full text-left">
                <tbody class="text-gray-900">
                    <tr>
                        <th scope="col" class="py-3 px-2">Name</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2">
                            {{
                                category.translates[switchLanguage]?.name ||
                                "---"
                            }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3 px-2">Image</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2">
                            <Image
                                :src="category.upload.url"
                                :alt="category.upload.file_name"
                                width="75"
                                preview
                                v-show="category.upload.url"
                            />
                            <span v-show="!category.upload.url">---</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Link :href="route(`admin.categories.index`)">
            <Button label="Back To List" size="small" severity="contrast" />
        </Link>
    </Dashboard>
</template>

<script setup>
import Image from "primevue/image";
import { ref } from "vue";
import DetailButton from "@/Components/Layouts/DetailButton.vue";
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
const switchLanguage = ref("en");

defineProps({
    category: Object,
});
</script>

<style lang="scss" scoped></style>
