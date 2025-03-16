<template>
    <div class="mt-5">
        <SearchBox
            :label="'users'"
            :permission="'user_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <div>
            <DataTable
                :value="items"
                tableStyle="min-width: 50rem"
                :emptyMessage="'No cars found'"
            >
                <Column field="name" header="Name"></Column>
                <Column field="email" header="Email"></Column>
                <Column header="Role">
                    <template #body="{ data }">
                        <div>{{ data.roles[0].name }}</div>
                    </template>
                </Column>
                <Column header="Action" class="relative">
                    <template #body="{ data }">
                        <TableActions
                            :data="data"
                            route-name="users"
                            :is-delete="hasPermission('user_delete')"
                            :is-show="false"
                            :is-edit="hasPermission('user_edit')"
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
import SearchBox from "@/Components/Layouts/SearchBox.vue";
import { useRolePermission } from "@/composables/useRolePermission";

defineProps({
    items: Array,
    links: Array,
});

const { hasPermission } = useRolePermission();
</script>

<style lang="scss" scoped></style>
