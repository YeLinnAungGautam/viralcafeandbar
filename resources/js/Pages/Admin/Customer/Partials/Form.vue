<template>
    <div
        class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-"
    >
        <form @submit.prevent="handleSubmit">
            <div class="mb-5">
                <InputLabel for="first_name" value="First name" />
                <InputText
                    id="first_name"
                    class="w-full"
                    type="text"
                    v-model="form.first_name"
                    autocomplete="off"
                />
                <InputError :message="form.errors.first_name" />
            </div>
            <div class="mb-5">
                <InputLabel for="last_name" value="Last name" />
                <InputText
                    id="last_name"
                    class="w-full"
                    type="text"
                    v-model="form.last_name"
                    autocomplete="off"
                />
                <InputError :message="form.errors.last_name" />
            </div>
            <div class="mb-5">
                <InputLabel for="username" value="Username" />
                <InputText
                    id="username"
                    class="w-full"
                    type="text"
                    v-model="form.username"
                    autocomplete="off"
                />
                <InputError :message="form.errors.username" />
            </div>
            <div class="mb-5">
                <InputLabel for="email" value="Email" />
                <InputText
                    id="email"
                    class="w-full"
                    type="text"
                    v-model="form.email"
                    autocomplete="off"
                />
                <InputError :message="form.errors.email" />
            </div>
            <div class="mb-5">
                <InputLabel for="password" value="Password" />
                <InputText
                    id="password"
                    class="w-full"
                    type="password"
                    v-model="form.password"
                    autocomplete="off"
                />
                <InputError :message="form.errors.password" />
            </div>
            <div class="mb-5">
                <InputLabel for="phone" value="Phone" />
                <InputText
                    id="phone"
                    class="w-full"
                    type="text"
                    v-model="form.phone"
                    autocomplete="off"
                />
                <InputError :message="form.errors.phone" />
            </div>
            <div class="mb-5">
                <InputLabel for="address" value="Address" />
                <Textarea
                    id="address"
                    class="w-full"
                    type="text"
                    autoResize
                    rows="3"
                    autocomplete="off"
                    v-model="form.meta.address"
                />
            </div>

            <div class="mb-5">
                <InputLabel for="country" value="Country" />
                <InputText
                    id="country"
                    class="w-full"
                    type="text"
                    v-model="form.meta.country"
                    autocomplete="off"
                />
            </div>
            <div class="mb-5">
                <InputLabel for="gender" value="Gender" />
                <PSelect
                    :options="genderOptions"
                    optionLabel="key"
                    optionValue="value"
                    class="w-full"
                    v-model="form.meta.gender"
                />
            </div>
            <div class="mb-5">
                <InputLabel for="dob" value="Date of Birth" />
                <DatePicker
                    placeholder="Date of Birth"
                    :manualInput="false"
                    :showIcon="true"
                    date-format="dd-mm-yy"
                    v-model="form.meta.dob"
                    size="small"
                    class="w-full"
                    :showTime="false"
                    @date-select="
                        (e) => {
                            form.meta.dob = moment(e).format('DD-MM-YYYY');
                        }
                    "
                />
                <InputError :message="form.errors.dob" />
            </div>
            <div class="mb-5">
                <InputLabel value="Status" />
                <PSelect
                    :options="activeOptions"
                    optionLabel="label"
                    optionValue="key"
                    class="w-full"
                    v-model="form.status"
                />
            </div>
        </form>
    </div>
    <FormButton label="customers" @submit="handleSubmit" />
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted, ref } from "vue";
import { useActiveOption } from "@/composables/useActiveOption";
import DatePicker from "primevue/datepicker";
import moment from "moment";
import Textarea from "primevue/textarea";

const toast = useToast();

const props = defineProps({
    customers: Array,
    editValue: {
        default: null,
    },
});

const form = useForm({
    first_name: "",
    last_name: "",
    username: "",
    email: "",
    password: "",
    phone: "",
    status: "active",
    meta: {
        address: "",
        country: "",
        gender: "",
        dob: null,
    },
});

const { onFormSubmit } = useFormSubmit(form, "customers", false);
const { activeOptions } = useActiveOption();
const genderOptions = [
    {
        key: "Male",
        value: "male",
    },
    {
        key: "Female",
        value: "female",
    },
];

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Customer ${
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
        form.first_name = props.editValue.first_name;
        form.last_name = props.editValue.last_name;
        form.username = props.editValue.username;
        form.email = props.editValue.email;
        form.phone = props.editValue.phone;
        form.meta.address = props.editValue.meta?.address || "";
        form.meta.country = props.editValue.meta?.country || "";
        form.meta.gender = props.editValue.meta?.gender || null;
        form.meta.dob = props.editValue.meta?.dob || null;
    }
});
</script>

<style lang="scss" scoped></style>
