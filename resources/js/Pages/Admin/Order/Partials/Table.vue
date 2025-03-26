<template>
    <div class="mt-5">
        <div v-if="!listOnly" class="flex justify-between items-center my-5">
            <Link
                v-if="hasPermission('order_create')"
                :href="route('admin.orders.create')"
                class="btn-primary"
            >
                Create
            </Link>
            <div class="flex justify-end items-center gap-1">
                <DatePicker
                    placeholder="Start date"
                    :manualInput="false"
                    :showIcon="true"
                    date-format="dd-mm-yy"
                    v-model="dates.start_date"
                    @date-select="
                        (e) => {
                            dates.start_date = moment(e).format('YYYY-MM-DD');
                        }
                    "
                />
                <DatePicker
                    placeholder="End date"
                    :manualInput="false"
                    :showIcon="true"
                    date-format="dd-mm-yy"
                    v-model="dates.end_date"
                    @date-select="
                        (e) => {
                            dates.end_date = moment(e).format('YYYY-MM-DD');
                        }
                    "
                    :invalid="page.props.errors.end_date ? true : false"
                />
                <Button
                    @click.prevent="handleFilter"
                    label="Filter"
                    size="small"
                    severity="contrast"
                    icon="pi pi-search"
                />
                <Button
                    @click.prevent="clearDate"
                    type="submit"
                    label="Clear"
                    size="small"
                    severity="warn"
                    icon="pi pi-times"
                />
            </div>
        </div>
        <DataTable
            :value="items"
            tableStyle="min-width: 50rem"
            removableSort
            :sortField="sortField"
            :sortOrder="sortOrder"
            @sort="onSort"
            v-bind="listOnly ? {} : { filterDisplay: 'row' }"
        >
            <Column
                :showFilterMenu="false"
                field="order_no"
                header="Order Code"
                :show-clear-button="false"
            >
                <template #body="{ data }">
                    <Link
                        :href="route('admin.orders.show', data.id)"
                        class="action-link cursor-pointer hover:underline hover:underline-offset-2"
                    >
                        <i class="fa-solid fa-eye"></i>

                        {{ data.order_no }}
                    </Link>
                </template>

                <template #filter>
                    <InputText
                        size="small"
                        v-model="filterModel.order_no"
                        class="w-40"
                        type="text"
                        @input.prevent="
                            handleFilter(filterModel.order_no, 'order_no')
                        "
                        placeholder="Search by order code"
                        :show-clear-button="false"
                    />
                </template>
            </Column>

            <Column
                field="customer"
                header="Order Customer"
                :showFilterMenu="false"
                :show-clear-button="false"
            >
                <template #body="{ data }">
                    <Link
                        :href="route('admin.customers.show', data.customer_id)"
                        class="action-link cursor-pointer hover:underline hover:underline-offset-2"
                    >
                        <i class="fa-solid fa-eye"></i>

                        {{
                            `${data.order_customer.first_name} ${data.order_customer.last_name} `
                        }}
                    </Link>
                </template>

                <template #filter>
                    <InputText
                        size="small"
                        v-model="filterModel.customer"
                        class="w-40"
                        type="text"
                        @input.prevent="
                            handleFilter(filterModel.customer, 'customer')
                        "
                        placeholder="Search by customer"
                    />
                </template>
            </Column>

            <Column
                class="text-nowrap"
                header="Expense"
                :showFilterMenu="false"
                :show-clear-button="false"
            >
                <template #body="{ data }">
                    <span v-html="currencyFormat(data.total_expense)" />
                </template>
            </Column>

            <Column
                class="text-nowrap"
                header="Total Price"
                field="total_price"
                :showFilterMenu="false"
                :show-clear-button="false"
                :sortable="listOnly ? false : true"
            >
                <template #body="{ data }">
                    <span v-html="currencyFormat(data.total_price)" />
                </template>
            </Column>

            <Column
                field="order_status"
                header="Order Status"
                :showFilterMenu="false"
                :show-clear-button="false"
            >
                <template #body="{ data }">
                    <Tag
                        class="capitalize"
                        :value="data.order_status"
                        :severity="getSeverityForOrder(data.order_status)"
                    />
                </template>
                <template #filter>
                    <PSelect
                        v-model="filterModel.order_status"
                        :options="orderStatus"
                        placeholder="Select One"
                        :showClear="true"
                        optionLabel="value"
                        optionValue="key"
                        @change="
                            handleFilter(
                                filterModel.order_status,
                                'order_status'
                            )
                        "
                    >
                        <template #option="slotProps">
                            <Tag
                                :value="slotProps.option.value"
                                :severity="
                                    getSeverityForOrder(slotProps.option.key)
                                "
                            />
                        </template>
                    </PSelect>
                </template>
            </Column>

            <Column
                header="Payment Status"
                field="payment_status"
                :showFilterMenu="false"
                :show-clear-button="false"
            >
                <template #body="{ data }">
                    <Tag
                        class="capitalize"
                        :value="data.payment_status"
                        :severity="getSeverityForPayment(data.payment_status)"
                    />
                </template>
                <template #filter>
                    <PSelect
                        v-model="filterModel.payment_status"
                        :options="paymentStatus"
                        placeholder="Select One"
                        :showClear="true"
                        optionLabel="value"
                        optionValue="key"
                        @change="
                            handleFilter(
                                filterModel.payment_status,
                                'payment_status'
                            )
                        "
                    >
                        <template #option="slotProps">
                            <Tag
                                :value="slotProps.option.value"
                                :severity="
                                    getSeverityForPayment(slotProps.option.key)
                                "
                            />
                        </template>
                    </PSelect>
                </template>
            </Column>

            <Column
                field="created_at"
                header="Created on"
                :showFilterMenu="false"
                :sortable="listOnly ? false : true"
            >
                <template #body="{ data }">
                    {{ moment(data.created_at).format("DD/MM/YYYY") }}
                    <span class="block text-sm">
                        {{ moment(data.created_at).format("hh:mm A") }}
                    </span>
                </template>
            </Column>

            <Column v-if="isAction" header="Action" class="relative">
                <template #body="{ data }">
                    <TableActions
                        :data="data"
                        route-name="orders"
                        :is-delete="hasPermission('order_delete')"
                        :is-show="hasPermission('order_show')"
                        :is-edit="false"
                    />
                </template>
            </Column>
            <template #empty>
                <p class="text-center">No records</p>
            </template>
        </DataTable>
    </div>
    <div v-if="!listOnly">
        <Pagination v-if="links.length > 0" :links="links" />
    </div>
