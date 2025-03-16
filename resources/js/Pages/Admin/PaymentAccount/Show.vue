<template>
    <Dashboard title="Show Payment Account">
        <BreadcrumbDefault page-title="Payment Account Details" />
        <DetailButton
            @changes="(sw) => (switchLanguage = sw)"
            :label="'payment-accounts'"
            :data="paymentaccount"
        />

        <div
            class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
        >
            <h4 class="text-base mb-3 font-bold text-black">
                Payment Account Details
            </h4>
            <table class="w-full text-left">
                <tbody class="text-gray-900">
                    <tr>
                        <th scope="col" class="py-3 px-2">Account name</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2">
                            {{ paymentaccount.account_name }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3 px-2">Account number</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2">
                            {{ paymentaccount.account_number }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3 px-2">Bank name</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2">
                            {{ paymentaccount.banks.name }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3 px-2">Status</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3 px-2">
                            <Tag
                                class="capitalize"
                                :value="paymentaccount.status"
                                :severity="getSeverity(paymentaccount.status)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Link :href="route(`admin.payment-accounts.index`)">
            <Button label="Back To List" size="small" severity="contrast" />
        </Link>
    </Dashboard>
</template>

<script setup>
import Image from "primevue/image";
import { ref } from "vue";
import DetailButton from "@/Components/Layouts/DetailButton.vue";
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
import Tag from "primevue/tag";
import { useServerity } from "@/composables/useServerity";
const switchLanguage = ref("en");

const { getSeverity } = useServerity();

defineProps({
    paymentaccount: Object,
});
</script>

<style lang="scss" scoped></style>
