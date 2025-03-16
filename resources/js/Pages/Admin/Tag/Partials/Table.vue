<template>
    <div class="mt-5">
        <SearchBox
            :label="'tags'"
            :permission="'tag_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <div>
            <DataTable :value="items" tableStyle="min-width: 50rem">
                <Column field="name" header="Name">
                    <template #body="{ data }">
                        <div class="capitalize">
                            {{ data.name }}
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

                <Column header="Action" class="relative">
                    <template #body="{ data }">
                        <TableActions
                            :data="data"
                            route-name="tags"
                            :is-delete="hasPermission('tag_delete')"
                            :is-show="hasPermission('tag_show')"
                            :is-edit="hasPermission('tag_edit')"
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
