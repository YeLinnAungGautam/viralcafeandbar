<template>
    <div
        class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
    >
        <h4 class="text-base mb-3 font-bold text-black">Transaction lists</h4>
        <DataTable :value="transactions" tableStyle="min-width: 50rem">
            <Column header="Customer">
                <template #body="{ data }">
                    <Link
                        class="action-link"
                        :href="route('admin.customers.show', data.customer_id)"
                    >
                        <i class="fa-solid fa-eye"></i>
                        {{ data.customer.first_name }}
                        {{ data.customer.last_name }}
                    </Link>
                </template>
            </Column>
            <Column header="Amount">
                <template #body="{ data }">
                    <span v-html="currencyFormat(data.amount)"></span>
                </template>
            </Column>
            <Column header="Payment method">
                <template #body="{ data }">
                    <span class="capitalize">
                        {{ data.payment.title }}
                    </span>
                </template>
            </Column>
            <Column header="Payment date">
                <template #body="{ data }">
                    {{ moment(data.payment_date).format("DD/MM/YYYY") }}
                </template>
            </Column>
            <Column header="Notes">
                <template #body="{ data }">
                    {{ data.note }}
                </template>
            </Column>
            <Column header="Action">
                <template #body="{ data }">
                    <div class="flex gap-x-2">
                        <TableActions
                            :data="data"
                            route-name="transactions"
                            :is-delete="hasPermission('transaction_delete')"
                            :is-show="false"
                            :is-edit="false"
                        >
                            <template #other>
                                <button
                                    v-if="hasPermission('transaction_edit')"
                                    @click="emits('edit', data)"
                                    class="w-8 h-8 inline-flex justify-center items-center bg-slate-200 rounded-full"
                                >
                                    <i class="fa-solid fa-pencil text-sm"></i>
                                </button>
                                <button
                                    @click.prevent="handleShow(data)"
                                    class="w-8 h-8 inline-flex justify-center items-center bg-slate-200 rounded-full"
                                >
                                    <i class="fa-solid fa-image text-sm"></i>
                                </button>
                            </template>
                        </TableActions>
                    </div>
                </template>
            </Column>
            <template #empty>
                <p class="text-center">No records</p>
            </template>
        </DataTable>
        <Dialog
            v-model:visible="visible"
            modal
            header="Transaction"
            :style="{ width: '500px' }"
        >
            <div>
                <div v-if="order.upload">
                    <h4 class="font-bold">Image</h4>
                    <img
                        class="object-cover w-full"
                        :src="order?.upload?.url"
                    />
                </div>
                <table class="w-full">
                    <tbody>
                        <tr>
                            <th class="py-1 text-start">Order number</th>
                            <td class="py-1 text-end">#{{ orderNumber }}</td>
                        </tr>
                        <tr>
                            <th class="py-1 text-start">Customer</th>
                            <td class="py-1 text-end">
                                {{ order.customer.name }}
                            </td>
                        </tr>
                        <tr>
                            <th class="py-1 text-start">Amount</th>
                            <td class="py-1 text-end">
                                <div
                                    v-html="currencyFormat(order.amount)"
                                ></div>
                            </td>
                        </tr>
                        <tr>
                            <th class="py-1 text-start">Payment method</th>
                            <td class="py-1 text-end">
                                {{ order.payment.title }}
                            </td>
                        </tr>
                        <tr>
                            <th class="py-1 text-start">Payment date</th>
                            <td class="py-1 text-end">
                                {{
                                    moment(order.payment_date).format(
                                        "DD/MM/YYYY"
                                    )
                                }}
                            </td>
                        </tr>
                        <tr>
                            <th class="py-1 text-start">Note</th>
                            <td class="py-1 text-end">{{ order.note }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Dialog>
    </div>
</template>

<script setup>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import { useTagStatus } from "@/composables/useTagStatus";
import moment from "moment";
import Tag from "primevue/tag";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import Dialog from "primevue/dialog";
import { useRolePermission } from "@/composables/useRolePermission";
import TableActions from "@/Components/Layouts/TableActions.vue";
import currencyFormat from "@/composables/useCurrencyFormat";

const { hasPermission } = useRolePermission();

const visible = ref(false);
const order = ref();

const emits = defineEmits(["edit"]);

defineProps({
    transactions: Array,
    orderNumber: String,
});

const handleShow = (data) => {
    visible.value = true;
    order.value = data;
};

const { getSeverityForPayment, getSeverityForOrder } = useTagStatus();
</script>

<style lang="scss" scoped></style>
