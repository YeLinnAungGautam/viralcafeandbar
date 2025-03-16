<template>
    <div class="mt-5">
        <!-- <SearchBox
            :label="'banners'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        /> -->
        <DataTable :value="items" tableStyle="min-width: 50rem">
            <Column class="w-30" field="event" header="Action">
                <template #body="{ data }">
                    <Tag
                        class="capitalize"
                        :value="data.event"
                        :severity="getSeverity(data.event)"
                    />
                </template>
            </Column>

            <Column header="Description">
                <template #body="{ data }">
                    {{ data?.description || "---" }}
                </template>
            </Column>
            <Column class="w-30" header="Action By">
                <template #body="{ data }">
                    {{ data.causer?.name || "---" }}
                </template>
            </Column>
            <Column class="w-30" header="Date & time">
                <template #body="{ data }">
                    {{ moment(data.created_at).format("DD/MM/YYYY") }}
                    <span class="block">
                        {{ moment(data.created_at).format("hh:ss A") }}
                    </span>
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
import moment from "moment";
import Tag from "primevue/tag";

defineProps({
    items: Array,
    links: Array,
});
const getSeverity = (serverity) => {
    switch (serverity) {
        case "created":
            return "success";
        case "updated":
            return "primary";
        case "deleted":
            return "danger";
    }
};
</script>

<style lang="scss" scoped></style>
