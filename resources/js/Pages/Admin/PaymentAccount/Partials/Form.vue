<template>
    <div
        class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-"
    >
        <form @submit.prevent="handleSubmit">
            <div class="mb-5">
                <InputLabel value="Banks" />
                <PSelect
                    :options="banks"
                    optionLabel="name"
                    optionValue="id"
                    filter
                    class="w-full"
                    v-model="form.payment_info_id"
                />
            </div>
            <div class="mb-5">
                <InputLabel for="account_name" value="Account name" />
                <InputText
                    id="account_name"
                    class="w-full"
                    type="text"
                    v-model="form.account_name"
                    autocomplete="off"
                />
                <InputError :message="form.errors.account_name" />
            </div>
            <div class="mb-5">
                <InputLabel for="account_number" value="Account number" />
                <InputText
                    id="account_number"
                    class="w-full"
                    type="text"
                    v-model="form.account_number"
                    autocomplete="off"
                />
                <InputError :message="form.errors.account_number" />
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
        </form>
    </div>
    <FormButton label="payment-accounts" @submit="handleSubmit" />
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
    banks: Array,
    editValue: {
        default: null,
    },
});

const form = useForm({
    account_name: "",
    account_number: null,
    payment_info_id: null,
    status: "active",
});

const { onFormSubmit } = useFormSubmit(form, "payment-accounts", false);
const { activeOptions } = useActiveOption();

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Payment Account ${
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
        form.account_name = props.editValue.account_name;
        form.account_number = props.editValue.account_number;
        form.payment_info_id = props.editValue.banks?.id || null;
        form.status = props.editValue.status;
    }
});
</script>

<style lang="scss" scoped></style>
