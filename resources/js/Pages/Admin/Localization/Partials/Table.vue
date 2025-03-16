<template>
    <div class="mt-5">
        <SearchBox
            :label="'localizations'"
            :permission="'localization_create'"
            @search="
                (searchFunction) => {
                    searchFunction();
                }
            "
        />
        <DataTable
            :value="items"
            tableStyle="min-width: 50rem"
            editMode="row"
            :dataKey="items.key"
            v-model:editingRows="editingRows"
            @row-edit-save="onRowEditSave"
            removableSort
            @sort="onSort"
        >
            <Column field="key" sortable header="Key">
                <template #body="{ data }"> {{ data.key }} </template>
            </Column>
            <Column
                v-for="field in languages"
                :key="field.id"
                :header="field.name"
            >
                <template #body="{ data }"
                    >{{
                        data.records.find((d) => d.language_id === field.id)
                            .value
                    }}
                </template>
                <template #editor="{ data }">
                    <InputText
                        @keyup.enter="onKeyEnter(data)"
                        autocomplete="off"
                        class="w-full"
                        v-model="
                            data.records.find((d) => d.language_id === field.id)
                                .value
                        "
                        size="small"
                    />
                </template>
            </Column>
            <Column
                v-show="hasPermission('localization_edit')"
                :rowEditor="true"
                header="Edit"
            >
                <template #roweditoriniticon>
                    <button
                        class="w-8 h-8 inline-flex justify-center items-center bg-slate-200 rounded-full"
                    >
                        <i class="fa-solid fa-pencil text-sm"></i>
                    </button>
                </template>
            </Column>

            <Column
                v-show="hasPermission('localization_delete')"
                header="Delete"
                class="relative"
            >
                <template #body="{ data }">
                    <div class="flex gap-x-2">
                        <button
                            @click.prevent="
                                () => {
                                    open = true;
                                    deleteData = data.records;
                                }
                            "
                            class="w-8 h-8 inline-flex justify-center items-center bg-slate-200 rounded-full"
                        >
                            <i class="fa-solid fa-trash-alt text-sm"></i>
                        </button>
                    </div>
                </template>
            </Column>

            <template #empty>
                <p class="text-center">No records</p>
            </template>
        </DataTable>
    </div>
    <Dialog
        v-model:visible="open"
        modal
        header="Confirmation"
        :style="{ width: '300px' }"
        id="form-header"
    >
        <h3 class="px-2 text-[15px]">Are you sure to delete !</h3>
        <div class="card mt-5 flex flex-wrap gap-2 justify-end">
            <Button
                label="Cancel"
                size="small"
                icon="pi pi-times"
                severity="secondary"
                @click.prevent="
                    () => {
                        open = false;
                    }
                "
            ></Button>
            <Button
                label="Confirm"
                size="small"
                icon="pi pi-check"
                severity="danger"
                @click.prevent="
                    () => {
                        handleDelete(deleteData);
                    }
                "
            ></Button>
        </div>
    </Dialog>
    <Pagination :links="links" />
</template>

<script setup>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import SearchBox from "@/Components/Layouts/SearchBox.vue";
import { router, usePage } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { ref } from "vue";
import { useRolePermission } from "@/composables/useRolePermission";
import Dialog from "primevue/dialog";
import { onClickOutside } from "@vueuse/core";

defineProps({
    items: Array,
    links: Array,
});
const page = usePage();
var { languages } = page.props;
const toast = useToast();
const editingRows = ref([]);
const { hasPermission } = useRolePermission();
const open = ref(false);
const deleteData = ref();
const handleDelete = (options) => {
    router.post(
        route("admin.localizations.handle-delete"),
        { data: options },
        {
            onSuccess: () => {
                console.log("success");
                toast.add({
                    severity: "success",
                    summary: "Success",
                    detail: "Deleted successful.",
                    life: 3000,
                });
                open.value = false;
            },
        }
    );
};

const onRowEditSave = (data) => {
    handleUpdate(data.newData.records);
};
const onKeyEnter = (data) => {
    handleUpdate(data.records);
};
const handleUpdate = (options) => {
    router.post(
        route("admin.localizations.handle-update"),
        { data: options },
        {
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "Success",
                    detail: "Updated successful.",
                    life: 3000,
                });
            },
            onError: () => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: "All fields are required!.",
                    life: 3000,
                });
            },
        }
    );
};

import useSortingTable from "@/composables/useSortingTable";

const { onSort, sortOrder, sortField } = useSortingTable(
    route("admin.localizations.index")
);

// const sortOrder = ref();
// const onSort = (data) => {
//     switch (data.sortOrder) {
//         case 1:
//             sortOrder.value = "asc";
//             break;
//         case -1:
//             sortOrder.value = "desc";
//             break;
//         default:
//             sortOrder.value = null;
//             break;
//     }

//     router.get(
//         route("admin.localizations.index"),
//         { sortBy: sortOrder.value },
//         {
//             preserveState: true,
//             preserveScroll: true,
//             only: ["localizations"],
//         }
//     );
// };
onClickOutside(open, () => {
    open.value = false;
});
</script>

<style lang="scss" scoped></style>
