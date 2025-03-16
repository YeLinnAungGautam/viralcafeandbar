<template>
    <div
        class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-"
    >
        <form @submit.prevent="handleSubmit">
            <div class="mb-5">
                <InputLabel for="name" value="Name" />
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
                <InputLabel value="Role" />
                <PSelect
                    :options="roles"
                    optionLabel="name"
                    optionValue="name"
                    class="w-full"
                    v-model="form.role"
                />
            </div>
        </form>
    </div>
    <FormButton label="users" @submit="handleSubmit" />
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";

const toast = useToast();

const props = defineProps({
    user: Array,
    roles: Array,
    editValue: {
        default: null,
    },
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    role: "admin",
});

const { onFormSubmit } = useFormSubmit(form, "users", false);

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `User ${
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
        form.email = props.editValue.email;
        form.phone = props.editValue.phone;
        form.role = props.editValue.roles[0].name;
    }
});
</script>

<style lang="scss" scoped></style>
