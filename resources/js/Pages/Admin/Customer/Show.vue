<template>
    <Dashboard title="Show Customer">
        <BreadcrumbDefault page-title="Customer Details" />
        <DetailButton :label="'customers'" :data="customer" />
        <div
            class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
        >
            <h4 class="text-base mb-3 font-bold text-black">
                Customer Details
            </h4>
            <table class="w-full text-left">
                <tbody class="text-gray-900">
                    <tr>
                        <th scope="col" class="py-3">First Name</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ customer.first_name || "---" }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Last Name</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ customer.last_name || "---" }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">User Name</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ customer.username }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Email</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ customer.email || "---" }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Phone</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ customer.phone || "---" }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Address</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            <!-- {{ customer.meta?.address || "---" }} -->
                            <div
                                class="whitespace-pre"
                                v-html="customer.meta?.address || '---'"
                            ></div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Country</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ customer.meta?.country || "---" }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Gender</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ customer.meta?.gender || "---" }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Date Of Birth</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ customer.meta?.dob || "---" }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Status</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            <Tag
                                class="capitalize"
                                :value="customer.status"
                                :severity="getSeverity(customer.status)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <OrderTable :orders="orders" :point_transactions="point_transactions" />
        <Link :href="route(`admin.customers.index`)">
            <Button label="Back To List" size="small" severity="contrast" />
        </Link>
    </Dashboard>
</template>

<script setup>
import Tag from "primevue/tag";
import Button from "primevue/button";
import { Link } from "@inertiajs/vue3";
import DetailButton from "@/Components/Layouts/DetailButton.vue";
import { useServerity } from "@/composables/useServerity";
import OrderTable from "./Partials/OrderTable.vue";

defineProps({
    customer: Object,
    orders: Array,
    point_transactions: Array,
});

const { getSeverity } = useServerity();
</script>

<style lang="scss" scoped></style>
