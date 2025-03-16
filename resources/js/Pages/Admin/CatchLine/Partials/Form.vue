<template>
    <div>
        <form @submit.prevent="handleSubmit">
            <div
                class="w-full block px-4 pt-1 pb-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <Tabs id="descriptionTab" value="en">
                    <TabList>
                        <Tab
                            v-for="tab in languages"
                            :key="tab.id"
                            :value="tab.code"
                        >
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
                            <div class="">
                                <InputLabel for="name" value="Name" />
                                <InputText
                                    id="name"
                                    class="w-full"
                                    type="text"
                                    v-model="form.translates[index].name"
                                    autocomplete="off"
                                    @input.prevent="
                                        handleInputChange(
                                            tab,
                                            form.translates[index]
                                        )
                                    "
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
            <div
                class="w-full block px-4 py-3 pb-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <div class="mb-5">
                    <InputLabel value="Type" />
                    <PSelect
                        :options="typeOptions"
                        optionLabel="label"
                        optionValue="key"
                        class="w-full"
                        v-model="form.type"
                        @change="defaultType"
                        placeholder="Choose Type"
                        :showClear="true"
                    />
                    <InputError :message="form.errors.type" />
                </div>
                <div v-show="form.type == 'product'" class="mb-5">
                    <InputLabel value="Product" />
                    <PSelect
                        @filter="handleCallProduct"
                        :options="productLists"
                        optionLabel="name"
                        optionValue="id"
                        class="w-full"
                        v-model="form.product_id"
                        placeholder="Choose Product"
                        filter
                    />
                    <InputError :message="form.errors.product_id" />
                </div>
                <div v-show="form.type == 'category'" class="mb-5">
                    <InputLabel value="Category" />
                    <PSelect
                        @filter="handleCallCategory"
                        :options="categoriesLists"
                        optionLabel="name"
                        optionValue="id"
                        class="w-full"
                        v-model="form.category_id"
                        placeholder="Choose Category"
                        filter
                    />
                    <InputError :message="form.errors.category_id" />
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
                <div class="mb-5">
                    <InputLabel value="Image" />
                    <UploadFIle
                        :multiple="true"
                        :is-edit="editValue"
                        v-model="form.upload"
                    />
                </div>
            </div>
        </form>
    </div>
    <FormButton label="tag-lines" @submit="handleSubmit" />
</template>

<script setup>
import _ from "lodash";
import { useForm, usePage } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted, ref } from "vue";
import Editor from "@/Components/Editor.vue";
import Tabs from "primevue/tabs";
import TabPanels from "primevue/tabpanels";
import TabPanel from "primevue/tabpanel";
import TabList from "primevue/tablist";
import Tab from "primevue/tab";
import MultiSelect from "primevue/multiselect";
import UploadFIle from "@/Components/UploadFIle.vue";
import { useActiveOption } from "@/composables/useActiveOption";
import Textarea from "primevue/textarea";

const toast = useToast();
const page = usePage();
var { languages, api_token } = page.props;

const props = defineProps({
    tags: Array,
    editValue: {
        default: null,
    },
});

const productLists = ref([]);
const categoriesLists = ref([]);

const form = useForm({
    name: "",
    status: "active",
    upload: null,
    translates: [],
    type: null,
    product_id: null,
    category_id: null,
});

async function getByProductName(params) {
    const data = await axios.get(route("products.search-by-keyword"), {
        headers: {
            "API-TOKEN": api_token,
        },
        params: {
            keyword: params,
        },
    });
    const result = await data.data;
    productLists.value = result.data;
}

const handleCallProduct = _.debounce(
    async (event) => await getByProductName(event.value),
    300
);

async function getByCategoryName(params) {
    const data = await axios.get(route("categories.search-by-keyword"), {
        headers: {
            "API-TOKEN": api_token,
        },
        params: {
            keyword: params,
        },
    });

    const result = await data.data;

    categoriesLists.value = result.data;
}

const handleCallCategory = _.debounce(
    async (event) => await getByCategoryName(event.value),
    300
);

const { onFormSubmit } = useFormSubmit(form, "tag-lines", false);

const { activeOptions } = useActiveOption();

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Tag Line  ${
                    !props.editValue ? "create" : "update"
                } successful.`,
                life: 3000,
            });
        },
    };
    onFormSubmit(options, props.editValue);
};

const defaultType = () => {
    if (form.type == "product") {
        form.category_id = props.editValue?.category_id
            ? props.editValue.category_id
            : null;
    } else {
        form.product_id = props.editValue?.product_id
            ? props.editValue.product_id
            : null;
    }
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
                name: "",
                description: "",
            });
        }
    });
};
handleAddLanguages();
const handleInputChange = (item, value) => {
    if (item.code == "en") {
        form.name = value.name;
        form.description = value.description;
    }
};

const typeOptions = ref([
    {
        key: "product",
        label: "Product",
    },
    {
        key: "category",
        label: "Category",
    },
]);

onMounted(() => {
    if (props.editValue != null) {
        form.name = props.editValue.name;
        form.description = props.editValue.description;
        form.short_description = props.editValue.short_description;
        form.status = props.editValue.status;
        form.upload = props.editValue.upload;
        form.translates = [];
        form.type = props.editValue.product_id ? "product" : "category";
        form.category_id = props.editValue.category_id;
        form.product_id = props.editValue.product_id;

        productLists.value = [props.editValue.product];
        categoriesLists.value = [props.editValue.category];

        Object.keys(props.editValue.translates).forEach((key, index) => {
            const language = props.editValue.translates[key];
            form.translates.push({
                label: language.code,
                name: language.name,
                description: language.description,
                language_id: language.language_id,
            });
        });
    }
});
</script>

<style lang="css" scoped>
#descriptionTab .p-tab {
    padding: 10px 0px;
    font-size: 14px;
    margin-right: 20px;
}

.p-tabpanels {
    padding: 15px 0px;
}
</style>
