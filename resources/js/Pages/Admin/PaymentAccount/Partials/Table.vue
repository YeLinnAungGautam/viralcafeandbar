<template>
    <div class="mt-5">
        <SearchBox
            :label="'payment-accounts'"
            :permission="'paymentaccount_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column header="Account name">
                <template #body="{ data }">
                    <div>
                        {{ data.account_name }}
                    </div>
                </template>
            </Column>
            <Column header="Account number">
                <template #body="{ data }">
                    <div>
                        {{ data.account_number }}
                    </div>
                </template>
            </Column>
            <Column header="Bank name">
                <template #body="{ data }">
                    <div>
                        {{ data.banks?.name || "---" }}
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
                        route-name="payment-accounts"
                        :is-delete="hasPermission('paymentaccount_delete')"
                        :is-show="hasPermission('paymentaccount_show')"
                        :is-edit="hasPermission('paymentaccount_edit')"
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
