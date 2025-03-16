<template>
    <div class="mt-5">
        <div
            class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
        >
            <h4 class="text-base mb-3 font-bold text-black">Order History</h4>
            <DataTable :value="orders.data" tableStyle="min-width: 50rem">
                <Column field="order_no" header="Order code">
                    <template #body="{ data }">
                        <Link
                            :href="route('admin.orders.show', data.id)"
                            class="action-link cursor-pointer hover:underline hover:underline-offset-2"
                        >
                            <i class="fa-solid fa-eye"></i>

                            {{ data.order_no }}
                        </Link>
                    </template>
                </Column>

                <Column field="customer" header="Order Customer">
                    <template #body="{ data }">
                        <Link
                            :href="
                                route('admin.customers.show', data.customer_id)
                            "
                            class="action-link cursor-pointer hover:underline hover:underline-offset-2"
                        >
                            <i class="fa-solid fa-eye"></i>

                            {{
                                `${data.order_customer.first_name} ${data.order_customer.last_name} `
                            }}
                        </Link>
                    </template>
                </Column>

                <Column class="text-nowrap" header="Total Tax">
                    <template #body="{ data }">
                        <span v-html="currencyFormat(data.total_tax)"></span>
                    </template>
                </Column>

                <Column class="text-nowrap" header="Total Price">
                    <template #body="{ data }">
                        <span v-html="currencyFormat(data.total_price)"></span>
                    </template>
                </Column>

                <Column field="order_status" header="Order Status">
                    <template #body="{ data }">
                        <Tag
                            class="capitalize"
                            :value="data.order_status"
                            :severity="getSeverityForOrder(data.order_status)"
                        />
                    </template>
                </Column>

                <Column header="Payment Status" field="payment_status">
                    <template #body="{ data }">
                        <Tag
                            class="capitalize"
                            :value="data.payment_status"
                            :severity="
                                getSeverityForPayment(data.payment_status)
                            "
                        />
                    </template>
                </Column>

                <Column
                    field="created_at"
                    header="Created at"
                    :showFilterMenu="false"
                >
                    <template #body="{ data }">
                        {{ moment(data.created_at).format("DD/MM/YYYY") }}
                        <span class="block text-sm">
                            {{ moment(data.created_at).format("hh:mm A") }}
                        </span>
                    </template>
                </Column>

                <Column header="Action" class="relative">
                    <template #body="{ data }">
                        <TableActions
                            :data="data"
                            route-name="orders"
                            :is-delete="false"
                            :is-show="hasPermission('order_show')"
                            :is-edit="false"
                        />
                    </template>
                </Column>
                <template #empty>
                    <p class="text-center">No records</p>
                </template>
            </DataTable>
            <Pagination :links="orders.links" />
        </div>
    </div>
    <div class="col-span-full mt-5">
        <div
            class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
        >
            <h4 class="text-base mb-3 font-bold text-black">
                Point transactions
            </h4>
            <DataTable
                :value="point_transactions.data"
                tableStyle="min-width: 50rem"
            >
                <Column header="Customer">
                    <template #body="{ data }">
                        <Link
                            class="action-link cursor-pointer hover:underline hover:underline-offset-2"
                            :href="
                                route('admin.customers.show', data.customer_id)
                            "
                        >
                            <i class="fa-solid fa-eye"></i>
                            {{ data.customer.first_name }}
                            {{ data.customer.last_name }}
                        </Link>
                    </template>
                </Column>
                <Column header="Usage Amount">
                    <template #body="{ data }">
                        <span v-html="currencyFormat(data.usage_amount)">
                        </span>
                    </template>
                </Column>
                <Column header="Exchange Rate">
                    <template #body="{ data }">
                        <span v-html="currencyFormat(data.exchange_rate)">
                        </span>
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
                <Column header="Point Status">
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
            <Pagination :links="point_transactions.links" />
        </div>
    </div>
</template>

<script setup>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Tag from "primevue/tag";
import moment from "moment";
import { useTagStatus } from "@/composables/useTagStatus";
import _ from "lodash";
import { Link } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { useRolePermission } from "@/composables/useRolePermission";
import currencyFormat from "@/composables/useCurrencyFormat";

const props = defineProps({
    orders: Array,
    point_transactions: Array,
    links: Array,
});

const {
    getSeverityForOrder,
    getSeverityForPayment,
    orderStatus,
    paymentStatus,
} = useTagStatus();
const toast = useToast();

const { hasPermission } = useRolePermission();
</script>

<style lang="scss" scoped></style>
