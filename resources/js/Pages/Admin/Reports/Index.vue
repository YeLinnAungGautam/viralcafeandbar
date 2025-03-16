<script setup>
import { ref, onMounted } from "vue";
import Table from "../Order/Partials/Table.vue";
import { router, usePage } from "@inertiajs/vue3";
import moment from "moment";
import { useTagStatus } from "@/composables/useTagStatus";
import Tag from "primevue/tag";
import SplitButton from "primevue/splitbutton";

const props = defineProps({
    results: Object,
    customer_name: String,
    order_no: String,
});

const page = usePage();
const customer_name = ref(props.customer_name || "");
const order_no = ref(props.order_no || "");
const order_status = ref(props.order_status || "");
const payment_status = ref(props.payment_status || "");
const dates = ref([]);

// const dates = ref(
//     {
//         start_date: null,
//     },
//     {
//         end_date: null,
//     }
// );

onMounted(() => {
    dates.value = [];
    // if (route().params.dates) {
    //     const parsedDates = route().params.dates.map((date) => {
    //         const parsedDate = moment(date, "YYYY-MM-DD", true);
    //         return parsedDate.isValid() ? parsedDate.toDate() : null;
    //     });

    //     // Filter out invalid dates
    //     dates.value = parsedDates.filter((date) => date !== null);

    //     if (dates.value.length === 0) {
    //         const startDate = new Date();
    //         const endDate = new Date();
    //         startDate.setDate(endDate.getDate() - 15);

    //         dates.value = [startDate, endDate];
    //     }
    // } else {
    //     const startDate = new Date();
    //     const endDate = new Date();
    //     startDate.setDate(endDate.getDate() - 15);

    //     dates.value = [startDate, endDate];
    // }

    dates.value.start_date = route().params.start_date;
    dates.value.end_date = route().params.end_date;

    // dates.value.start_date = route().params.start_date;
    // dates.value.end_date = route().params.end_date;
});

const formatDate = (dates) => {
    const months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ];

    // if (!dates || dates.length !== 2 || !dates[0] || !dates[1]) {
    //     return "";
    // }

    if (!dates || !dates[0]) {
        return "";
    }

    const day1 = dates[0].getDate();
    const month1 = months[dates[0].getMonth()];
    const year1 = dates[0].getFullYear();

    const day2 = dates[1] ? dates[1].getDate() : "";
    const month2 = dates[1] ? months[dates[1].getMonth()] : "";
    const year2 = dates[1] ? dates[1].getFullYear() : "";

    let result = `${day1}-${month1}-${year1}`;
    if (day2 && month2 && year2) {
        result += ` - ${day2}-${month2}-${year2}`;
    }
    return result;
};

const presetDates = ref([
    {
        label: "Today",
        value: [moment().toDate(), moment().toDate()],
    },
    {
        label: "Yesterday",
        value: [
            moment().subtract(1, "days").toDate(),
            moment().subtract(1, "days").toDate(),
        ],
    },
    {
        label: "This week",
        value: [
            moment().startOf("week").toDate(),
            moment().endOf("week").toDate(),
        ],
    },
    {
        label: "Last week",
        value: [
            moment().subtract(1, "weeks").startOf("week").toDate(),
            moment().subtract(1, "weeks").endOf("week").toDate(),
        ],
    },
    {
        label: "This month",
        value: [
            moment().startOf("month").toDate(),
            moment().endOf("month").toDate(),
        ],
    },
    {
        label: "Last month",
        value: [
            moment().subtract(1, "months").startOf("month").toDate(),
            moment().subtract(1, "months").endOf("month").toDate(),
        ],
    },
]);

