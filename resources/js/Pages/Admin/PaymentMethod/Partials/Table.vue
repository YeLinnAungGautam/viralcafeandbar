<template>
    <div class="mt-5">
        <SearchBox
            :label="'payments'"
            :permission="'paymentmethod_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column field="title" header="Title"></Column>
            <Column header="Key">
                <template #body="{ data }">
                    <div v-html="data.key ? data.key : '-'"></div>
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
                        route-name="payments"
                        :is-delete="hasPermission('paymentmethod_delete')"
                        :is-show="hasPermission('paymentmethod_show')"
                        :is-edit="hasPermission('paymentmethod_edit')"
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
import { useRolePermission } from "@/composables/useRolePermission";
import SearchBox from "@/Components/Layouts/SearchBox.vue";

const { getSeverity } = useServerity();
defineProps({
    items: Array,
    links: Array,
});
const { hasPermission } = useRolePermission();
</script>

<style lang="scss" scoped></style>
