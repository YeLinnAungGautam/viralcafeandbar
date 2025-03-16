<template>
    <div class="mt-5">
        <SearchBox
            :label="'memberships'"
            :permission="'membership_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column field="name" header="Name"></Column>
            <Column field="min_point" header="Min point"></Column>
            <Column field="max_point" header="Max point"></Column>
            <Column field="{{ percentage }}" header="Percentage">
                <template #body="slotProps">
                    {{ `${slotProps.data.percentage}%` }}
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
                        route-name="memberships"
                        :is-delete="hasPermission('membership_delete')"
                        :is-show="hasPermission('membership_show')"
                        :is-edit="hasPermission('membership_edit')"
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
import SearchBox from "@/Components/Layouts/SearchBox.vue";
import { useServerity } from "@/composables/useServerity";
import { useRolePermission } from "@/composables/useRolePermission";

defineProps({
    items: Array,
    links: Array,
});
const { getSeverity } = useServerity();
const { hasPermission } = useRolePermission();
</script>

<style lang="scss" scoped></style>
