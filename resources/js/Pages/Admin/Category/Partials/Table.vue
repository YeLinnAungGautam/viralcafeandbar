<template>
    <div class="mt-5">
        <SearchBox
            :label="'categories'"
            :permission="'category_create'"
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
            <Column field="name" header="Name"></Column>
            <Column header="Action" class="relative">
                <template #body="{ data }">
                    <TableActions
                        :data="data"
                        route-name="categories"
                        :is-delete="hasPermission('category_delete')"
                        :is-show="hasPermission('category_show')"
                        :is-edit="hasPermission('category_edit')"
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
import SearchBox from "@/Components/Layouts/SearchBox.vue";
import Image from "primevue/image";
import { useRolePermission } from "@/composables/useRolePermission";

defineProps({
    items: Array,
    links: Array,
});
const { hasPermission } = useRolePermission();
</script>

<style lang="scss" scoped></style>
