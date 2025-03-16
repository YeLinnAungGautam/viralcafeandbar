<template>
    <div class="flex gap-x-2">
        <Link
            v-if="isEdit"
            class="w-8 h-8 inline-flex justify-center items-center bg-slate-200 rounded-full"
            :href="route(`admin.${props.routeName}.edit`, data.id)"
        >
            <i class="fa-solid fa-pencil text-sm"></i>
        </Link>
        <Link
            v-if="isShow"
            class="w-8 h-8 inline-flex justify-center items-center bg-slate-200 rounded-full"
            :href="route(`admin.${props.routeName}.show`, data.id)"
        >
            <i class="fa-solid fa-eye text-sm"></i>
        </Link>
        <button
            v-if="isDelete"
            @click.prevent="
                () => {
                    open = true;
                }
            "
            class="w-8 h-8 inline-flex justify-center items-center bg-slate-200 rounded-full"
        >
            <i class="fa-solid fa-trash-alt text-sm"></i>
        </button>
        <slot name="other"></slot>
    </div>
    <Dialog
        v-model:visible="open"
        modal
        header="Confirmation"
        :style="{ width: '300px' }"
        id="form-header"
    >
        <h3 class="px-2 text-[15px]">Are you sure to delete ?</h3>
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
                        handleDelete(data.id);
                    }
                "
            ></Button>
        </div>
    </Dialog>
</template>

<script setup>
const props = defineProps({
    routeName: String,
    link: {
        default: null,
    },
    data: Object,
    isDelete: {
        default: true,
    },
    isShow: {
        default: true,
    },
    isEdit: {
        default: true,
    },
});

import { Link, router } from "@inertiajs/vue3";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import { useToast } from "primevue/usetoast";
import { ref } from "vue";

const toast = useToast();
const open = ref(false);

const handleDelete = (id) => {
    if (open.value) {
        router.delete(route(`admin.${props.routeName}.destroy`, id), {
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "Success",
                    detail: "Deleted successful.",
                    life: 3000,
                });
                open.value = false;
            },
        });
    }
};
</script>

<style lang="scss" scoped></style>
