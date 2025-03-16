<template>
    <div class="mt-5">
        <SearchBox
            :label="'customers'"
            :permission="'customer_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column field="username" header="User Name"></Column>
            <Column field="email" header="Email">
                <template #body="{ data }">
                    {{ data?.email || "---" }}
                </template>
            </Column>
            <Column field="phone" header="Phone">
                <template #body="{ data }">
                    {{ data?.phone || "---" }}
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
                        route-name="customers"
                        :is-delete="hasPermission('customer_delete')"
                        :is-show="hasPermission('customer_show')"
                        :is-edit="hasPermission('customer_edit')"
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
const { hasPermission } = useRolePermission();
const { getSeverity } = useServerity();
</script>

<style lang="scss" scoped></style>
