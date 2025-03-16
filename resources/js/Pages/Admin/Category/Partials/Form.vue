<template>
    <div
        class="w-full block bg-white mb-4"
        :class="{
            'border border-slate-200 rounded-lg shadow px-4 py-5': props.popup,
        }"
    >
        <form @submit.prevent="handleSubmit">
            <div class="mb-5 w-full">
                <div class="flex gap-2 items-end w-full">
                    <div class="w-full">
                        <InputLabel for="name" value="Name" />
                        <InputText
                            id="name"
                            class="w-full"
                            type="text"
                            v-model="form.name"
                            autocomplete="off"
                            @input.prevent="form.translates[0].name = form.name"
                        />
                    </div>

                    <Button
                        @click="handleAddLanguages"
                        class="text-nowrap"
                        label="Translate"
                        :disabled="form.translates.length == languages.length"
                    />
                </div>
                <InputError :message="form.errors.name" />
            </div>

            <div
                class="mb-5 w-full"
                v-for="(translate, index) in form.translates"
                :key="index"
            >
                <InputGroup v-if="translate.label != 'en'">
                    <InputGroupAddon>
                        <span class="uppercase">
                            {{ translate.label }}
                        </span>
                    </InputGroupAddon>
                    <InputText v-model="translate.name" autocomplete="off" />
                </InputGroup>
            </div>
            <div class="mb-5">
                <InputLabel value="Image" />
                <UploadFile
                    :multiple="false"
                    :is-edit="editValue"
                    v-model="form.upload"
                />
            </div>
            <Button
                v-if="!popup"
                @click.prevent="handleSubmit"
                type="submit"
                label="Submit"
                size="small"
                severity="contrast"
            />
        </form>
    </div>

    <FormButton v-if="popup" label="categories" @submit="handleSubmit" />
</template>

<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";
import UploadFile from "@/Components/UploadFIle.vue";
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";

const toast = useToast();
const props = defineProps({
    editValue: {
        default: null,
    },
    popup: {
        default: true,
    },
});

const page = usePage();
var { languages } = page.props;

const form = useForm({
    name: "",
    parent: null,
    upload: null,
    translates: [
        {
            id: null,
            label: "en",
            name: null,
            language_id: languages[0].id,
        },
    ],
});

const { onFormSubmit } = useFormSubmit(form, "categories", true);

const emits = defineEmits("success");

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Category ${
                    !props.editValue ? "create" : "update"
                } successful.`,
                life: 3000,
            });
            emits("success", true);
        },
    };
    onFormSubmit(options, props.editValue);
};

// const handleInputChange = (value) => {
//     form.tra;
// };

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
        form.name = props.editValue.name;
        form.upload = props.editValue.upload;

        form.translates = [];

        Object.keys(props.editValue.translates).forEach((key, index) => {
            const language = props.editValue.translates[key];

            form.translates.push({
                label: language.code,
                name: language.name,
                language_id: language.language_id,
            });
        });
    }
});
</script>

<style lang="scss" scoped></style>
