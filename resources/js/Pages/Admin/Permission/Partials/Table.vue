<template>
    <div class="mt-5">
        <SearchBox
            :label="'permissions'"
            :permission="'permission_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column field="name" header="Name"></Column>
            <Column header="Action" class="relative">
                <template #body="{ data }">
                    <TableActions
                        :data="data"
                        route-name="permissions"
                        :is-delete="hasPermission('permission_delete')"
                        :is-show="false"
                        :is-edit="hasPermission('permission_edit')"
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
import { useRolePermission } from "@/composables/useRolePermission";

defineProps({
    items: Array,
    links: Array,
});
const { hasPermission } = useRolePermission();
</script>

<style lang="scss" scoped></style>