const filter = () => {
    let formattedDates = null;
    if (dates.value?.length) {
        formattedDates = dates.value.map((d) =>
            d ? d.toISOString().split("T")[0] : null
        );
    }

    router.get(
        route("admin.reportings.index"),
        {
            customer_name: customer_name.value,
            order_no: order_no.value,
            order_status: order_status.value,
            payment_status: payment_status.value,
            // start_date: dates.value.start_date,
            // end_date: dates.value.end_date,
            dates: formattedDates,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const handlePageChange = (url) => {
    router.get(
        url,
        {
            customer_name: customer_name.value,
            order_no: order_no.value,
            order_status: order_status.value,
            payment_status: payment_status.value,
            start_date: dates.value.start_date,
            end_date: dates.value.end_date,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const exportData = async () => {
    let formattedDates = null;
    if (dates.value?.length) {
        formattedDates = dates.value.map((d) =>
            d ? d.toISOString().split("T")[0] : null
        );
    }

    try {
        if (
            customer_name.value ||
            order_no.value ||
            order_status.value ||
            payment_status.value ||
            dates.value
        ) {
            const response = await axios.get("order-export", {
                params: {
                    customer_name: customer_name.value,
                    order_no: order_no.value,
                    order_status: order_status.value,
                    payment_status: payment_status.value,
                    // start_date: dates.value.start_date,
                    // end_date: dates.value.end_date,
                    dates: formattedDates,
                },
                responseType: "blob",
            });

            const now = new Date();
            const day = now.getDate();
            const month = now.toLocaleString("default", { month: "short" });
            const year = now.getFullYear();

            const fileName = `order-${day}-${month}-${year}.xlsx`;

            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", fileName);
            document.body.appendChild(link);
            link.click();
            link.remove();
        }
    } catch (error) {
        console.error("There was an error downloading the Excel file:", error);
    }
};

const {
    getSeverityForOrder,
    getSeverityForPayment,
    orderStatus,
    paymentStatus,
} = useTagStatus();

const orderReport = async (type) => {
    try {
        const response = await axios.get(`order-report/${type}`, {
            responseType: "blob",
        });

        const now = new Date();
        const day = now.getDate();
        const month = now.toLocaleString("default", { month: "short" });
        const year = now.getFullYear();

        const fileName = `order-${day}-${month}-${year}.xlsx`;

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", fileName);
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (error) {
        console.error("There was an error downloading the Excel file:", error);
    }
};

// const items = [
//     {
//         label: "Daily",
//         command: () => {
//             orderReport("daily");
//         },
//     },
//     {
//         label: "Weekly",
//         command: () => {
//             orderReport("weekly");
//         },
//     },
//     {
//         label: "Monthly",
//         command: () => {
//             orderReport("monthly");
//         },
//     },
// ];
</script>

<template>
    <Dashboard title="Reporting">
        <div class="grid grid-cols-4 gap-4">
            <div>
                <InputLabel for="customer_name" value="Customer Name" />
                <InputText
                    id="customer_name"
                    class="w-full"
                    type="text"
                    v-model="customer_name"
                />
            </div>
            <div>
                <InputLabel for="order_no" value="Order Number" />
                <InputText
                    id="order_no"
                    class="w-full"
                    type="text"
                    v-model="order_no"
                />
            </div>
            <div>
                <InputLabel for="order_status" value="Order Status" />

                <PSelect
                    class="w-full"
                    v-model="order_status"
                    :options="orderStatus"
                    placeholder="Select One"
                    :showClear="true"
                    optionLabel="value"
                    optionValue="key"
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
            </div>
            <div>
                <InputLabel for="payment_status" value="Payment Status" />
                <PSelect
                    class="w-full"
                    v-model="payment_status"
                    :options="paymentStatus"
                    placeholder="Select One"
                    :showClear="true"
                    optionLabel="value"
                    optionValue="key"
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
            </div>
        </div>
        <div class="grid grid-cols-4 gap-4 mt-5">
            <div class="col-span-1">
                <VueDatePicker
                    v-model="dates"
                    range
                    :preset-dates="presetDates"
                    :format="formatDate"
                    :enable-time-picker="false"
                    multi-calendars
                    position="right"
                >
                    <template
                        #preset-date-range-button="{ label, value, presetDate }"
                    >
                        <span
                            role="button"
                            :tabindex="0"
                            @click="presetDate(value)"
                            @keyup.enter.prevent="presetDate(value)"
                            @keyup.space.prevent="presetDate(value)"
                        >
                            {{ label }}
                        </span>
                    </template>
                </VueDatePicker>
            </div>
            <!-- <div class="flex-1">
                <InputLabel for="start_date" value="Order Start Date" />
                <DatePicker
                    class="w-full"
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
            </div>
            <div class="flex-1">
                <InputLabel for="end_date" value="Order End Date" />
                <DatePicker
                    class="w-full"
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
            </div> -->
            <!-- <div class="flex-1">
                <SplitButton
                    class="mt-6 bg-black"
                    label="Download"
                    :model="items"
                ></SplitButton>
            </div> -->
            <div>
                <Button
                    @click.prevent="filter"
                    label="Filter"
                    size="small"
                    severity="contrast"
                    icon="pi pi-search"
                />
            </div>
        </div>
        <!-- <div class="flex justify-end items-center mt-5">
            <Button
                @click.prevent="filter"
                label="Filter"
                size="small"
                severity="contrast"
                icon="pi pi-search"
            />
        </div> -->
        <div v-if="results?.data?.length" class="flex justify-end">
            <button
                type="button"
                class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-black bg-slate-300 border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-2 focus:ring-slate-200"
                @click.prevent="exportData"
            >
                <svg
                    class="w-4 h-4 mr-2"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"
                    ></path>
                </svg>
                Export
            </button>
        </div>
        <Table :items="results.data" :list-only="true" :is-action="false" />
        <div v-if="results?.data?.length">
            <Pagination
                v-if="results.links.length > 0"
                :links="results.links"
                @pagination="handlePageChange"
            />
        </div>
    </Dashboard>
</template>
