<template>
    <div class="mt-5">
        <SearchBox
            :label="'pages'"
            :permission="'page_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column field="title" header="Title" class="w-50">
                <template #body="{ data }">
                    <div class="capitalize">
                        {{ data.title }}
                    </div>
                </template></Column
            >
            <Column header="Excerpt">
                <template #body="{ data }">
                    <div v-html="data.excerpt ? data.excerpt : '-'"></div>
                </template>
            </Column>
            <Column header="Status">
                <template #body="{ data }">
                    <Tag
                        class="capitalize"
                        :value="data.status"
                        :severity="getSeverity(data.status)"
                    />
                </template>
            </Column>

            <Column header="Action" class="relative">
                <template #body="{ data }">
                    <TableActions
                        :data="data"
                        route-name="pages"
                        :is-delete="hasPermission('page_delete')"
                        :is-show="hasPermission('page_show')"
                        :is-edit="hasPermission('page_edit')"
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
import { useRolePermission } from "@/composables/useRolePermission";

const { getSeverity } = useServerity();
defineProps({
    items: Array,
    links: Array,
});

const { hasPermission } = useRolePermission();
</script>

<style lang="scss" scoped></style>
