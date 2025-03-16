<template>
    <Dashboard title="Show Product">
        <BreadcrumbDefault page-title="Product Details" />
        <div class="mb-3 flex gap-2 items-center justify-end">
            <Link :href="route('admin.products.index')">
                <Button
                    label="View all"
                    icon="fa-solid fa-list"
                    severity="contrast"
                    size="small"
                />
            </Link>
            <Link :href="route('admin.products.edit', product.id)">
                <Button
                    label="Edit"
                    icon="fa-solid fa-edit"
                    severity="contrast"
                    size="small"
                />
            </Link>
            <PSelect
                style="width: 130px"
                v-model="switchLanguage"
                :options="languages"
                optionValue="code"
                optionLabel="name"
            />
        </div>
        <!-- <div class="grid grid-cols-2 gap-x-5"> -->
        <div class="col-span-1">
            <div
                class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <h4 class="text-base mb-3 font-bold text-black">
                    Product Details
                </h4>
                <table class="w-full text-sm text-left">
                    <tbody class="text-gray-900">
                        <tr>
                            <th scope="col" class="py-2 w-50">Product Name</th>
                            <td scope="col" class="py-2 w-10">-</td>

                            <td scope="col" class="py-2 px-2">
                                {{
                                    product.translates[switchLanguage]?.name ||
                                    "---"
                                }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-2">Description</th>
                            <td scope="col" class="py-2 w-10">-</td>

                            <td scope="col" class="py-2 px-2">
                                <div
                                    v-html="
                                        product.translates[switchLanguage]
                                            ?.description || '---'
                                    "
                                ></div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-2">Type</th>
                            <td scope="col" class="py-2 w-10">-</td>

                            <td scope="col" class="py-2 px-2">
                                <Tag
                                    class="capitalize"
                                    :value="product.type"
                                    severity="info"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-2">Status</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-2 px-2">
                                <Tag
                                    class="capitalize"
                                    :value="product.active"
                                    :severity="getSeverity(product.active)"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-2">Categories</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td
                                scope="col"
                                class="py-2 px-2 flex items-center gap-1"
                            >
                                <template
                                    v-for="category in product.categories"
                                    :key="category.id"
                                >
                                    <div>
                                        <!-- {{
                                            category.translates[switchLanguage]
                                                ?.name || "---"
                                        }} -->
                                        <Tag
                                            class="capitalize"
                                            :value="
                                                category.translates[
                                                    switchLanguage
                                                ]?.name || '---'
                                            "
                                            severity="warn"
                                        />
                                    </div>
                                </template>
                            </td>
                        </tr>
                        <tr
                            v-if="
                                product.translates[switchLanguage]?.additionals
                                    ?.length > 0
                            "
                        >
                            <th scope="col" class="py-2">Additionals</th>
                            <td scope="col" class="py-2 w-10">-</td>
                            <td scope="col" class="py-2">
                                <template
                                    v-for="additional in product.translates[
                                        switchLanguage
                                    ].additionals"
                                    :key="additional.id"
                                >
                                    <div class="px-2 py-2">
                                        {{ additional?.text || "---" }}
                                    </div>
                                </template>
                            </td>
                        </tr>
                        <tr v-if="product.type != 'simple'">
                            <th scope="col" class="py-2">Vairation</th>
                            <td scope="col" class="py-2 w-10">-</td>

                            <td scope="col" class="py-2"></td>
                        </tr>
                        <tr>
                            <th scope="col" class="py-3">Images</th>
                            <td scope="col" class="py-2 w-10">-</td>

                            <td scope="col" class="py-3">
                                <div
                                    v-show="!isEmpty(product.upload)"
                                    class="grid grid-cols-10 gap-2"
                                >
                                    <template
                                        v-for="img in product.upload"
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
                                <span
                                    v-show="isEmpty(product.upload)"
                                    class="px-3"
                                    >---</span
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- </div> -->
            <!-- <div class="col-span-1">
                <div
                    class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
                >
                    <h4 class="text-base mb-3 font-bold text-black">Images</h4>

                    <div class="grid grid-cols-3 gap-3">
                        <template v-for="img in product.upload" :key="img.id">
                            <Image
                                :src="img.url"
                                :alt="img.file_name"
                                preview
                            />
                        </template>
                    </div>
                </div>
            </div> -->
            <div class="col-span-full">
                <div
                    class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
                >
                    <h4 class="text-base mb-3 font-bold text-black">Pricing</h4>
                    <DataTable
                        size="small"
                        :value="product.skus"
                        tableStyle="min-width: 50rem"
                    >
                        <Column header="Sku">
                            <template #body="{ data }">
                                {{ data.code ?? "---" }}
                            </template>
                        </Column>
                        <Column header="Regular Price">
                            <template #body="{ data }">
                                <span
                                    v-html="currencyFormat(data.original_price)"
                                ></span>
                                <!-- {{ currencyFormat(data.original_price) }} -->
                            </template>
                        </Column>
                        <Column header="Sale Price">
                            <template #body="{ data }">
                                <span
                                    v-html="
                                        currencyFormat(data.sale_price) || '---'
                                    "
                                ></span>
                                <!-- {{ currencyFormat(data.sale_price) }} -->
                            </template>
                        </Column>
                        <Column field="stock" header="Stock">
                            <template #body="{ data }">
                                {{ data.stock ?? "Unlimit" }}
                            </template>
                        </Column>
                        <Column header="Stock Status">
                            <template #body="{ data }">
                                <Tag
                                    class="capitalize"
                                    :value="data.stock_status.replace('_', ' ')"
                                />
                            </template>
                        </Column>
                        <Column header="Discount from date">
                            <template #body="{ data }">
                                {{ data.discount_start_date ?? "-" }}
                            </template>
                        </Column>
                        <Column header="Discount to date">
                            <template #body="{ data }">
                                {{ data.discount_end_date ?? "-" }}
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
            <Link :href="route(`admin.products.index`)">
                <Button label="Back To List" size="small" severity="contrast" />
            </Link>
        </div>
    </Dashboard>
</template>

<script setup>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Image from "primevue/image";
import Button from "primevue/button";
import { usePage, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import Tag from "primevue/tag";
import currencyFormat from "@/composables/useCurrencyFormat";
import { isEmpty } from "lodash";

const switchLanguage = ref("en");

defineProps({
    product: Object,
});

const page = usePage();

const { languages } = page.props;

const getSeverity = (status) => {
    switch (status) {
        case "draft":
            return "danger";

        case "active":
            return "success";
    }
};
</script>

<style lang="scss" scoped></style>
