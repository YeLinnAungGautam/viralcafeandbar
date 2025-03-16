<template>
    <div class="mt-5">
        <SearchBox
            :label="'templates'"
            :permission="'template_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <div>
            <DataTable :value="items" tableStyle="min-width: 50rem">
                <Column field="title" header="Title">
                    <template #body="{ data }">
                        <div class="capitalize">
                            {{ data.title }}
                        </div>
                    </template></Column
                >

                <Column header="Action" class="relative">
                    <template #body="{ data }">
                        <TableActions
                            :data="data"
                            route-name="templates"
                            :is-delete="hasPermission('template_delete')"
                            :is-show="hasPermission('template_show')"
                            :is-edit="hasPermission('template_edit')"
                        />
                    </template>
                </Column>
                <template #empty>
                    <p class="text-center">No records</p>
                </template>
            </DataTable>
            <Pagination :links="links" />
        </div>
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
const { hasPermission } = useRolePermission();

defineProps({
    items: Array,
    links: Array,
});
</script>

<style lang="scss" scoped></style>
