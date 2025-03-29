<template>
    <Dashboard title="Show Order">
        <BreadcrumbDefault page-title="Order Details" />
        <div class="card flex justify-end gap-2 mb-4">
            <Button
                v-if="order.order_status != 'cancel'"
                type="button"
                @click="visibleTransaction = true"
                size="small"
                label="Record a payment"
                severity="contrast"
            />
            <Button
                type="button"
                label="More actions"
                size="small"
                @click="toggle"
                severity="contrast"
                aria-haspopup="true"
                aria-controls="overlay_menu"
                icon="pi pi-chevron-down"
                iconPos="right"
            />
            <Menu ref="menu" id="overlay_menu" :model="items" :popup="true">
                <template #item="{ item, props }">
                    <a :href="item.route" target="_blank" v-bind="props.action">
                        <span :class="item.icon" />
                        <span class="ml-2">{{ item.label }}</span>
                    </a>
                </template>
            </Menu>
        </div>
        <div class="grid grid-cols-2 gap-x-5">
            <div class="col-span-1">
                <div
                    class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
                >
                    <h4 class="text-base mb-3 font-bold text-black">
                        Order details
                    </h4>
                    <table class="w-full text-sm text-left">
                        <tbody class="text-gray-900">
                            <tr>
                                <th scope="col" class="py-2">Order number</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <span
                                        :class="
                                            order.order_status == 'cancel'
                                                ? 'text-danger'
                                                : ''
                                        "
                                    >
                                        {{ order.order_no }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="py-2">Order date</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    {{
                                        moment(order.order_date).format(
                                            "DD/MM/YYYY"
                                        )
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="py-2">Order status</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <Tag
                                        class="capitalize"
                                        :value="order.order_status"
                                        :severity="
                                            getSeverityForOrder(
                                                order.order_status
                                            )
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="py-2">Payment status</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <Tag
                                        class="capitalize"
                                        :value="order.payment_status"
                                        :severity="
                                            getSeverityForPayment(
                                                order.payment_status
                                            )
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="py-2">Payment method</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2 capitalize">
                                    {{ order?.payment?.title }}
                                </td>
                            </tr>
                            <!-- <tr>
                                <th scope="col" class="py-2">Total tax</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <span
                                        v-html="currencyFormat(order.total_tax)"
                                    ></span>
                                </td>
                            </tr> -->
                            <tr>
                                <th scope="col" class="py-2">Sub total</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <span
                                        v-html="currencyFormat(order.subtotal)"
                                    ></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="py-2">Total discount</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <span
                                        v-html="
                                            currencyFormat(order.total_discount)
                                        "
                                    ></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="py-2">Grand total</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <span
                                        v-html="
                                            currencyFormat(order.total_price)
                                        "
                                    ></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="py-2">Total expense</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <span
                                        v-html="
                                            currencyFormat(order.total_expense)
                                        "
                                    ></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="py-2">Total profix</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <span
                                        v-html="
                                            currencyFormat(
                                                order.total_price -
                                                    order.total_expense
                                            )
                                        "
                                    ></span>
                                </td>
                            </tr>
                            <!-- <tr>
                                <th scope="col" class="py-2">Total profit</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <span
                                        v-html="
                                            currencyFormat(
                                                order.total_price -
                                                    order.total_expense
                                            )
                                        "
                                    ></span>
                                </td>
                            </tr> -->

                            <tr>
                                <th scope="col" class="py-2">Created on</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2 capitalize">
                                    {{
                                        moment(order.created_at).format(
                                            "DD/MM/YYYY"
                                        )
                                    }}
                                    <span class="">
                                        {{
                                            moment(order.created_at).format(
                                                "hh:mm A"
                                            )
                                        }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-span-1">
                <div
                    class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
                >
                    <h4 class="text-base mb-3 font-bold text-black">
                        Customer details
                    </h4>
                    <table class="w-full text-sm text-left">
                        <tbody class="text-gray-900">
                            <tr>
                                <th scope="col" class="py-2">First name</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    {{ `${order.order_customer.first_name} ` }}
                                </td>
                            </tr>
                            <!-- <tr>
                                <th scope="col" class="py-2">Last name</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    {{ `${order.order_customer.last_name} ` }}
                                </td>
                            </tr> -->
                            <tr>
                                <th scope="col" class="py-2">Email</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    {{ order.order_customer.email }}
                                </td>
                            </tr>
                            <!-- <tr>
                                <th scope="col" class="py-2">Phone</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    {{ order.order_customer.phone }}
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="py-2">Address</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    <div
                                        class="whitespace-pre"
                                        v-html="
                                            order.order_customer?.address ||
                                            '---'
                                        "
                                    ></div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="py-2">Country</th>
                                <td scope="col" class="py-2 w-10">-</td>
                                <td scope="col" class="py-2">
                                    {{ order.order_customer.country }}
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
                <div
                    class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
                >
                    <h3 class="text-base mb-3 text-black font-bold">
                        Internal notes
                    </h3>
                    <p>
                        {{ order.internal_note ?? "---" }}
                    </p>
                </div>
            </div>
            <div class="col-span-full mt-5">
                <div
                    class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
                >
                    <h4 class="text-base mb-3 font-bold text-black">
                        Product lists
                    </h4>
                    <DataTable
                        :value="order.order_items"
                        tableStyle="min-width: 50rem"
                    >
                        <Column header="Product">
                            <template #body="{ data }">
                                {{ data.product_name }}
                                <h6 class="text-sm text-slate-500">
                                    {{ data.sku_name }}
                                </h6>
                            </template>
                        </Column>

                        <Column
                            header="Quantity"
                            headerClass="text-end"
                            :bodyStyle="{ textAlign: 'right' }"
                        >
                            <template #body="{ data }">
                                {{ data.qty }}
                            </template>
                        </Column>
                        <Column
                            header="Unit expense"
                            headerClass="text-end"
                            :bodyStyle="{ textAlign: 'right' }"
                        >
                            <template #body="{ data }">
                                <span
                                    v-html="
                                        currencyFormat(data.unit_expense, false)
                                    "
                                ></span>
                            </template>
                        </Column>
                        <Column
                            header="Total expense"
                            headerClass="text-end"
                            :bodyStyle="{ textAlign: 'right' }"
                        >
                            <template #body="{ data }">
                                <span
                                    v-html="
                                        currencyFormat(
                                            data.total_expense,
                                            false
                                        )
                                    "
                                ></span>
                            </template>
                        </Column>
                        <Column
                            header="Profix (Only orignal)"
                            headerClass="text-end"
                            :bodyStyle="{ textAlign: 'right' }"
                        >
                            <template #body="{ data }">
                                +<span
                                    v-html="
                                        currencyFormat(
                                            data.original_price * data.qty -
                                                data.total_expense,
                                            false
                                        )
                                    "
                                ></span>
                            </template>
                        </Column>
                        <Column
                            header="Unit price"
                            headerClass="text-end"
                            :bodyStyle="{ textAlign: 'right' }"
                        >
                            <template #body="{ data }">
                                <span
                                    v-html="
                                        currencyFormat(
                                            data.original_price,
                                            false
                                        )
                                    "
                                ></span>
                            </template>
                        </Column>
                        <Column
                            header="Total"
                            headerClass="text-end"
                            :bodyStyle="{ textAlign: 'right' }"
                        >
                            <template #body="{ data }">
                                <span
                                    v-html="
                                        currencyFormat(
                                            data.original_price * data.qty,
                                            false
                                        )
                                    "
                                ></span>
                            </template>
                        </Column>
                        <ColumnGroup type="footer">
                            <Row>
                                <Column
                                    footer="Subtotal:"
                                    :colspan="6"
                                    footerStyle="text-align:right"
                                />
                                <Column footerStyle="text-align:right">
                                    <template #footer>
                                        <span
                                            v-html="
                                                currencyFormat(
                                                    order.subtotal,
                                                    false
                                                )
                                            "
                                        ></span>
                                    </template>
                                </Column>
                            </Row>
                            <!-- <Row>
                                <Column
                                    footer="Tax:"
                                    :colspan="6"
                                    footerStyle="text-align:right"
                                />
                                <Column footerStyle="text-align:right">
                                    <template #footer>
                                        <span
                                            v-html="
                                                currencyFormat(
                                                    order.total_tax,
                                                    false
                                                )
                                            "
                                        />
                                    </template>
                                </Column>
                            </Row> -->
                            <Row class="text-right" v-if="order.total_discount">
                                <Column
                                    :footer="`Total Discount (${settings.currency}):`"
                                    :colspan="6"
                                    footerStyle="text-align:right"
                                />
                                <Column footerStyle="text-align:right">
                                    <template #footer>
                                        <span
                                            v-html="
                                                currencyFormat(
                                                    order.total_discount,
                                                    false
                                                )
                                            "
                                        />
                                    </template>
                                </Column>
                            </Row>
                            <Row v-if="order.membership_discount">
                                <Column
                                    :footer="`Membership Discount (${order.membership_discount}%):`"
                                    :colspan="6"
                                    footerStyle="text-align:right"
                                />
                                <Column footerStyle="text-align:right">
                                    <template #footer>
                                        <span
                                            v-html="
                                                currencyFormat(
                                                    order.membership_discount_amount,
                                                    false
                                                )
                                            "
                                        ></span>
                                    </template>
                                </Column>
                            </Row>
                            <Row>
                                <Column
                                    :footer="`Grand Total (${settings.currency}):`"
                                    :colspan="6"
                                    footerStyle="text-align:right"
                                />
                                <Column footerStyle="text-align:right">
                                    <template #footer>
                                        <span
                                            v-html="
                                                currencyFormat(
                                                    order.total_price,
                                                    false
                                                )
                                            "
                                        ></span>
                                    </template>
                                </Column>
                            </Row>
                            <Row>
                                <Column
                                    :footer="`Total Paid (${settings.currency}):`"
                                    :colspan="6"
                                    footerStyle="text-align:right"
                                />
                                <Column footerStyle="text-align:right">
                                    <template #footer>
                                        <span
                                            v-html="
                                                currencyFormat(totalPaid, false)
                                            "
                                        ></span>
                                    </template>
                                </Column>
                            </Row>
                            <Row>
                                <Column
                                    :footer="`Total Due (${settings.currency}):`"
                                    :colspan="6"
                                    footerStyle="text-align:right"
                                />
                                <Column footerStyle="text-align:right">
                                    <template #footer>
                                        <span
                                            v-html="
                                                currencyFormat(
                                                    order.total_price -
                                                        totalPaid,
                                                    false
                                                )
                                            "
                                        ></span>
                                    </template>
                                </Column>
                            </Row>
                        </ColumnGroup>
                    </DataTable>
                </div>
            </div>
            <div class="col-span-full mt-5">
                <TrasnsactionTable
                    @edit="handleTransactionEdit"
                    :transactions="order.transactions"
                    :orderNumber="order.order_no"
                />
            </div>
            <!-- <div class="col-span-full mt-5">
                <PointTable :items="order.point_transactions" />
            </div> -->
            <Link :href="route(`admin.orders.index`)">
                <Button label="Back To List" size="small" severity="contrast" />
            </Link>
        </div>
        <Dialog
            v-model:visible="visibleTransaction"
            modal
            header="Transaction"
            :style="{ width: '50vw' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        >
            <TransactionForm
                :editValue="transaction"
                @success="visibleTransaction = false"
                :order="order"
                :methods="methods"
                :total-paid="totalPaid"
            />
        </Dialog>
    </Dashboard>
</template>

<script setup>
import moment from "moment";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import { usePage, Link, router } from "@inertiajs/vue3";
import { computed, onUpdated, ref, watch } from "vue";
import Tag from "primevue/tag";
import { useTagStatus } from "@/composables/useTagStatus";
import ColumnGroup from "primevue/columngroup";
import Row from "primevue/row";
import Dialog from "primevue/dialog";
import TransactionForm from "./Partials/TransactionForm.vue";
import Menu from "primevue/menu";
import { useToast } from "primevue/usetoast";
import TrasnsactionTable from "./Partials/TrasnsactionTable.vue";
import PointTable from "./Partials/PointTable.vue";
import currencyFormat from "@/composables/useCurrencyFormat";
import { useConfirm } from "primevue/useconfirm";
import SplitButton from "primevue/splitbutton";

const page = usePage();

const { settings } = page.props;

const props = defineProps({
    order: Object,
    methods: Array,
});

const visibleTransaction = ref(false);
const menu = ref();
const menuDownload = ref();
const menuEmailSend = ref();

const transaction = ref();

const totalPaid = computed(() => getTotalAmount(props.order.transactions));

const items = ref([
    {
        items: [
            {
                label: "Export as PDF",
                icon: "fa-solid fa-file",
                route: `/admin/orders/${props.order.id}/download`,
                target: "_blank",
            },
            {
                label: "Resend order email",
                icon: "fa-regular fa-envelope",
                command: () => handleResend("confirmation"),
            },
            {
                label: "Cancel",
                icon: "fa-solid fa-cancel",
                command: () => handleChnageStatus("cancel"),
            },
        ],
    },
]);

const toggle = (event) => {
    menu.value.toggle(event);
};

const toast = useToast();
const confirm = useConfirm();

function getTotalAmount(transactions) {
    return transactions.reduce((sum, transaction) => {
        return sum + transaction.amount;
    }, 0);
}

const handleChnageStatus = (status) => {
    confirm.require({
        message: "Are you sure you want to cancel this order?",
        header: "Confirmation",
        icon: "pi pi-exclamation-triangle",
        rejectProps: {
            label: "Cancel",
            severity: "secondary",
            outlined: true,
        },
        acceptProps: {
            label: "Sure",
            severity: "secondary",
        },
        accept: () => {
            const options = {
                onSuccess: () => {
                    toast.add({
                        severity: "success",
                        summary: "Success",
                        detail: `Order was ${status}`,
                        life: 3000,
                    });
                },
                onError: (error) => {
                    toast.add({
                        severity: "warn",
                        summary: "Warning!",
                        detail: error.message,
                        life: 3000,
                    });
                },
            };
            router.post(
                route("admin.orders.update", props.order.id),
                {
                    _method: "put",
                    order_status: status,
                },
                options
            );
        },
        reject: () => {},
    });
};

const handleResend = (status) => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Order resending mail successful.`,
                life: 3000,
            });
        },
        onError: (error) => {
            toast.add({
                severity: "warn",
                summary: "Warning!",
                detail: error.message,
                life: 3000,
            });
        },
    };
    router.post(
        route("admin.orders.resend", {
            id: props.order.id,
            name: status,
        }),
        null,
        options
    );
};

const handleTransactionEdit = (data) => {
    transaction.value = data;
    visibleTransaction.value = true;
};

watch(visibleTransaction, (value) => {
    if (value == false) {
        transaction.value = null;
    }
});

const { getSeverityForPayment, getSeverityForOrder } = useTagStatus();
</script>

<style scoped>
.text-end .p-datatable-column-header-content {
    align-items: end !important;
}
</style>
