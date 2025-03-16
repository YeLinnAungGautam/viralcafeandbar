<template>
    <div
        class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-"
    >
        <form @submit.prevent="handleSubmit">
            <div class="mb-5">
                <InputLabel for="name" value="Bank name" />
                <InputText
                    id="name"
                    class="w-full"
                    type="text"
                    v-model="form.name"
                    autocomplete="off"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div class="mb-5">
                <InputLabel value="Status" />
                <PSelect
                    :options="activeOptions"
                    optionLabel="label"
                    optionValue="key"
                    filter
                    class="w-full"
                    v-model="form.status"
                />
            </div>
            <div class="mb-5">
                <InputLabel value="Image" />
                <UploadFIle :is-edit="editValue" v-model="form.upload" />
            </div>
        </form>
    </div>
    <FormButton label="payment-infos" @submit="handleSubmit" />
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";
import UploadFIle from "@/Components/UploadFIle.vue";
import { useActiveOption } from "@/composables/useActiveOption";

const toast = useToast();

const props = defineProps({
    editValue: {
        default: null,
    },
});

const form = useForm({
    name: "",
    upload: [],
    status: "active",
});

const { onFormSubmit } = useFormSubmit(form, "payment-infos", false);
const { activeOptions } = useActiveOption();

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Payment Information ${
                    !props.editValue ? "create" : "update"
                } successful.`,
                life: 3000,
            });
        },
    };
    onFormSubmit(options, props.editValue);
};

onMounted(() => {
    if (props.editValue != null) {
        form.name = props.editValue.name;
        form.status = props.editValue.status;
        form.upload = props.editValue.upload;
    }
});
</script>

<style lang="scss" scoped></style>
