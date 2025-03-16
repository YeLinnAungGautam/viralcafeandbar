<template>
    <div
        class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
    >
        <form @submit.prevent="handleSubmit">
            <div class="mb-4">
                <InputLabel for="name" value="Country" />
                <PSelect
                    @change="handleChangeSelect"
                    :options="dia"
                    optionLabel="name"
                    filter
                    class="w-full"
                    placeholder="Choose Country"
                    v-model="selected"
                />
                <InputError :message="form.errors.name" />
            </div>
            <div class="mb-4">
                <InputLabel for="code" value="Code" />
                <InputText
                    class="w-full"
                    v-model="form.code"
                    :disabled="true"
                />
                <InputError :message="form.errors.code" />
            </div>
            <div class="mb-4">
                <InputLabel value="Active" />
                <PSelect
                    :options="optionsActive"
                    optionLabel="name"
                    optionValue="key"
                    filter
                    class="w-full"
                    v-model="form.active"
                    placeholder="Choose active status"
                />
            </div>
            <div class="mb-4">
                <InputLabel value="Default" />
                <PSelect
                    :options="activeOptions"
                    optionLabel="label"
                    optionValue="key"
                    filter
                    class="w-full"
                    v-model="form.is_default"
                />
            </div>
        </form>
    </div>
    <FormButton label="languages" @submit="handleSubmit" />
</template>

<script setup>
import dia from "@/assets/dia.json";
import useFormSubmit from "@/composables/useFormSubmit";
import { useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { onMounted, ref } from "vue";

const props = defineProps({
    categories: Array,
    editValue: {
        default: null,
    },
});

const selected = ref();

const form = useForm({
    name: null,
    code: null,
    dial_code: null,
    active: "active",
    is_default: false,
});

const toast = useToast();

const { onFormSubmit } = useFormSubmit(form, "languages", false);

const optionsActive = ref([
    {
        name: "Active",
        key: "active",
    },
    {
        name: "Draft",
        key: "draft",
    },
]);

const activeOptions = [
    {
        key: true,
        label: "Yes",
    },
    {
        key: false,
        label: "No",
    },
];

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Language ${
                    !props.editValue ? "create" : "update"
                } successful.`,
                life: 3000,
            });
        },
    };
    onFormSubmit(options, props.editValue);
};

const handleChangeSelect = (target) => {
    form.name = target.value.name;
    form.code = target.value.code;
};

onMounted(() => {
    if (props.editValue) {
        selected.value = dia.filter(
            (item) => item.code === props.editValue.code
        )[0];
        form.name = props.editValue.name;
        form.code = props.editValue.code;
        form.active = props.editValue.active;
        form.is_default = props.editValue.is_default ? true : false;
    }
});
</script>

<style lang="scss" scoped></style>
