<template>
    <div class="mt-5">
        <SearchBox
            :label="'languages'"
            :permission="'language_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column field="name" header="Name"></Column>
            <Column field="code" header="Code"></Column>
            <Column header="Active">
                <template #body="{ data }">
                    <Tag
                        class="capitalize"
                        :value="data.active"
                        :severity="getSeverity(data.active)"
                    />
                </template>
            </Column>
            <Column header="Default">
                <template #body="{ data }">
                    <Tag
                        class="capitalize"
                        :value="data.is_default ? 'Yes' : 'No'"
                    />
                </template>
            </Column>

            <Column header="Action" class="relative">
                <template #body="{ data }">
                    <TableActions
                        :data="data"
                        route-name="languages"
                        :is-delete="hasPermission('language_delete')"
                        :is-show="false"
                        :is-edit="hasPermission('language_edit')"
                    />
                </template>
            </Column>
            <template #empty>
                <p class="text-center">No records</p>
            </template>
        </DataTable>

        <Pagination :links="links" />
    </div>
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
