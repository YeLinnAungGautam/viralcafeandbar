<template>
    <div
        class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
    >
        <h4 class="text-base mb-3 font-bold text-black">Point transactions</h4>
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column header="Customer">
                <template #body="{ data }">
                    <Link
                        class="action-link"
                        :href="route('admin.customers.show', data.customer.id)"
                    >
                        <i class="fa-solid fa-eye"></i>
                        {{ data.customer.first_name }}
                        {{ data.customer.last_name }}
                    </Link>
                </template>
            </Column>
            <Column header="Usage amount">
                <template #body="{ data }">
                    <span v-html="currencyFormat(data.usage_amount)"></span>
                </template>
            </Column>
            <Column header="Exchange rate">
                <template #body="{ data }">
                    <span v-html="currencyFormat(data.exchange_rate)"></span>
                </template>
            </Column>
            <Column header="Point">
                <template #body="{ data }">
                    <span :class="data.status">
                        {{ data.status == "expand" ? "+" : "-" }}
                        {{ data.point }}
                    </span>
                </template>
            </Column>
            <Column header="Point status">
                <template #body="{ data }">
                    <span class="capitalize">
                        {{ data.status }}
                    </span>
                </template>
            </Column>
            <template #empty>
                <p class="text-center">No records</p>
            </template>
        </DataTable>
    </div>
</template>

<script setup>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import { Link } from "@inertiajs/vue3";
import currencyFormat from "@/composables/useCurrencyFormat";

defineProps({
    items: Array,
});
</script>

<style lang="css" scoped>
.expand {
    color: green;
}

.reduce {
    color: red;
}
</style>
