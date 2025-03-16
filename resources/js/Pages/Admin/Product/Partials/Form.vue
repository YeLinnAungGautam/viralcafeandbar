<template>
    <div class="grid grid-cols-6 gap-x-5">
        <div class="col-span-4">
            <div
                class="w-full block px-4 pt-1 pb-1 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <Tabs
                    id="descriptionTab"
                    class="mb-3 border-slate-300"
                    value="en"
                >
                    <TabList>
                        <Tab
                            v-for="tab in languages"
                            :key="tab.id"
                            :value="tab.code"
                        >
                            {{ tab.name }}
                            {{ tab.is_default == 1 ? "(Default)" : "(Optional)" }}
                        </Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel
                            v-for="(tab, index) in languages"
                            :key="tab.id"
                            :value="tab.code"
                        >
                            <div class="mb-5">
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
                            <div class="mb-5">
                                <InputLabel value="Description (Optional)" />
                                <Editor
                                    @handleInput="
                                        handleInputChange(
                                            tab,
                                            form.translates[index]
                                        )
                                    "
                                    v-model="form.translates[index].description"
                                />
                            </div>
                            <div class="flex space-x-3 items-center mb-3">
                                <ToggleSwitch
                                    id="has"
                                    v-model="form.has_additional"
                                />
                                <InputLabel
                                    for="has"
                                    class="!mb-0"
                                    :value="`Has Additional`"
                                />
                            </div>
                            <div v-if="form.has_additional">
                                <div>
                                    <draggable
                                        v-model="
                                            form.translates[index].additionals
                                        "
                                        :item-key="'index'"
                                    >
                                        <template
                                            #item="{
                                                element: additional,
                                                index: key,
                                            }"
                                        >
                                            <div class="mb-3">
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <InputGroup>
                                                        <InputGroupAddon>
                                                            <i
                                                                class="fa-solid fa-arrows-up-down-left-right"
                                                            ></i>
                                                        </InputGroupAddon>
                                                        <InputText
                                                            class="w-full"
                                                            v-model="
                                                                additional.text
                                                            "
                                                        >
                                                        </InputText>
                                                    </InputGroup>
                                                    <button>
                                                        <button
                                                            :disabled="
                                                                form.translates[
                                                                    index
                                                                ].additionals
                                                                    .length <= 1
                                                            "
                                                            @click.prevent="
                                                                handleRemoveAdditional(
                                                                    key
                                                                )
                                                            "
                                                            class="w-8 h-8 bg-slate-200 inline-flex justify-center items-center rounded-full"
                                                            :class="
                                                                form.translates[
                                                                    index
                                                                ].additionals
                                                                    .length <= 1
                                                                    ? 'opacity-50'
                                                                    : ''
                                                            "
                                                        >
                                                            <i
                                                                class="fa-solid fa-trash-alt text-sm"
                                                            ></i>
                                                        </button>
                                                    </button>
                                                </div>
                                                <InputError
                                                    :message="
                                                        form.errors[
                                                            `translates.${index}.additionals.${key}.text`
                                                        ]
                                                    "
                                                />
                                            </div>
                                        </template>
                                    </draggable>
                                    <button
                                        @click.prevent="handleNewAdditional"
                                        class="p-2 inline-flex justify-center items-center bg-slate-200 rounded-md hover:bg-slate-300 duration-500"
                                    >
                                        <i class="fa-solid fa-plus me-2"></i>
                                        Add new
                                    </button>
                                </div>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>

            <div
                class="w-full block px-4 py-5 pb-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <div class="mb-4">
                    <h6 class="mb-3 font-semibold">Upload Preview Image</h6>
                    <upload-file
                        :is-edit="editValue"
                        v-model="form.upload"
                        :multiple="true"
                    />
                </div>
                <!-- <div>
                    <InputLabel for="name" value="Product type" />
                    <PSelect
                        class="w-full"
                        :options="options"
                        optionValue="key"
                        optionLabel="name"
                        v-model="form.type"
                        placeholder="Choose One"
                        @change="handleChangeType"
                    />
                </div> -->
            </div>
            <div
                v-if="form.type == 'simple'"
                class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <PricingForm :item="form.skus[0]" :errors="form.errors" />
            </div>

            <div
                class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
                v-if="form.type == 'vairation'"
            >
                <h6
                    class="mb-3 font-semibold flex justify-between items-center"
                >
                    Vairation

                    <Button
                        type="button"
                        icon="fa fa-ellipsis-v"
                        @click="toggle"
                        aria-haspopup="true"
                        aria-controls="overlay_menu"
                        rounded
                        outlined
                    />

                    <Menu
                        ref="menu"
                        id="overlay_menu"
                        :model="items"
                        :popup="true"
                    />
                </h6>
                <div class="mb-3">
                    <MultiSelect
                        id="attribute"
                        v-model="form.attribute_id"
                        :options="attributes"
                        optionLabel="name"
                        optionValue="id"
                        placeholder="Select Attributes"
                        showClear
                        display="chip"
                        class="w-full"
                        :maxSelectedLabels="3"
                    >
                    </MultiSelect>
                </div>
                <Message
                    :life="2000"
                    @life-end="form.errors.skus = ''"
                    v-if="form.errors.skus"
                    severity="error"
                >
                    {{ form.errors.skus }}
                </Message>
                <div v-if="form.attribute_id.length" class="text-end">
                    <Button
                        v-if="!editValue"
                        @click="handleGenerate"
                        size="small"
                        outlined
                    >
                        Auto Generate
                    </Button>
                    <span v-if="!editValue" class="mx-2 text-slate-500 text-sm">
                        OR
                    </span>
                    <Button
                        @click="handleAddNew"
                        size="small"
                        type="button"
                        outlined
                    >
                        Add Manaual
                    </Button>
                </div>
                <div>
                    <div
                        class="mt-3"
                        v-for="(item, key) in form.skus"
                        :key="item"
                    >
                        <div class="flex justify-center items-center gap-3">
                            <div
                                class="flex-1"
                                v-for="(vairation, index) in item.vairations"
                                :key="index"
                            >
                                <PSelect
                                    class="w-full"
                                    :options="vairation"
                                    optionValue="id"
                                    optionLabel="value"
                                    v-model="item.attribute_option_id[index]"
                                    :disabled="item.id ? true : false"
                                    placeholder="Choose One"
                                />
                            </div>
                            <div class="flex-1">
                                <InputText
                                    class="w-full"
                                    v-model="item.price"
                                    :disabled="editValue"
                                    placeholder="Regular price"
                                    :invalid="
                                        form.errors['skus.' + key + '.price']
                                    "
                                />
                            </div>
                            <div class="flex-1">
                                <InputText
                                    class="w-full"
                                    v-model="item.stock"
                                    :disabled="editValue"
                                    placeholder="Stock"
                                    :invalid="
                                        form.errors['skus.' + key + '.stock']
                                    "
                                />
                            </div>
                            <div class="flex gap-2">
                                <Button
                                    type="button"
                                    icon="fa fa-pencil"
                                    @click="handleEdit(key)"
                                    aria-haspopup="true"
                                    aria-controls="sku_menu"
                                    outlined
                                    rounded
                                />

                                <Button
                                    type="button"
                                    icon="fa fa-trash"
                                    @click="handleDelete(key)"
                                    aria-haspopup="true"
                                    aria-controls="sku_menu"
                                    outlined
                                    rounded
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-2">
            <div
                class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <h6 class="flex justify-between items-center">Status</h6>
                <div class="">
                    <PSelect
                        id="type"
                        v-model="form.active"
                        :options="optionsActive"
                        optionLabel="name"
                        optionValue="key"
                        class="w-full"
                    />
                </div>
            </div>
            <div
                class="w-full block px-4 py-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <div class="mb-4">
                    <InputLabel for="cate" value="Category" />
                    <MultiSelect
                        id="type"
                        v-model="form.category"
                        :options="categories"
                        optionLabel="name"
                        optionValue="id"
                        placeholder="Choose Category"
                        class="w-full"
                        filter
                        showClear
                    >
                        <template #footer>
                            <div class="py-2 px-4">
                                <button
                                    @click="visiableCategory = true"
                                    class="text-slate-700 py-1 rounded-md px-2 bg-slate-200 w-full"
                                >
                                    <i class="fa fa-plus-circle"></i>
                                    Add New
                                </button>
                            </div>
                        </template>
                    </MultiSelect>
                    <InputError :message="form.errors.category" />
                </div>
                <div class="flex space-x-3 items-center">
                    <ToggleSwitch
                        id="product_status"
                        v-model="form.product_status"
                    />
                    <InputLabel
                        class="!mb-0"
                        for="product_status"
                        value="New Arrival"
                    />
                </div>
            </div>
            <!-- <div
                class="w-full block px-4 py-5 pb-5 bg-white border border-slate-200 rounded-lg shadow mb-4"
            >
                <div class="mb-4">
                    <h6 class="mb-3 font-semibold">Upload Video</h6>
                    <upload-file
                        id="none"
                        :acceptedFileTypes="['video/*']"
                        :is-edit="editValue"
                        v-model="form.video_link"
                        :multiple="false"
                    />
                    <span class="-mt-1 block text-slate-500 text-xs">
                        Can upload only (10MB)
                    </span>
                </div>
                <div class="mb-4">
                    <h6 class="mb-3 font-semibold">Upload Video Thumnail</h6>
                    <upload-file
                        id="none"
                        :is-edit="editValue"
                        v-model="form.image_link"
                        :multiple="false"
                    />
                </div>
            </div> -->
        </div>
    </div>
    <Dialog
        v-model:visible="visibleSku"
        modal
        :header="form.skus[indexKey]?.name ?? 'Add New SKU'"
        :style="{ width: '50vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        @close="sku = null"
    >
        <!-- :item="sku" -->
        <PricingForm
            :index="indexKey"
            :item="form.skus[indexKey]"
            :errors="form.errors"
            :has-upload="true"
            @submit="handleSubmitVairation"
        />
    </Dialog>
    <Dialog
        v-model:visible="visiableAttribute"
        modal
        header="Add attribute"
        :style="{ width: '40vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <AttributeForm @success="visiableAttribute = false" />
    </Dialog>
    <Dialog
        v-model:visible="visiableAttributeOpt"
        modal
        header="Add Attribute Option"
        :style="{ width: '40vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <AttributeOptionForm
            @success="visiableAttributeOpt = false"
            :attributes="attributes"
        />
    </Dialog>
    <Dialog
        v-model:visible="visiableCategory"
        modal
        header="Add category"
        :style="{ width: '40vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <CategoryForm
            :popup="false"
            :categories="categories"
            @success="visiableCategory = false"
        />
    </Dialog>
    <FormButton label="products" @submit="handleSubmit" />

    <ConfirmDialog />
