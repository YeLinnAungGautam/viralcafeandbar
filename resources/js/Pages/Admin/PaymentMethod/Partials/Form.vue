<template>
    <div
        class="w-full block px-4 pt-1 pb-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
    >
        <Tabs id="descriptionTab" class="border-slate-300" value="en">
            <TabList>
                <Tab v-for="tab in languages" :key="tab.id" :value="tab.code">
                    {{ tab.name }}
                    {{ tab.is_default == 1 ? "(Default)" : "" }}
                </Tab>
            </TabList>
            <TabPanels>
                <TabPanel
                    v-for="(tab, index) in languages"
                    :key="tab.id"
                    :value="tab.code"
                >
                    <div class="mb-5">
                        <InputLabel for="title" value="Title" />
                        <InputText
                            id="title"
                            class="w-full"
                            type="text"
                            v-model="form.translates[index].title"
                            autocomplete="off"
                            @input.prevent="
                                handleInputChange(tab, form.translates[index])
                            "
                        />
                        <InputError :message="form.errors.title" />
                    </div>
                    <div class="mb-5">
                        <InputLabel for="description" value="Description" />
                        <Editor
                            id="description"
                            class="w-full"
                            type="text"
                            v-model="form.translates[index].description"
                            autocomplete="off"
                            @handleInput="
                                handleInputChange(tab, form.translates[index])
                            "
                        />
                        <InputError :message="form.errors.description" />
                    </div>
                </TabPanel>
            </TabPanels>
        </Tabs>
    </div>

    <div
        class="w-full block px-4 py-2 bg-white border border-slate-200 rounded-lg shadow mb-2"
    >
        <div class="mb-5">
            <InputLabel for="key" value="key" />
            <InputText
                id="key"
                class="w-full"
                type="text"
                v-model="form.key"
                autocomplete="off"
            />
            <InputError :message="form.errors.key" />
        </div>
        <div class="mb-5">
            <InputLabel value="Status" />
            <PSelect
                :options="activeOptions"
                optionLabel="label"
                optionValue="key"
                class="w-full"
                v-model="form.status"
            />
        </div>
    </div>
    <FormButton label="payments" @submit="handleSubmit" />
</template>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted } from "vue";
import Editor from "@/Components/Editor.vue";
import Tab from "primevue/tab";
import Tabs from "primevue/tabs";
import TabPanel from "primevue/tabpanel";
import TabPanels from "primevue/tabpanels";
import TabList from "primevue/tablist";
import { useActiveOption } from "@/composables/useActiveOption";

const page = usePage();
const { languages } = page.props;

const toast = useToast();

const props = defineProps({
    memberships: Array,
    editValue: {
        default: null,
    },
});

const form = useForm({
    title: "",
    description: "",
    key: "",
    translates: [],
    status: "active",
});

const { onFormSubmit } = useFormSubmit(form, "payments", false);
const { activeOptions } = useActiveOption();

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Payment ${
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
        const exist = form.translates.some(
            (item) => item?.label == element.code
        );
        if (!exist) {
            form.translates.push({
                label: element.code,
                language_id: element.id,
                title: "",
                page_text: "",
            });
        }
    });
};
handleAddLanguages();
const handleInputChange = (item, value) => {
    if (item.code == "en") {
        form.title = value.title;
        form.description = value.description;
    }
};

onMounted(() => {
    if (props.editValue != null) {
        form.title = props.editValue.title;
        form.description = props.editValue.description;
        form.key = props.editValue.key;
        form.status = props.editValue.status;
        form.translates = [];

        Object.keys(props.editValue.translates).forEach((key, index) => {
            const language = props.editValue.translates[key];
            form.translates.push({
                label: language.code,
                title: language.title,
                description: language.description,
                language_id: language.language_id,
            });
        });
    }
});
</script>

<style lang="scss" scoped></style>
