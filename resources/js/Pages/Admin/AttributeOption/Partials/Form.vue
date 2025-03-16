<template>
    <form @submit.prevent="handleSubmit">
        <div class="mb-5">
            <InputLabel for="attribute" value="Attribute" />
            <PSelect
                id="attribute"
                v-model="form.attribute_id"
                :options="attributes"
                optionLabel="name"
                optionValue="id"
                placeholder="Select Attributes"
                showClear
                display="chip"
                class="w-full"
            />

            <InputError :message="form.errors.name" />
        </div>
        <div class="mb-5">
            <InputLabel> Option Value </InputLabel>
            <div
                v-for="(option, index) in form.options"
                :key="index"
                class="flex justify-between gap-x-3 mb-3"
            >
                <InputText
                    class="w-full"
                    type="text"
                    v-model="option.name"
                    autocomplete="off"
                />
                <button
                    v-if="form.options.length > 1"
                    @click.prevent="handleRemove(index)"
                >
                    <i class="fa fa-trash-alt text-red text-sm"></i>
                </button>
            </div>
            <button
                @click.prevent="handleAddNew"
                class="bg-slate-200 px-3 py-1 rounded-md inline-flex justify-center items-center"
            >
                <i class="fa fa-plus text-xs me-1"></i> Add
            </button>
        </div>
        <Button type="submit" label="Submit" />
    </form>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";
const toast = useToast();
const props = defineProps({
    attributes: Array,
    editValue: {
        default: null,
    },
});

const form = useForm({
    attribute_id: "",
    options: [
        {
            name: "",
        },
    ],
});

const { onFormSubmit } = useFormSubmit(form, "attribute-options", false);

const emits = defineEmits("success");

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Attribute ${
                    !props.editValue ? "create" : "update"
                } successful.`,
                life: 3000,
            });
            form.reset();
            emits("success", true);
        },
    };
    onFormSubmit(options, props.editValue);
};

const handleAddNew = () => {
    form.options.push({
        name: "",
    });
};

const handleRemove = (index) => {
    form.options.splice(index, 1);
};

onMounted(() => {
    if (props.editValue != null) {
        form.name = props.editValue.name;
        form.attribute_id = props.editValue.attribute_id;
    }
});
</script>

<style lang="scss" scoped></style>
