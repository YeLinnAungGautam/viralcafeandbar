<template>
    <div
        class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-"
    >
        <form @submit.prevent="handleSubmit">
            <div class="mb-5 w-full">
                <InputLabel for="title" value="Title" />
                <InputText
                    id="title"
                    class="w-full"
                    type="text"
                    v-model="form.title"
                    autocomplete="off"
                />

                <InputError :message="form.errors.title" />
            </div>
            <div class="mb-5">
                <InputLabel for="content" value="Content" />
                <Editor
                    id="content"
                    class="w-full"
                    type="text"
                    v-model="form.content"
                    autocomplete="off"
                />
                <InputError :message="form.errors.content" />
            </div>
            <div class="mb-5">
                <InputLabel for="content" value="Placeholder" />
                <div>{code} , {customer} , {order_code} , {date}</div>
            </div>
        </form>
    </div>
    <FormButton label="templates" @submit="handleSubmit" />
</template>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";

import Editor from "@/Components/Editor.vue";
import useFormSubmit from "@/composables/useFormSubmit";

const toast = useToast();
const page = usePage();
var { languages } = page.props;

const props = defineProps({
    templates: Array,
    editValue: {
        default: null,
    },
});

const form = useForm({
    title: "",
    content: "",
});

const { onFormSubmit } = useFormSubmit(form, "templates", false);

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Template ${
                    !props.editValue ? "create" : "update"
                } successful.`,
                life: 3000,
            });
        },
    };
    onFormSubmit(options, props.editValue);
};
const handleAddLanguages = () => {
    languages.forEach((element) => {
        const exists = form.translates.some(
            (item) => item?.label == element.code
        );

        if (!exists) {
            form.translates.push({
                label: element.code,
                language_id: element.id,
                name: null,
            });
        }
    });
};

onMounted(() => {
    if (props.editValue != null) {
        form.title = props.editValue.title;
        form.content = props.editValue.content;
    }
});
</script>

<style lang="scss" scoped></style>
