<template>
    <div
        class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-"
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
                        icon="fa-solid fa-language"
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
                <InputLabel value="Status" />
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
    <FormButton label="tags" @submit="handleSubmit" />
</template>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";
import { useActiveOption } from "@/composables/useActiveOption";

const toast = useToast();
const page = usePage();
var { languages } = page.props;

const props = defineProps({
    tags: Array,
    editValue: {
        default: null,
    },
});

const form = useForm({
    name: "",
    status: "active",
    translates: [
        {
            id: null,
            label: "en",
            name: null,
            language_id: 1,
        },
    ],
});

const { onFormSubmit } = useFormSubmit(form, "tags", false);
const { activeOptions } = useActiveOption();

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Tag ${
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
        form.name = props.editValue.name;
        form.status = props.editValue.status;
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
