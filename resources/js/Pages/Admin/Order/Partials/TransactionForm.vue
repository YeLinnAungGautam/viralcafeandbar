<template>
    <form @submit.prevent="handleSubmit">
        <div class="mb-5">
            <InputLabel for="payment_date" value="Payment date" />
            <!-- <DatePicker
                id="payment_date"
                class="w-full"
                autocomplete="off"
                v-model="form.payment_date"
            /> -->
            <VueDatePicker v-model="form.payment_date" :format="format">
            </VueDatePicker>
            <InputError :message="form.errors.payment_date" />
        </div>
        <div class="mb-5">
            <InputLabel for="key" value="Amount" />
            <InputText
                id="key"
                v-model="form.amount"
                class="w-full"
                type="number"
                autocomplete="off"
            />
            <InputError :message="form.errors.amount" />
        </div>
        <div class="mb-5">
            <InputLabel for="method" value="Payment method" />
            <PSelect
                placeholder="Choose payment method"
                v-model="form.payment_method"
                :options="methods"
                optionLabel="title"
                optionValue="id"
                class="w-full"
            />
            <InputError :message="form.errors.payment_method" />
        </div>
        <div class="mb-5">
            <InputLabel for="noted" value="Notes (Optional)" />
            <InputText
                v-model="form.noted"
                id="noted"
                class="w-full"
                type="text"
                autocomplete="off"
            />
            <InputError :message="form.errors.noted" />
        </div>
        <div class="mb-5">
            <InputLabel for="upload" value="Upload" />
            <upload-file v-model="form.upload" :multiple="false" />
        </div>
        <Button type="submit" label="Submit" size="small" severity="contrast" />
    </form>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import UploadFile from "@/Components/UploadFIle.vue";
import { onMounted, watch } from "vue";
import moment from "moment";

const toast = useToast();

const props = defineProps({
    editValue: {
        default: null,
    },
    methods: Array,
    order: Object,
    totalPaid: {
        default: 0,
    },
});

const emits = defineEmits(["success"]);

const form = useForm({
    order_id: props.order.id,
    customer_id: props.order.customer_id,
    payment_date: new Date(),
    amount: props.order.total_price - props.totalPaid,
    payment_status: "paid",
    payment_method: "",
    noted: "",
    upload: [],
});

const format = (date) => {
    return moment(date).format("DD/MM/YYYY");
};

const { onFormSubmit } = useFormSubmit(form, "transactions", true);

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Transaction ${
                    !props.editValue ? "create" : "update"
                } successful.`,
                life: 3000,
            });
            form.reset();
            emits("success", false);
        },
        onError: (errors) => {
            form.errors = errors;
        },
    };

    onFormSubmit(options, props.editValue);
};

onMounted(() => {
    if (props.editValue) {
        form.payment_date = props.editValue.payment_date;
        form.amount = props.editValue.amount;
        form.payment_status = "paid";
        form.noted = props.editValue.note;
        form.payment_method = props.editValue.payment_method;
        form.upload = props.editValue.upload;
    } else {
        form.reset();
    }
    if (props.order) {
        form.payment_method = props.order.payment_method;
    }
});
</script>

<style lang="scss" scoped></style>
