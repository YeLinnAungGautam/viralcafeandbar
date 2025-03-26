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
                    <Image
                        :src="data.upload?.at(-1)?.url"
                        class="w-[50px] h-[50px] object-cover"
                        :preview="data.upload.at(-1)?.thumbnail ? true : false"
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
                </template>
            </Column>
            <Column field="total_expense" header="Expense" sortable>
                <template #body="{ data }">
                    <span
                        class="text-nowrap"
                        v-html="currencyFormat(data.total_expense)"
                    ></span>
                </template>
            </Column>
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
            <Column field="stock" header=" Stock" class="w-20" sortable>
                <template #body="{ data }">
                    {{ data.total_stock ?? "Unlimit" }}
                </template>
            </Column>
            <Column header=" Status">
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
</script>

<style lang="css" scoped></style>
