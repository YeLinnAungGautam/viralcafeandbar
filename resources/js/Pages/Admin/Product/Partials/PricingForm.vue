<template>
    <div class="grid grid-cols-2 gap-x-4">
        <div v-if="hasUpload" class="col-span-full">
            <upload-file :is-edit="sku" v-model="sku.upload" :multiple="true" />
        </div>
        <div class="mb-5">
            <InputLabel for="sku" value="SKU (Optional)" />
            <InputText
                id="sku"
                class="w-full"
                type="text"
                autocomplete="off"
                v-model="sku.code"
            />
        </div>
        <div class="mb-5">
            <InputLabel for="stock" value="Stock" />
            <InputText
                id="stock"
                class="w-full"
                type="number"
                v-model="sku.stock"
            />
            <InputError
                :message="errors['skus.' + index + '.stock'] ?? errors.stock"
            />
        </div>
        <!-- <div class="mb-5">
            <InputLabel for="stock_status" value="Stock status" />
            <PSelect
                class="w-full"
                placeholder="Choose Order Status"
                :options="options"
                optionLabel="name"
                optionValue="key"
                v-model="sku.stock_status"
            />
            <InputError
                :message="
                    errors['skus.' + index + '.stock_status'] ??
                    errors.stock_status
                "
            />
        </div> -->
        <!-- <div class="mb-5">
            <InputLabel for="weight" value="Weight (Optional)" />
            <InputText
                id="weight"
                class="w-full"
                type="number"
                autocomplete="off"
                v-model="sku.weight"
            />
            <InputError
                :message="errors['skus.' + index + '.weight'] ?? errors.weight"
            />
        </div> -->
        <div class="mb-5">
            <InputLabel for="price" value="Regular price" />
            <InputText
                id="price"
                class="w-full"
                type="number"
                autocomplete="off"
                v-model="sku.price"
            />
            <InputError
                :message="errors['skus.' + index + '.price'] ?? errors.price"
            />
        </div>
        <div class="mb-5">
            <InputLabel for="sale_price" value="Sale price" />
            <InputText
                id="sale_price"
                class="w-full"
                type="number"
                autocomplete="off"
                v-model="sku.sale_price"
            />
            <InputError
                :message="
                    errors['skus.' + index + '.sale_price'] ?? errors.sale_price
                "
            />
            <button
                class="hover:underline mt-3"
                @click.prevent="sku.discount_schedule = !sku.discount_schedule"
            >
                {{ !sku.discount_schedule ? "Add Schedule" : "Cancel" }}
            </button>
        </div>
        <div
            v-if="sku.discount_schedule"
            class="w-full flex gap-4 col-span-full"
        >
            <div class="mb-5 w-full">
                <InputLabel for="start_date" value="From date" />
                <DatePicker
                    id="start_date"
                    v-model="sku.discount_start_date"
                    showTime
                    :min-date="new Date()"
                    hourFormat="12"
                    class="w-full"
                />
                <InputError
                    :message="
                        errors['skus.' + index + '.discount_start_date'] ??
                        errors.discount_start_date
                    "
                />
            </div>
            <div class="w-full">
                <InputLabel for="end_date" value="To date" />
                <DatePicker
                    id="end_date"
                    v-model="sku.discount_end_date"
                    showTime
                    :min-date="new Date()"
                    hourFormat="12"
                    class="w-full"
                />
                <InputError
                    :message="
                        errors['skus.' + index + '.discount_end_date'] ??
                        errors.discount_start_date
                    "
                />
            </div>
        </div>
    </div>
    <Button
        v-if="hasUpload"
        @click="emits('submit', sku)"
        type="button"
        size="small"
        label="Save"
    />
</template>

<script setup>
import { onMounted, onUpdated, ref, onUnmounted } from "vue";
import UploadFile from "@/Components/UploadFIle.vue";

const props = defineProps({
    hasUpload: {
        default: false,
    },
    item: Object,
    errors: Object,
    isDisabled: {
        default: false,
    },
    attributes: Object,
    index: {
        default: 0,
    },
});

const emits = defineEmits(["wasUpdate", "submit"]);

const options = ref([
    {
        name: "In stock",
        key: "in_stock",
    },
    {
        name: "Out of stock",
        key: "out_stock",
    },
    {
        name: " On backorder",
        key: "on_backorder",
    },
]);

const sku = ref({
    name: null,
    id: null,
    code: null,
    price: null,
    sale_price: null,
    stock: null,
    stock_status: null,
    weight: null,
    has_discount: false,
    discount_type: "",
    discount: null,
    discount_schedule: false,
    discount_start_date: null,
    discount_end_date: null,
    upload: [],
});

onMounted(() => {
    sku.value = props.item;
});
let names = [];
const handleChange = (event) => {
    let x = {
        [event.value?.attribute?.name]: event.value?.id,
    };

    names.push(event.value.value);

    sku.value.name = names.join("/");

    sku.value.attribute_option_id.push(x);
};

const optionsDiscount = ref([
    { name: "Fix Price", key: "fix" },
    { name: "Percentage", key: "percent" },
]);

onUpdated(() => {
    emits("wasUpdate", sku);
});

onUnmounted(() => {});
</script>

<style lang="scss" scoped></style>