</template>

<script setup>
// import Editor from "primevue/editor";
import Tabs from "primevue/tabs";
import TabList from "primevue/tablist";
import Tab from "primevue/tab";
import TabPanels from "primevue/tabpanels";
import TabPanel from "primevue/tabpanel";
import Dialog from "primevue/dialog";
import PricingForm from "./PricingForm.vue";
import AttributeForm from "@/Pages/Admin/Attribute/Partials/Form.vue";
import CategoryForm from "@/Pages/Admin/Category/Partials/Form.vue";
import AttributeOptionForm from "@/Pages/Admin/AttributeOption/Partials/Form.vue";
import Message from "primevue/message";
import UploadFile from "@/Components/UploadFIle.vue";
import ConfirmDialog from "primevue/confirmdialog";
import Editor from "@/Components/Editor.vue";
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";
import MultiSelect from "primevue/multiselect";
import useFormSubmit from "@/composables/useFormSubmit";
import { ref, onMounted } from "vue";
import { Link, router, useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import axios from "axios";
import Button from "primevue/button";
import Menu from "primevue/menu";
import FormButton from "@/Components/Layouts/FormButton.vue";
import InputText from "primevue/inputtext";
import draggable from "vuedraggable";

import { add } from "lodash";
import ToggleSwitch from "primevue/toggleswitch";

const confirm = useConfirm();
const toast = useToast();
const page = usePage();

const datas = ref([
    { id: 1, name: "Item 1" },
    { id: 2, name: "Item 2" },
    { id: 3, name: "Item 3" },
]);

const props = defineProps({
    editValue: {
        type: Object,
        default: null,
    },
    attributes: Array,
    categories: Array,
    attributeOptions: Array,
});

const indexKey = ref(0);
const visibleSku = ref(false);
const visiableAttribute = ref(false);
const visiableCategory = ref(false);
const visiableAttributeOpt = ref(false);

const options = ref([
    {
        name: "Simple",
        key: "simple",
    },
    {
        name: "Vairation",
        key: "vairation",
    },
]);

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

const product_status = ref([
    {
        name: "Arrival",
        key: "arrival",
    },
]);

const menu = ref();
const items = ref([
    {
        label: "Options",
        items: [
            {
                label: "Add Attribute",
                command: () => {
                    visiableAttribute.value = true;
                },
            },
            {
                label: "Add Attribute Value",
                command: () => {
                    visiableAttributeOpt.value = true;
                },
            },
        ],
    },
]);

const form = useForm({
    name: "",
    type: "simple",
    description: null,
    active: "active",
    attribute_id: [],
    upload: [],
    category: [],
    product_status: false,
    has_additional: false,
    image_link: [],
    video_link: [],
    skus: [
        {
            id: null,
            name: null,
            code: null,
            price: null,
            sale_price: null,
            stock: null,
            stock_status: "in_stock",
            weight: null,
            has_discount: false,
            discount_type: null,
            discount: null,
            discount_schedule: false,
            discount_start_date: null,
            discount_end_date: null,
            vairations: [],
            attribute_option_id: [],
            upload: [],
        },
    ],
    translates: [],
});

const { onFormSubmit } = useFormSubmit(form, "products", true);

const handleGenerate = async () => {
    const result = await axios(route("admin.attribute-options.generate"), {
        method: "POST",
        data: {
            ids: form.attribute_id,
        },
    });

    if (result.data.success) {
        form.skus = [];

        const vairation = result.data.data;
        for (let index = 0; index < vairation.length; index++) {
            var element = vairation[index];
            form.skus.push({
                id: null,
                name: element.name,
                code: null,
                price: null,
                sale_price: null,
                stock: null,
                stock_status: "in_stock",
                weight: null,
                has_discount: false,
                discount_type: null,
                discount: null,
                discount_schedule: false,
                discount_start_date: null,
                discount_end_date: null,
                vairations: element.vairations,
                attribute_option_id: element.attribute_option_id,
                upload: [],
            });
        }
    }
};

const selectedAttributeOptions = ref([]);

const handleAddNew = async () => {
    const result = await axios(route("admin.attribute-options.selectd"), {
        method: "POST",
        data: {
            ids: form.attribute_id,
        },
    });

    selectedAttributeOptions.value = result.data.data;
    pushSkuData();
};

const pushSkuData = () => {
    form.skus.push({
        id: null,
        name: null,
        code: null,
        price: null,
        sale_price: null,
        stock: null,
        stock_status: "in_stock",
        weight: null,
        has_discount: false,
        discount_type: null,
        discount: null,
        discount_schedule: false,
        discount_start_date: null,
        discount_end_date: null,
        vairations: selectedAttributeOptions.value,
        attribute_option_id: [],
        upload: [],
    });
};

const handleDelete = (index, sku) => {
    const id = form.skus[index]?.id;
    confirm.require({
        message: "Do you want to delete this record?",
        header: "Danger Zone",
        icon: "fa fa-info-circle",
        rejectLabel: "Cancel",
        rejectProps: {
            label: "Cancel",
            severity: "secondary",
            outlined: true,
        },
        acceptProps: {
            label: "Delete",
            severity: "danger",
        },
        accept: () => {
            if (id) {
                router.delete(route("admin.product-vairations.remove", id));
            }
            form.skus.splice(index, 1);
        },
        reject: () => {
            return;
        },
    });
};

const handleEdit = (key) => {
    visibleSku.value = true;
    indexKey.value = key;
};

const handleSubmitVairation = (item = {}) => {
    if (props.editValue) {
        const options = {
            onSuccess: () => {
                visibleSku.value = false;

                form.skus[indexKey.value].upload =
                    props.editValue.skus[indexKey.value].upload;

                toast.add({
                    severity: "success",
                    summary: "Success",
                    detail: `Vairation ${
                        !props.editValue ? "create" : "update"
                    } successful.`,
                    life: 3000,
                });
            },
            onError: (errors) => {
                form.errors = errors;
            },
        };

        var url;
        var config;

        if (item.id != null) {
            config = {
                _method: "put",
                ...item,
            };
            url = route("admin.product-vairations.update", item.id);
        } else {
            config = {
                ...{
                    product_id: props.editValue.id,
                    ...item,
                },
            };

            url = route("admin.product-vairations.store");
        }

        router.post(url, config, options);
    } else {
        form.skus[indexKey.value] = item;
        visibleSku.value = false;
    }
};

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Product ${
                    !props.editValue ? "create" : "update"
                } successful.`,
                life: 3000,
            });
        },
        onError: (errors) => {
            form.errors = errors;
        },
    };

    onFormSubmit(options, props.editValue);
};

const handleChangeType = (event) => {
    if (event.value == "simple") {
        form.skus = [];
        pushSkuData();
    } else {
        form.skus = [];
    }
};

onMounted(async () => {
    if (props.editValue) {
        form.name = props.editValue.name;
        form.description = props.editValue.description;
        form.active = props.editValue.active;
        form.type = props.editValue.type;
        form.category = props.editValue.categories.map((item) => item.id);
        form.upload = props.editValue.upload ?? [];
        form.product_status =
            props.editValue.product_status == "arrival" ? true : false;
        form.has_additional = props.editValue.has_additional;

        if (props.editValue.video) {
            form.image_link.url = props.editValue.video.image_link
                ? props.editValue.video.image_link
                : [];

            form.video_link.url = props.editValue.video.video_link
                ? props.editValue.video.video_link
                : [];
        }

        if (props.editValue.type != "simple") {
            form.attribute_id = props.editValue.attributes.map(
                (item) => item.id
            );

            await handleGenerate();
        }

        for (let index = 0; index < props.editValue.skus.length; index++) {
            const sku = props.editValue.skus[index];
            form.skus[index].id = sku?.id ?? null;
            form.skus[index].code = sku?.code ?? "";
            form.skus[index].stock = sku.stock;
            form.skus[index].stock_status = sku.stock_status;
            form.skus[index].weight = sku.weight;
            form.skus[index].price = sku.original_price;
            form.skus[index].sale_price = sku.sale_price;
            form.skus[index].discount_schedule = sku.discount_schedule;
            form.skus[index].discount_start_date = sku.discount_start_date;
            form.skus[index].discount_end_date = sku.discount_end_date;
            form.skus[index].upload = sku.upload;
        }

        form.skus = form.skus.filter((item) => item.id);

        Object.keys(props.editValue.translates).forEach((key, index) => {
            const language = props.editValue.translates[key];
            for (const property in language) {
                form.translates[index].name = language.name;
                form.translates[index].description = language.description
                    ? language.description
                    : "";
                form.translates[index].label = language.code;
                form.translates[index].language_id = language.language_id;
                if (language.additionals) {
                    form.translates[index].additionals = language.additionals;
                }
            }
        });
    }
});

var { languages } = page.props;

const handleAddLanguages = () => {
    languages.forEach((element) => {
        const exists = form.translates.some(
            (item) => item?.label == element.code
        );

        if (!exists) {
            form.translates.push({
                label: element.code,
                language_id: element.id,
                name: "",
                description: "",
                additionals: [
                    {
                        text: "",
                    },
                ],
            });
        }
    });
};

const handleNewAdditional = () => {
    form.translates.forEach((el) =>
        el.additionals.push({
            text: "",
        })
    );
};

const handleRemoveAdditional = (index) => {
    form.translates.forEach((el) => el.additionals.splice(index, 1));
};

handleAddLanguages();

const handleInputChange = (item, value) => {
    if (item.code == "en") {
        form.name = value.name;
        form.description = value.description;
    }
};

const toggle = (event) => {
    menu.value.toggle(event);
};
</script>

<style lang="css" scoped>
#descriptionTab .p-tab {
    padding: 10px 0px;
    font-size: 14px;
    margin-right: 20px;
}
/* //class="p-toggleswitch-input toggle-style" */

.p-tabpanels {
    padding: 15px 0px;
}
</style>
