<template>
    <div class="mt-5">
        <SearchBox
            :label="'notifications'"
            :permission="'promotion_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column header="Image">
                <template #body="{ data }">
                    <!-- <img :src="data.upload?.at(-1)?.thumbnail" alt="" /> -->
                    <Image
                        :src="data.upload?.at(-1)?.url"
                        width="50"
                        :preview="data.upload?.at(-1)?.url"
                    />
                </template>
            </Column>
            <Column field="name" header="Name">
                <template #body="{ data }">
                    <div class="capitalize">
                        {{ data.name }}
                    </div>
                </template></Column
            >
            <Column header="Status">
                <template #body="{ data }">
                    <Tag
                        class="capitalize"
                        :value="data.status"
                        :severity="getSeverity(data.status)"
                    />
                </template>
            </Column>

            <Column header="Action" class="relative">
                <template #body="{ data }">
                    <TableActions
                        :data="data"
                        route-name="notifications"
                        :is-delete="hasPermission('promotion_delete')"
                        :is-show="hasPermission('promotion_show')"
                        :is-edit="hasPermission('promotion_edit')"
                    >
                        <!-- <template #other>
                            <button
                                @click="handleSendNotification(data)"
                                class="action-btn"
                            >
                                <i class="fa fa-bell"></i>
                            </button>
                        </template> -->
                    </TableActions>
                </template>
            </Column>
            <template #empty>
                <p class="text-center">No records</p>
            </template>
        </DataTable>
    </div>
    <Pagination :links="links" />
</template>

<script setup>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Tag from "primevue/tag";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
const page = usePage();
var { languages, api_token } = page.props;

import { useServerity } from "@/composables/useServerity";
import SearchBox from "@/Components/Layouts/SearchBox.vue";
import { data } from "autoprefixer";
import { useToast } from "primevue/usetoast";
import { useRolePermission } from "@/composables/useRolePermission";
import Image from "primevue/image";

const toast = useToast();

const { getSeverity } = useServerity();
const { hasPermission } = useRolePermission();

// const handleSendNotification = async (promo) => {
//     const data = await axios(route("promotions.send-notify"), {
//         headers: {
//             "API-TOKEN": api_token,
//         },
//         method: "POST",
//         data: {
//             title: promo.name,
//             body: promo.short_description,
//             id: promo.id,
//         },
//     });

//     toast.add({
//         severity: "success",
//         summary: "Success",
//         detail: "Notification push successful.",
//         life: 3000,
//     });
// };

defineProps({
    items: Array,
    links: Array,
});
</script>

<style lang="scss" scoped></style>
