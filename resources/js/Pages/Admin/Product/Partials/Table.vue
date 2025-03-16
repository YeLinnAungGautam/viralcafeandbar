<template>
    <div class="mt-5">
        <SearchBox
            :label="'products'"
            :permission="'product_create'"
            :excel="true"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable
            :value="items"
            @sort="onSort"
            removableSort
            tableStyle="min-width: 50rem"
            :first="first"
        >
            <Column header="Image">
                <template #body="{ data }">
                    <!-- <img :src="data.upload?.at(-1)?.thumbnail" alt="" /> -->
                    <Image
                        :src="data.upload?.at(-1)?.url"
                        width="50"
                        :preview="data.upload?.at(-1)?.url"
                    />
                </template>
            </Column>
            <Column field="name" header="Name" sortable>
                <template #body="{ data }">
                    <Link
                        :href="route('admin.products.show', data.id)"
                        class="action-link cursor-pointer hover:underline hover:underline-offset-2"
                    >
                        <i class="fa-solid fa-eye"></i>

                        {{ data.name }}
                    </Link>
                </template></Column
            >
            <Column field="original_price" header="Price" sortable>
                <template #body="{ data }">
                    <span
                        class="text-nowrap"
                        v-html="
                            currencyFormat(
                                data.min_sale_price
                                    ? data.min_sale_price
                                    : data.min_original_price
                            )
                        "
                    ></span>
                    <small
                        v-if="data.min_sale_price"
                        class="line-through me-1 block text-slate-500"
                        v-html="currencyFormat(data.min_original_price)"
                    >
                    </small>
                </template>
            </Column>
            <Column field="stock" header="Total Stock" class="w-20" sortable>
                <template #body="{ data }">
                    {{ data.total_stock ?? "Unlimit" }}
                </template>
            </Column>
            <Column header="Product Status">
                <template #body="{ data }">
                    <Tag
                        class="capitalize text-nowrap"
                        :value="
                            data.product_status == 'arrival'
                                ? 'New Arrival'
                                : 'Featured'
                        "
                        severity="info"
                    />
                </template>
            </Column>
            <Column field="active" header="Active">
                <template #body="{ data }">
                    <Tag
                        class="capitalize"
                        :value="data.active"
                        :severity="getSeverity(data.active)"
                    />
                </template>
            </Column>
            <Column field="created_at" header="Date & time" sortable>
                <template #body="{ data }">
                    {{ moment(data.created_at).format("DD/MM/YYYY") }}
                    <span class="block">
                        {{ moment(data.created_at).format("hh:ss A") }}
                    </span>
                </template>
            </Column>
            <Column header="Action" class="relative">
                <template #body="{ data }">
                    <TableActions
                        :data="data"
                        route-name="products"
                        :is-delete="hasPermission('product_delete')"
                        :is-show="hasPermission('product_show')"
                        :is-edit="hasPermission('product_edit')"
                    />
                </template>
            </Column>
            <template #empty>
                <p class="text-center">No records</p>
            </template>
        </DataTable>
    </div>
    <Pagination :links="links" />
</template>

<script setup>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Tag from "primevue/tag";
import moment from "moment";
import { Link, router } from "@inertiajs/vue3";
import currencyFormat from "@/composables/useCurrencyFormat";
import SearchBox from "@/Components/Layouts/SearchBox.vue";
import { useRolePermission } from "@/composables/useRolePermission";
import Image from "primevue/image";
import { onMounted, ref } from "vue";

defineProps({
    items: Array,
    links: Array,
});
const first = ref();
const { hasPermission } = useRolePermission();

const getSeverity = (status) => {
    switch (status) {
        case "draft":
            return "danger";

        case "active":
            return "success";
    }
};

import useSortingTable from "@/composables/useSortingTable";

const { onSort, sortOrder, sortField } = useSortingTable(
    route("admin.products.index")
);

// const sortOrder = ref();
// const sortField = ref();

// const onSort = (data) => {
//     switch (data.sortField) {
//         case "product_name":
//             sortField.value = "name";
//             break;
//         case "regular_price":
//             sortField.value = "min_original_price";
//             break;
//         default:
//             sortField.value = null;
//             break;
//     }
//     switch (data.sortOrder) {
//         case 1:
//             sortOrder.value = "asc";
//             break;
//         case -1:
//             sortOrder.value = "desc";
//             break;
//         default:
//             sortOrder.value = null;
//             break;
//     }
//     router.get(
//         route("admin.products.index"),
//         { sortOrder: sortOrder.value, sortField: sortField.value },
//         {
//             preserveState: true,
//             preserveScroll: true,
//             only: ["products"],
//         }
//     );
// };
</script>

<style lang="css" scoped></style>
