<template>
    <div class="mt-5">
        <SearchBox
            :label="'payment-infos'"
            :permission="'paymentinfo_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column header="Image">
                <template #body="{ data }">
                    <!-- <img class="h-12" :src="data.upload?.thumbnail" alt="" /> -->
                    <Image
                        :src="data.upload?.url"
                        width="50"
                        :preview="data.upload?.url"
                    />
                </template>
            </Column>
            <Column field="name" header="Bank name"> </Column>
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
                        route-name="payment-infos"
                        :is-delete="hasPermission('paymentinfo_delete')"
                        :is-show="hasPermission('paymentinfo_show')"
                        :is-edit="hasPermission('paymentinfo_edit')"
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
import Image from "primevue/image";

defineProps({
    items: Array,
    links: Array,
});

const { getSeverity } = useServerity();

const { hasPermission } = useRolePermission();
</script>

<style lang="scss" scoped></style>
