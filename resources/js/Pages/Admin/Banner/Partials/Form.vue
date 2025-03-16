<template>
    <div>
        <form @submit.prevent="handleSubmit">
            <div
                class="w-full block px-4 pt-1 pb-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <Tabs
                    class="border-b mb-3 border-slate-300"
                    id="descriptionTab"
                    value="en"
                >
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
                            <div class="mb-5">
                                <InputLabel for="title" value="Title" />
                                <InputText
                                    id="title"
                                    class="w-full"
                                    type="text"
                                    v-model="form.translates[index].title"
                                    autocomplete="off"
                                    @input.prevent="
                                        handleInputChange(
                                            tab,
                                            form.translates[index]
                                        )
                                    "
                                />
                                <InputError :message="form.errors.title" />
                            </div>
                            <div class="mb-5">
                                <InputLabel
                                    for="description"
                                    value="Description"
                                />
                                <Editor
                                    id="description"
                                    class="w-full"
                                    type="text"
                                    v-model="form.translates[index].description"
                                    autocomplete="off"
                                    @handleInput="
                                        handleInputChange(
                                            tab,
                                            form.translates[index]
                                        )
                                    "
                                />
                                <InputError
                                    :message="form.errors.description"
                                />
                            </div>
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
            <div
                class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <div class="mb-5">
                    <InputLabel value="Type" />
                    <PSelect
                        :options="typeOptions"
                        optionLabel="label"
                        optionValue="key"
                        class="w-full"
                        v-model="form.type"
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
                    <InputLabel value="Banner type (Optional)" />
                    <PSelect
                        :options="bannerTypes"
                        optionLabel="label"
                        optionValue="value"
                        class="w-full"
                        v-model="form.banner_type"
                        placeholder="Choose banner type"
                        @change="defaultType"
                        :showClear="true"
                    />
                    <InputError :message="form.errors.type" />
                </div>
                <div class="mb-5">
                    <InputLabel value="Is active" />
                    <PSelect
                        :options="activeOptions"
                        optionLabel="label"
                        optionValue="key"
                        filter
                        class="w-full"
                        v-model="form.status"
                    />
                </div>
                <div class="mb-5">
                    <InputLabel value="Image" />
                    <UploadFIle :is-edit="editValue" v-model="form.upload" />
                </div>
            </div>
        </form>
    </div>
    <FormButton label="banners" @submit="handleSubmit" />
</template>

<script setup>
import _ from "lodash";
import { useForm, usePage } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import { onMounted, ref } from "vue";
import axios from "axios";
import Editor from "@/Components/Editor.vue";
import Tabs from "primevue/tabs";
import TabList from "primevue/tablist";
import Tab from "primevue/tab";
import TabPanels from "primevue/tabpanels";
import TabPanel from "primevue/tabpanel";
import UploadFIle from "@/Components/UploadFIle.vue";
import { useActiveOption } from "@/composables/useActiveOption";

const toast = useToast();
const page = usePage();
var { languages, api_token } = page.props;

const props = defineProps({
    banners: Array,
    editValue: {
        default: null,
    },
    products: Array,
});

const bannerTypes = ref([
    {
        label: "Intro",
        value: "intro",
    },
    {
        label: "Home",
        value: "home",
    },
]);

const productLists = ref([]);
const categoriesLists = ref([]);

const form = useForm({
    title: "",
    description: "",
    type: null,
    banner_type: null,
    upload: [],
    product_id: null,
    category_id: null,
    type: null,
    status: "active",
    translates: [],
});

// const defaultType = () => {
//     if (form.type == "product") {
//         form.category_id = props.editValue.category_id
//             ? props.editValue.category_id
//             : null;
//     } else {
//         form.product_id = props.editValue.product_id
//             ? props.editValue.product_id
//             : null;
//     }
// };

const { onFormSubmit } = useFormSubmit(form, "banners", false);

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Banner ${
                    !props.editValue ? "create" : "update"
                } successful.`,
                life: 3000,
            });
        },
    };
    onFormSubmit(options, props.editValue);
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
                description: "",
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

const { activeOptions } = useActiveOption();

onMounted(async () => {
    if (props.editValue != null) {
        form.title = props.editValue.title;
        form.description = props.editValue.description;
        form.status = props.editValue.status;
        form.banner_type = props.editValue.banner_type;
        productLists.value = [props.editValue.product];
        form.product_id = props.editValue.product_id;
        categoriesLists.value = [props.editValue.category];
        form.category_id = props.editValue.category_id ?? null;
        form.translates = [];
        form.type = props.editValue.product_id ? "product" : "category";
        form.upload = props.editValue.upload;

        Object.keys(props.editValue.translates).forEach((key, index) => {
            const language = props.editValue.translates[key];
            form.translates.push({
                label: language.code,
                title: language.title,
                description: language.description ?? "",
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
