<template>
    <div class="mt-5">
        <SearchBox
            :label="'banners'"
            :permission="'banner_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column header="Image">
                <template #body="{ data }">
                    <!-- <img class="h-12" :src="data.upload?.thumbnail" alt="" /> -->
                    <Image
                        :src="data.upload?.url"
                        width="50"
                        :preview="data.upload?.url"
                    />
                </template>
            </Column>
            <Column field="title" header="Title">
                <template #body="{ data }">
                    <div class="capitalize">
                        {{ data.title }}
                    </div>
                </template></Column
            >

            <Column header="Status">
                <template #body="{ data }">
                    <Tag
                        class="capitalize"
                        :value="data.status"
                        :severity="getSeverity(data.status)"
                    />
                </template>
            </Column>

            <Column header="Product">
                <template #body="{ data }">
                    {{ data.product_id ? data.product.name : "---" }}
                </template>
            </Column>

            <Column header="Category">
                <template #body="{ data }">
                    {{ data.category_id ? data.category.name : "---" }}
                </template>
            </Column>

            <Column header="Action" class="relative">
                <template #body="{ data }">
                    <TableActions
                        :data="data"
                        route-name="banners"
                        :is-delete="hasPermission('banner_delete')"
                        :is-show="hasPermission('banner_show')"
                        :is-edit="hasPermission('banner_edit')"
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
import { useServerity } from "@/composables/useServerity";
import SearchBox from "@/Components/Layouts/SearchBox.vue";
import Image from "primevue/image";
import { useRolePermission } from "@/composables/useRolePermission";

const { getSeverity } = useServerity();
const { hasPermission } = useRolePermission();

defineProps({
    items: Array,
    links: Array,
});
</script>

<style lang="scss" scoped></style>