</template>

<script setup>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Tag from "primevue/tag";
import moment from "moment";
import { useTagStatus } from "@/composables/useTagStatus";
import InputText from "primevue/inputtext";
import _ from "lodash";
import { Link, router, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import DatePicker from "primevue/datepicker";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import { useRolePermission } from "@/composables/useRolePermission";
import currencyFormat from "@/composables/useCurrencyFormat";

const props = defineProps({
    items: Array,
    links: {
        type: Array,
        default: [],
    },
    listOnly: {
        type: Boolean,
        default: false,
    },
    isAction: {
        type: Boolean,
        default: true,
    },
});

const {
    getSeverityForOrder,
    getSeverityForPayment,
    orderStatus,
    paymentStatus,
} = useTagStatus();
const toast = useToast();

const { hasPermission } = useRolePermission();

const search = ref();
const page = usePage();

const filterModel = ref({
    order_no: null,
    customer: null,
    payment_status: null,
    order_status: null,
});

import useSortingTable from "@/composables/useSortingTable";

const { onSort, sortOrder, sortField } = useSortingTable(
    route("admin.orders.index")
);

const handleFilter = _.debounce((value, key) => {
    search.value = {
        ...search.value,
        [key]: value,
    };

    router.get(
        route("admin.orders.index"),
        { ...search.value, ...dates.value },
        {
            preserveState: true,
            preserveScroll: true,
            only: ["orders"],
            onError: (error) => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: `${error.end_date}`,
                    life: 3000,
                });
            },
        }
    );
}, 300);

const dates = ref(
    {
        start_date: null,
    },
    {
        end_date: null,
    }
);

const filterByDate = () => {
    router.get(route("admin.orders.index"), dates.value, {
        preserveState: true,
        preserveScroll: true,
        only: ["orders"],
        onError: (error) => {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: `${error.end_date}`,
                life: 3000,
            });
        },
    });
};

// const onSort = (event) => {
//     sortOrder.value = event.sortOrder;
//     sortField.value = event.sortField;

//     var sort_by;
//     switch (event.sortOrder) {
//         case 1:
//             sort_by = "asc";
//             break;
//         case -1:
//             sort_by = "desc";
//             break;
//         default:
//             sort_by = null;
//             break;
//     }

//     if (event.sortOrder && event.sortField) {
//         _.delay(() => {
//             router.get(
//                 route("admin.orders.index"),
//                 {
//                     sort_by: sort_by,
//                     column: event.sortField,
//                 },
//                 {
//                     preserveState: true,
//                 }
//             );
//         }, 300);
//     }
// };

const clearDate = () => {
    dates.value.start_date = null;
    dates.value.end_date = null;
    filterModel.value.order_no = null;
    filterModel.value.customer = null;
    filterModel.value.order_status = null;
    filterModel.value.payment_status = null;
    router.get(route("admin.orders.index"));
};

onMounted(() => {
    filterModel.value.order_no = route().params.order_no;
    filterModel.value.customer = route().params.customer;
    filterModel.value.order_status = route().params.order_status;
    filterModel.value.payment_status = route().params.payment_status;
    dates.value.start_date = route().params.start_date;
    dates.value.end_date = route().params.end_date;
});
</script>

<style lang="scss" scoped></style>
