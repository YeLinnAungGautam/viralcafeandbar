<template>
    <Dashboard title="Show Banner">
        <BreadcrumbDefault page-title="Banner Details" />
        <DetailButton
            @changes="(sw) => (switchLanguage = sw)"
            :label="'banners'"
            :data="banner"
        />

        <!-- <div class="col-span-1">
            <div
                class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <h4 class="text-base mb-3 font-bold text-black">Images</h4>

                <div class="grid grid-cols-3 gap-3">
                    <Image
                        :src="banner.upload.url"
                        :alt="banner.upload.file_name"
                        preview
                    />
                </div>
            </div>
        </div> -->
        <div class="col-span-1">
            <div
                class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <h4 class="text-base mb-3 font-bold text-black">
                    Banner Details
                </h4>
                <table class="w-full text-left">
                    <tbody class="text-gray-900">
                        <tr>
                            <th scope="col" class="py-3 px-2">Title</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2 capitalize">
                                {{
                                    banner.translates[switchLanguage]?.title ||
                                    "---"
                                }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-3 px-2">Description</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                <div
                                    v-html="
                                        banner.translates[switchLanguage]
                                            ?.description || '---'
                                    "
                                ></div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-3 px-2">Product</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                {{ banner.product?.name || "---" }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-3 px-2">Category</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                {{ banner.category?.name || "---" }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-3 px-2">Status</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                <Tag
                                    class="capitalize"
                                    :value="banner.status"
                                    :severity="getSeverity(banner.status)"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-3 px-2">Image</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                <Image
                                    :src="banner.upload.url"
                                    :alt="banner.upload.file_name"
                                    width="75"
                                    preview
                                    v-show="banner.upload.url"
                                />
                                <span v-show="!banner.upload.url">---</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Link :href="route(`admin.banners.index`)">
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
import Tag from "primevue/tag";
import { useServerity } from "@/composables/useServerity";

const { getSeverity } = useServerity();
defineProps({
    banner: Object,
});

const switchLanguage = ref("en");
</script>

<style lang="scss" scoped></style>
