<script setup>
import Dashboard from "@/Layouts/Dashboard.vue";
import DashboardCard from "@/Components/DashboardCard.vue";
import moment from "moment";
import _ from "lodash";
import { router } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import Table from "./Order/Partials/Table.vue";
import Chart from "primevue/chart";

const props = defineProps({
    dailyChart: Object,
    topProducts: Object,
    topCustomers: Object,
    productCount: [String, Number],
    customerCount: [String, Number],
    monthOrderConfirmCount: [String, Number],
    monthOrderPendingCount: [String, Number],
    recentOrders: Object,
});

const dates = ref([]);
const chartData = ref();
const chartOptions = ref();

onMounted(() => {
    dates.value = [];
    if (route().params.dates) {
        const parsedDates = route().params.dates.map((date) => {
            const parsedDate = moment(date, "YYYY-MM-DD", true);
            return parsedDate.isValid() ? parsedDate.toDate() : null;
        });

        // Filter out invalid dates
        dates.value = parsedDates.filter((date) => date !== null);

        if (dates.value.length === 0) {
            const startDate = new Date();
            const endDate = new Date();
            startDate.setDate(endDate.getDate() - 15);

            dates.value = [startDate, endDate];
        }
    } else {
        const startDate = new Date();
        const endDate = new Date();
        startDate.setDate(endDate.getDate() - 15);

        dates.value = [startDate, endDate];
    }

    dates.value.start_date = route().params.start_date;
    dates.value.end_date = route().params.end_date;

    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});

const setChartData = () => {
    const dates = props.dailyChart[1];
    const totals = props.dailyChart[0];

    return {
        labels: dates,
        datasets: [
            {
                label: "Total Sales",
                data: totals,
                fill: false,
                backgroundColor: "#A48044",
                tension: 0.4,
            },
        ],
    };
};

const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue("--p-text-color");

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: {
            tooltips: {
                mode: "index",
                intersect: false,
            },
            legend: {
                labels: {
                    color: textColor,
                },
            },
        },
    };
};

const formatNumber = (value) => {
    if (value == null) {
        return "0";
    }
    let convertNumber = value * 1;
    return convertNumber.toLocaleString("en-US");
};

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

const handleDate = (modelData) => {
    dates.value = modelData;

    const formattedDates = dates.value.map((d) =>
        d ? d.toISOString().split("T")[0] : null
    );

    router.get(
        route("dashboard"),
        { dates: formattedDates },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: function () {
                chartData.value = setChartData();
                chartOptions.value = setChartOptions();
            },
        }
    );
};

const presetDates = ref([
    { label: "Today", value: [moment().toDate(), moment().toDate()] },
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
</script>

<template>
    <Dashboard title="HOME">
        <div class="grid grid-cols-4 gap-5 mb-8">
            <DashboardCard
                text="Total"
                subText="Products"
                :amount="productCount"
            />
            <DashboardCard
                text="Total"
                subText="Customers"
                :amount="customerCount"
            />
            <DashboardCard
                text="Total"
                subText="Confirmed Orders"
                subTextFollow="(This Month)"
                :amount="monthOrderConfirmCount"
            />
            <DashboardCard
                text="Total"
                subText="Pending Orders"
                subTextFollow="(This Month)"
                :amount="monthOrderPendingCount"
            />
        </div>

        <div class="mb-5 w-full flex justify-end">
            <div class="w-[300px] text-right">
                <div>
                    <VueDatePicker
                        v-model="dates"
                        range
                        :preset-dates="presetDates"
                        :format="formatDate"
                        :enable-time-picker="false"
                        @update:model-value="handleDate"
                        multi-calendars
                    >
                        <template
                            #preset-date-range-button="{
                                label,
                                value,
                                presetDate,
                            }"
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
            </div>
        </div>

        <div>
            <div class="bg-white p-5 rounded-sm">
                <!-- <apexchart
                    ref="myChartComponent"
                    :width="dailyChart.width"
                    :height="dailyChart.height"
                    :type="dailyChart.type"
                    :options="dailyChart.options"
                    :series="dailyChart.series"
                ></apexchart> -->
                <Chart
                    type="bar"
                    :data="chartData"
                    :options="chartOptions"
                    class="h-[30em]"
                />
            </div>
        </div>

        <div class="grid grid-cols-2 mt-5 gap-4">
            <div class="bg-white p-4 rounded-sm">
                <h3 class="mb-5 font-semibold">Top Products</h3>
                <div class="overflow-x-auto rounded-lg bg-slate-100">
                    <table
                        class="w-full text-sm text-left rtl:text-right text-gray-500"
                    >
                        <thead
                            class="text-xs uppercase bg-[#9d6d00] text-white"
                        >
                            <tr>
                                <th class="px-6 py-3">Products</th>
                                <th class="px-6 py-3 text-end">Total Order</th>
                            </tr>
                        </thead>
                        <tr
                            v-if="topProducts.length > 0"
                            v-for="(d, index) in topProducts"
                            :key="d.product_id"
                            class="border-b border-slate-300 last:border-none"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ d.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-end">
                                {{ d.product_count }}
                            </td>
                        </tr>
                    </table>
                    <div
                        v-if="topProducts.length <= 0"
                        class="mt-4 mb-5 text-center text-slate-500"
                    >
                        Not Found
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-sm">
                <h3 class="mb-5 font-semibold">Top Customers</h3>
                <div class="overflow-x-auto rounded-lg bg-slate-100">
                    <table
                        class="w-full text-sm text-left rtl:text-right text-gray-500"
                    >
                        <thead
                            class="text-xs uppercase bg-[#9d6d00] text-white"
                        >
                            <tr>
                                <th class="px-6 py-3">Customer Name</th>
                                <th class="px-6 py-3 text-end">
                                    Total Purchase
                                </th>
                            </tr>
                        </thead>
                        <tr
                            v-if="topCustomers.length > 0"
                            v-for="(d, index) in topCustomers"
                            :key="d.user_id"
                            class="border-b border-slate-300 last:border-none"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{
                                    d.order_customer?.first_name +
                                    " " +
                                    d.order_customer?.last_name
                                }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-end">
                                {{ formatNumber(d.total_spent) }} Ks
                            </td>
                        </tr>
                    </table>
                    <div
                        v-if="topCustomers.length <= 0"
                        class="mt-4 mb-5 text-center text-slate-500"
                    >
                        Not Found
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-sm mt-5">
            <h3 class="mb-5 font-semibold">Recent Orders</h3>
            <Table :items="recentOrders" :list-only="true" />
        </div>
    </Dashboard>
</template>
