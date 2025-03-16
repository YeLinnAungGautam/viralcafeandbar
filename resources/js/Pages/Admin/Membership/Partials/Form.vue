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
                <InputLabel for="rule" value="Rule" />
                <Editor
                    id="rule"
                    class="w-full"
                    type="text"
                    v-model="form.rule"
                    autocomplete="off"
                />
                <InputError :message="form.errors.rule" />
            </div>
            <div class="mb-5">
                <InputLabel for="min_point" value="Min point" />
                <InputText
                    id="min_point"
                    class="w-full"
                    type="text"
                    v-model="form.min_point"
                    autocomplete="off"
                />
                <InputError :message="form.errors.min_point" />
            </div>
            <div class="mb-5">
                <InputLabel for="max_point" value="Max point" />
                <InputText
                    id="max_point"
                    class="w-full"
                    type="text"
                    v-model="form.max_point"
                    autocomplete="off"
                />
                <InputError :message="form.errors.max_point" />
            </div>
            <div class="mb-5">
                <InputLabel for="pecentage" value="Percentage" />
                <InputText
                    id="pecentage"
                    class="w-full"
                    type="text"
                    v-model="form.percentage"
                    autocomplete="off"
                />
                <InputError :message="form.errors.percentage" />
            </div>

            <div class="mb-5">
                <InputLabel value="Staus" />
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
    <FormButton label="memberships" @submit="handleSubmit" />
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";
import Editor from "@/Components/Editor.vue";
import { useActiveOption } from "@/composables/useActiveOption";

const toast = useToast();

const props = defineProps({
    memberships: Array,
    editValue: {
        default: null,
    },
});

const form = useForm({
    name: "",
    rule: "",
    min_point: "",
    max_point: "",
    percentage: "",
    status: "active",
});

const { onFormSubmit } = useFormSubmit(form, "memberships", false);
const { activeOptions } = useActiveOption();

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Membership ${
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
        form.rule = props.editValue.rule;
        form.max_point = props.editValue.max_point;
        form.min_point = props.editValue.min_point;
        form.percentage = props.editValue.percentage;
        form.status = props.editValue.status;
    }
});
</script>

<style lang="scss" scoped></style>
