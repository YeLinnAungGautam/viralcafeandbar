<template>
    <div
        class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-"
    >
        <form @submit.prevent="handleSubmit">
            <div class="mb-5 flex gap-2 items-end w-full">
                <div class="w-full">
                    <InputText
                        id="key"
                        class="w-full"
                        type="text"
                        v-model="form.key"
                        autocomplete="off"
                        placeholder="Key"
                    />
                    <InputError :message="form.errors.key" />
                </div>
            </div>
            <div class="mb-5">
                <div
                    class="w-full mb-5"
                    v-for="(translate, index) in form.translates"
                    :key="translate.label"
                >
                    <InputGroup>
                        <InputGroupAddon>
                            <span class="uppercase">
                                {{ translate.label }}
                            </span>
                        </InputGroupAddon>
                        <InputText
                            v-model="translate.value"
                            autocomplete="off"
                        />
                    </InputGroup>
                    <InputError
                        :message="form.errors[`translates.${index}.value`]"
                    />
                </div>
            </div>
        </form>
    </div>
    <FormButton label="localizations" @submit="handleSubmit" />
</template>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";

const toast = useToast();
const props = defineProps({
    editValue: {
        default: null,
    },
});

const page = usePage();

const form = useForm({
    key: "",
    translates: [],
});

const { onFormSubmit } = useFormSubmit(form, "localizations", false);

const emits = defineEmits("success");

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Localization ${
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

var { languages } = page.props;

onMounted(() => {
    if (props.editValue == null) {
        languages.map(function (el) {
            form.translates.push({
                label: el.code,
                language_id: el.id,
                value: "",
            });
        });
    } else if (props.editValue !== null) {
        form.key = props.editValue.key;
        form.translates.push({
            ...props.editValue,
            label: languages.find(
                (el) => el.id === props.editValue?.language_id
            )?.code,
        });
    }
});
</script>

<style lang="scss" scoped></style>
