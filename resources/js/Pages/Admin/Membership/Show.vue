<template>
    <Dashboard title="Show Membership">
        <BreadcrumbDefault page-title="Membership Details" />
        <DetailButton :label="'memberships'" :data="membership" />
        <div
            class="w-full block px-4 p-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
        >
            <h4 class="text-base mb-3 font-bold text-black">
                Membership Details
            </h4>
            <table class="w-full text-left">
                <tbody class="text-gray-900">
                    <tr>
                        <th scope="col" class="py-3">Name</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ membership.name }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Min point</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ membership.min_point }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Max point</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ membership.max_point }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Percentage</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            {{ membership.percentage }}%
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Status</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            <Tag
                                class="capitalize"
                                :value="membership.status"
                                :severity="getSeverity(membership.status)"
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="py-3">Rule</th>
                        <td scope="col" class="py-2 w-10">-</td>
                        <td scope="col" class="py-3">
                            <div v-html="membership.rule"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Link :href="route(`admin.memberships.index`)">
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
defineProps({
    membership: Object,
});

const { getSeverity } = useServerity();
</script>

<style lang="scss" scoped></style>
