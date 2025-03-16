<template>
    <Dashboard title="Show Tag Line">
        <BreadcrumbDefault page-title="Tag Line Details" />
        <DetailButton
            @changes="(sw) => (switchLanguage = sw)"
            :label="'tag-lines'"
            :data="tagline"
        >
        </DetailButton>
        <div class="col-span-1">
            <div
                class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <h4 class="text-base mb-3 font-bold text-black">
                    Tag Line Details
                </h4>
                <table class="w-full text-left table">
                    <tbody class="text-gray-900">
                        <tr>
                            <th scope="col" class="py-3 px-2 w-50">Name</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2 capitalize">
                                {{
                                    tagline.translates[switchLanguage]?.name ||
                                    "---"
                                }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-3 px-2">Status</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                <Tag
                                    class="capitalize"
                                    :value="tagline.status"
                                    :severity="getSeverity(tagline.status)"
                                />
                            </td>
                        </tr>
                        <!-- <tr>
                            <th scope="col" class="py-3 px-2">Description</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                <div
                                    v-html="
                                        tagline.translates[switchLanguage]
                                            ?.description || '---'
                                    "
                                    class=""
                                ></div>
                            </td>
                        </tr> -->
                        <tr>
                            <th scope="col" class="py-3 px-2">Product</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                {{ tagline.product?.name ?? "---" }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-3 px-2">Category</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                {{ tagline.category?.name ?? "---" }}
                            </td>
                        </tr>
                        <!-- <tr>
                            <th scope="col" class="py-3 px-2">
                                Notify Short Description
                            </th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                {{ tagline.short_description }}
                            </td>
                        </tr> -->
                        <!-- <tr>
                            <th scope="col" class="py-3 px-2">Tags</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                <Tag
                                    v-for="tag in tagline.tags"
                                    :key="tag.id"
                                    :value="tag.name"
                                    severity="warn"
                                    class="me-1 capitalize"
                                />
                            </td>
                        </tr> -->
                        <tr>
                            <th scope="col" class="py-3 px-2">Images</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-3 px-2">
                                <div
                                    v-show="!isEmpty(tagline.upload)"
                                    class="grid grid-cols-10 gap-2"
                                >
                                    <template
                                        v-for="img in tagline.upload"
                                        :key="img.id"
                                    >
                                        <Image
                                            :src="img.url"
                                            :alt="img.file_name"
                                            width="75"
                                            preview
                                        />
                                    </template>
                                </div>
                                <span v-show="isEmpty(tagline.upload)">
                                    ---
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <Dialog
            v-model:visible="visible"
            modal
            header="Notification Testing"
            :style="{ width: '500px' }"
            id="form-header"
        >
            <div class="w-full mb-5">
                <CustomerAutocomplete
                    url="/api/v1/customer-search"
                    v-model="customer"
                    multiple
                    fluid
                />
            </div>
            <Button label="Push" size="small" severity="contrast" />
        </Dialog> -->
        <Link :href="route(`admin.tag-lines.index`)">
            <Button label="Back To List" size="small" severity="contrast" />
        </Link>
    </Dashboard>
</template>

<script setup>
import Tag from "primevue/tag";
import Image from "primevue/image";
import { ref } from "vue";
import DetailButton from "@/Components/Layouts/DetailButton.vue";
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
import { useServerity } from "@/composables/useServerity";
import { isEmpty } from "lodash";
import Dialog from "primevue/dialog";
import CustomerAutocomplete from "@/Components/Autocomplete/Item.vue";

import { usePage } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
const page = usePage();
var { languages, api_token } = page.props;

const { getSeverity } = useServerity();

const customer = ref();

defineProps({
    tagline: Object,
});

const toast = useToast();

// const handlePushNotification = async (promo) => {
//     await axios(route("promotions.send-notify"), {
//         headers: {
//             "API-TOKEN": api_token,
//         },
//         method: "POST",
//         data: {
//             title: promo.name,
//             body: promo.short_description,
//             translates: promo.translates,
//             id: promo.id,
//             action_id: promo.id,
//             type: "promotions",
//         },
//     });

//     toast.add({
//         severity: "success",
//         summary: "Success",
//         detail: "Notification push successful.",
//         life: 3000,
//     });
// };

const visible = ref(false);

const switchLanguage = ref("en");
</script>

<style lang="scss" scoped></style>
