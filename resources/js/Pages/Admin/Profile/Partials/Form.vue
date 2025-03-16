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
                    disabled
                />
                <InputError :message="form.errors.email" />
            </div>
            <div class="mb-5">
                <InputLabel for="old_password" value="Old password" />
                <InputText
                    id="old_password"
                    class="w-full"
                    type="password"
                    v-model="form.old_password"
                    autocomplete="off"
                />
                <InputError :message="form.errors.old_password" />
            </div>
            <div class="mb-5">
                <InputLabel for="new_password" value="New password" />
                <InputText
                    id="new_password"
                    class="w-full"
                    type="password"
                    v-model="form.new_password"
                    autocomplete="off"
                />
                <InputError :message="form.errors.new_password" />
            </div>
        </form>
    </div>
    <FormButton label="products" @submit="handleSubmit" />
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";

const toast = useToast();

const props = defineProps({
    profile: {
        default: null,
    },
});

const form = useForm({
    name: "",
    email: "",
    old_password: "",
    new_password: "",
});

const { onFormSubmit } = useFormSubmit(form, "profiles", false);

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: "Profile update successful.",
                life: 3000,
            });
            if (props.profile != null) {
                form.name = props.profile.name;
                form.email = props.profile.email;
                form.old_password = "";
                form.new_password = "";
            }
        },
    };
    onFormSubmit(options, props.profile);
};

onMounted(() => {
    if (props.profile != null) {
        form.name = props.profile.name;
        form.email = props.profile.email;
    }
});
</script>

<style lang="scss" scoped></style>
