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
                <InputLabel for="permission" value="Permission" />
                <MultiSelect
                    id="type"
                    v-model="form.permissions"
                    :options="permissions"
                    optionLabel="name"
                    optionValue="id"
                    placeholder="Select Permission"
                    showClear
                    class="w-full"
                    filter
                />
                <InputError :message="form.errors.permissions" />
            </div>
        </form>
    </div>
    <FormButton label="roles" @submit="handleSubmit" />
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import MultiSelect from "primevue/multiselect";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";
const toast = useToast();
const props = defineProps({
    permissions: Array,
    editValue: {
        default: null,
    },
});

const form = useForm({
    name: "",
    permissions: [],
});

const { onFormSubmit } = useFormSubmit(form, "roles", false);

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Role ${
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
        form.permissions = props.editValue.permissions.map(function (el) {
            return el.id;
        });
    }
});
</script>

<style lang="scss" scoped></style>
