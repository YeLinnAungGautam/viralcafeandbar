<template>
    <Dashboard title="Create Order">
        <BreadcrumbDefault page-title="Create Orders" />
        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <div class="mb-4 flex gap-x-3">
                    <div class="w-1/2">
                        <InputText
                            placeholder="Search by name"
                            class="w-full"
                            @input.prevent="handleSearchProduct"
                            v-model="payload.keyword"
                        />
                    </div>
                    <div class="w-1/2">
                        <Select
                            @change="handleSearchProduct"
                            v-model="payload.category_id"
                            :options="categories"
                            optionLabel="name"
                            optionValue="id"
                            class="w-full text-[16px]"
                            placeholder="Select a category"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-5">
                    <template v-if="products.data.length != 0">
                        <div
                            v-for="product in products.data"
                            :key="product.id"
                            class="card border bg-white border-slate-200 rounded-lg shadow flex flex-col"
                        >
                            <img
                                :src="product.upload[0]?.url"
                                :alt="product.name"
                                class="w-full h-52 object-cover"
                            />
                            <div class="p-4">
                                <h3 class="font-bold text-[15px] mb-2 truncate">
                                    {{ product.name }}
                                </h3>
                                <div>
                                    <template
                                        v-for="category in product.categories"
                                        :key="category.id"
                                    >
                                        <Tag :value="category.name"></Tag>
                                    </template>
                                </div>
                            </div>
                            <div class="mt-auto p-4">
                                <div
                                    class="flex justify-between items-center w-full"
                                >
                                    <Button
                                        :disabled="product.total_stock == 0"
                                        label="Add to cart"
                                        severity="contrast"
                                        class="w-full"
                                        @click="handleAddToCart(product)"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-span-full">
                            <Pagination :links="products.links" :state="true" />
                        </div>
                    </template>
                    <template v-else>
                        <div
                            class="text-center border col-span-full bg-white border-slate-300 mb-5 p-6 rounded-md"
                        >
                            <h3 class="text-base">No record</h3>
                        </div>
                    </template>
                </div>
            </div>
            <div class="col-span-1">
                <div class="sticky top-20">
                    <div
                        class="card border p-3 mb-5 bg-white border-slate-200 rounded-lg shadow flex flex-col"
                    >
                        <h4 class="mb-4 font-bold">
                            Cart(s) - {{ form.items.length }}
                        </h4>
                        <template v-if="form.items.length > 0">
                            <div class="max-h-[400px] overflow-scroll">
                                <div
                                    v-for="(cart, index) in form.items"
                                    :key="cart.product_id"
                                >
                                    <div
                                        class="mb-5 border p-3 rounded-md border-slate-300"
                                    >
                                        <div
                                            class="relative flex justify-between items-start"
                                        >
                                            <div>
                                                <h4 class="font-bold mb-2">
                                                    {{ cart.product_name }}
                                                </h4>
                                                <span
                                                    v-if="cart.sku_code"
                                                    class="block text-sm"
                                                >
                                                    SKU - {{ cart.sku_code }}
                                                </span>
                                            </div>
                                            <div>
                                                <button
                                                    @click="handleRemove(index)"
                                                >
                                                    <i
                                                        class="fa-solid fa-trash-alt text-danger"
                                                    ></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div
                                            class="mt-3 flex justify-between items-center"
                                        >
                                            <div>
                                                <button
                                                    class="w-8 h-8 inline-flex justify-center items-center rounded-full"
                                                    :class="{
                                                        'bg-slate-100':
                                                            cart.qty <= 1,
                                                        'bg-slate-200':
                                                            cart.qty > 1,
                                                    }"
                                                    @click.prevent="
                                                        handleProductQty(
                                                            cart,
                                                            'remove'
                                                        )
                                                    "
                                                    :disabled="cart.qty <= 1"
                                                >
                                                    <i
                                                        class="fa-solid fa-minus"
                                                    ></i>
                                                </button>
                                                <span
                                                    class="w-8 inline-block text-center font-bold"
                                                >
                                                    {{ cart.qty }}
                                                </span>
                                                <button
                                                    class="w-8 h-8 inline-flex justify-center items-center bg-slate-200 rounded-full"
                                                    :class="{
                                                        'bg-slate-200':
                                                            cart.qty <
                                                            cart.sku_stock,
                                                        'bg-slate-100':
                                                            cart.qty ===
                                                            cart.sku_stock,
                                                    }"
                                                    @click.prevent="
                                                        handleProductQty(
                                                            cart,
                                                            'add'
                                                        )
                                                    "
                                                    :disabled="
                                                        cart.qty ===
                                                        cart.sku_stock
                                                    "
                                                >
                                                    <i
                                                        class="fa-solid fa-plus"
                                                    ></i>
                                                </button>
                                            </div>
                                            <div>
                                                Price :
                                                <span
                                                    v-html="
                                                        currencyFormat(
                                                            cart.price
                                                        )
                                                    "
                                                ></span>
                                                x
                                                {{ cart.qty }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end py-2">
                                <span class="block">
                                    Total items :
                                    {{ form.items.length }}
                                </span>
                                <span class="block">
                                    Total stocks :
                                    {{
                                        form.items.reduce((total, product) => {
                                            return total + product.qty;
                                        }, 0)
                                    }}
                                </span>
                                <span class="block">
                                    Total Price :
                                    <span
                                        v-html="
                                            currencyFormat(
                                                form.items.reduce(
                                                    (total, product) => {
                                                        return (
                                                            total +
                                                            product.qty *
                                                                product.price
                                                        );
                                                    },
                                                    0
                                                )
                                            )
                                        "
                                    ></span>
                                </span>
                            </div>
                        </template>
                        <template v-else>
                            <div
                                class="text-center border border-slate-300 mb-5 p-6 rounded-md"
                            >
                                <h3 class="text-base">No record</h3>
                            </div>
                        </template>
                    </div>

                    <div class="flex gap-3">
                        <Button
                            @click="handleClearAll"
                            type="submit"
                            label="Clear all"
                            class="w-full"
                            outlined
                            severity="danger"
                            :disabled="form.items.length == 0"
                        />
                        <Button
                            @click.prevent="visible = true"
                            type="submit"
                            label="Checkout"
                            class="w-full"
                            severity="contrast"
                            :disabled="form.items.length == 0"
                        />
                    </div>
                </div>
            </div>
        </div>
        <Dialog
            v-model:visible="visible"
            modal
            header="Checkout Review"
            :style="{ width: '50vw' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        >
            <form @submit.prevent="handleCheckout" class="m-0">
                <Fieldset legend="Customer Infromation">
                    <!-- <div class="mb-5 w-full">
                        <InputLabel for="name" value="Choose customer" />
                        <CustomerAutocomplete
                            url="/api/v1/customer-search"
                            v-model="customer"
                        />
                    </div> -->

                    <div class="flex gap-x-4">
                        <div class="mb-3 w-full">
                            <InputLabel for="fname" value="First Name" />
                            <InputText
                                id="fname"
                                class="w-full"
                                type="text"
                                v-model="form.customer.first_name"
                                autocomplete="off"
                            />
                            <InputError
                                :message="form.errors['customer.first_name']"
                            />
                        </div>
                        <!-- <div class="mb-3 w-full">
                            <InputLabel for="lname" value="Last Name" />
                            <InputText
                                id="lname"
                                class="w-full"
                                type="text"
                                v-model="form.customer.last_name"
                                autocomplete="off"
                            />
                            <InputError
                                :message="form.errors['customer.last_name']"
                            />
                        </div> -->
                    </div>
                    <div class="flex gap-x-4">
                        <div class="mb-3 w-full">
                            <InputLabel for="email" value="Email" />
                            <InputText
                                id="email"
                                class="w-full"
                                type="email"
                                v-model="form.customer.email"
                                autocomplete="off"
                            />
                            <InputError
                                :message="form.errors['customer.email']"
                            />
                        </div>
                        <!-- <div class="mb-3 w-full">
                            <InputLabel for="phone" value="Phone" />
                            <InputText
                                id="phone"
                                class="w-full"
                                type="text"
                                v-model="form.customer.phone"
                                autocomplete="off"
                            />
                            <InputError
                                :message="form.errors['customer.phone']"
                            />
                        </div> -->
                    </div>
                    <!-- <div class="flex gap-x-4">
                        <div class="mb-3 w-full">
                            <InputLabel for="address" value="Address" />
                            <Textarea
                                id="address"
                                class="w-full"
                                rows="1"
                                autoResize
                                type="text"
                                v-model="form.customer.address"
                                autocomplete="off"
                            />
                            <InputError
                                :message="form.errors['customer.address']"
                            />
                        </div>
                        <div class="mb-3 w-full">
                            <InputLabel for="country" value="Country" />
                            <InputText
                                id="country"
                                class="w-full"
                                type="text"
                                v-model="form.customer.country"
                                autocomplete="off"
                            />
                            <InputError
                                :message="form.errors['customer.country']"
                            />
                        </div>
                    </div> -->
                </Fieldset>
                <Fieldset legend="Order">
                    <div class="mb-4">
                        <InputLabel value="Order date" />
                        <VueDatePicker
                            v-model="form.order_date"
                            :min-date="
                                new Date(Date.now() - 14 * 24 * 60 * 60 * 1000)
                            "
                            hourFormat="12"
                            class="w-full"
                        />
                    </div>
                    <div class="mb-4">
                        <InputLabel
                            for="payment_method"
                            value="Payment method"
                        />
                        <PSelect
                            v-model="form.payment_method"
                            :options="payment_methods"
                            optionLabel="title"
                            optionValue="id"
                            placeholder="Choose payment method"
                            class="w-full"
                        />
                        <InputError :message="form.errors.payment_method" />
                    </div>
                    <div class="">
                        <InputLabel for="noted" value="Internal notes" />
                        <Textarea
                            class="w-full"
                            v-model="form.internal_note"
                            rows="5"
                            cols="30"
                        />
                    </div>
                </Fieldset>

                <div class="mt-5 flex gap-3">
                    <Button
                        type="button"
                        label="Cancel"
                        class="w-full"
                        outlined
                        severity="contrast"
                        @click.prevent="visible = false"
                    />

                    <Button
                        type="submit"
                        label="Confirm"
                        class="w-full"
                        severity="contrast"
                    />
                </div>
            </form>
        </Dialog>
    </Dashboard>
</template>

<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { onMounted, reactive, ref, watch } from "vue";
import Dialog from "primevue/dialog";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import Tag from "primevue/tag";
import _ from "lodash";
import useFormSubmit from "@/composables/useFormSubmit";
import InputLabel from "@/Components/InputLabel.vue";
import Fieldset from "primevue/fieldset";
import Textarea from "primevue/textarea";
import moment from "moment";
import axios from "axios";
import currencyFormat from "@/composables/useCurrencyFormat";
import CustomerAutocomplete from "@/Components/Autocomplete/Item.vue";
import Select from "primevue/select";
const visible = ref(false);

const props = defineProps({
    products: Object,
    payment_methods: Array,
    categories: Array,
});

const toast = useToast();
const confirm = useConfirm();
const customer = ref();
const customers = ref();

const { params } = route();

const payload = reactive({
    keyword: params.keyword || "",
    category_id: Number(params.category_id) || "",
});

const payment_status = ref([
    {
        key: "unpaid",
        name: "Unpaid",
    },
    {
        key: "paid",
        name: "Paid",
    },
]);

const order_status = ref([
    {
        key: "pending",
        name: "Pending",
    },
    {
        key: "confirm",
        name: "Confirm",
    },
    {
        key: "cancel",
        name: "Cancel",
    },
]);

const form = useForm({
    internal_note: "",
    payment_method: "cash_on_arrival",
    customer: {
        first_name: "",
        last_name: "",
        email: "",
        phone: "",
        address: "",
        country: "",
    },
    order_date: new Date(),
    items: [],
});

const { onFormSubmit } = useFormSubmit(form, "orders", false);

const handleAddToCart = async (product) => {
    let findIndex;
    if (product.type == "simple") {
        for (let index = 0; index < product.skus.length; index++) {
            const element = product.skus[index];

            findIndex = form.items.findIndex(
                (el) => el.product_id === product.id && el.sku_id === element.id
            );

            if (findIndex !== -1) {
                let cart = form.items[findIndex];

                if (cart.qty === cart.sku_stock) {
                    toast.add({
                        severity: "warn",
                        summary: "Warning message",
                        detail: `You can only order item quantity ${cart.sku_stock}`,
                        life: 3000,
                    });

                    return;
                }
                form.items[findIndex].qty++;
            } else {
                let price = 0;

                let now = moment().format("YYYY-MM-DD h:i:s");

                switch (element.discount_schedule) {
                    case true:
                        price =
                            now >= element.discount_start_date &&
                            now <= element.discount_end_date
                                ? element.sale_price
                                : element.original_price;
                        break;
                    case false:
                        price = element.sale_price
                            ? element.sale_price
                            : element.original_price;
                        break;
                }

                form.items.push({
                    product_name: product.name,
                    product_id: product.id,
                    qty: 1,
                    sku_id: element.id,
                    sku_code: element.code,
                    sku_stock: element.stock,
                    price: price,
                });
            }
        }

        toast.add({
            severity: "success",
            summary: "Confirmed",
            detail: `Product ${product.name}  was add to cart lists`,
            life: 3000,
        });
    }
};

const handleClearAll = () => {
    confirm.require({
        message: "Do you want to clear this record?",
        header: "Warning",
        icon: "pi pi-info-circle",
        rejectLabel: "Cancel",
        rejectProps: {
            label: "Cancel",
            severity: "secondary",
            outlined: true,
        },
        acceptProps: {
            label: "Sure",
            severity: "danger",
        },
        accept: () => {
            form.items = [];
            toast.add({
                severity: "success",
                summary: "Confirmed",
                detail: "All record are clear",
                life: 3000,
            });
        },
        reject: () => {
            toast.add({
                severity: "info",
                summary: "Rejected",
                detail: "You have rejected",
                life: 3000,
            });
        },
    });
};

const handleProductQty = async (cart, opt) => {
    if (cart.qty > cart.sku_stock) {
        toast.add({
            severity: "warn",
            summary: "Warning message",
            detail: `You can only order item quantity ${cart.sku_stock}`,
            life: 3000,
        });
    }

    if (opt == "add") {
        cart.qty++;
    } else {
        cart.qty--;
    }
};

const handleRemove = (index) => {
    var selected = form.items[index];
    confirm.require({
        message: `Do you want to clear this ${selected.product_name}?`,
        header: "Info",
        icon: "pi pi-info-circle",
        rejectLabel: "Cancel",
        rejectProps: {
            label: "Cancel",
            severity: "secondary",
            outlined: true,
        },
        acceptProps: {
            label: "Sure",
            severity: "danger",
        },
        accept: () => {
            form.items.splice(index, 1);
        },
    });
};

const handleSearchProduct = _.debounce((event) => {
    router.get(route("admin.orders.create"), payload, {
        preserveState: true,
        preserveScroll: true,
        only: ["products"],
    });
}, 500);

const handleCheckout = () => {
    const options = {
        onSuccess: (response) => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `New order was created successful.`,
                life: 3000,
            });
            form.reset();
        },
        onError: (response) => {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: response.error || "Validation Fail!",
                life: 3000,
            });
        },
    };
    onFormSubmit(options, null);
};

onMounted(async () => {
    var result = await axios("/api/v1/customer-search");
    customers.value = await result.data.data;
});

watch(customer, (value) => {
    form.customer.id = value?.id;
    form.customer.first_name = value?.first_name || "";
    form.customer.last_name = value?.last_name || "";
    form.customer.email = value?.email || "";
    form.customer.phone = value?.phone || "";
    form.customer.address = value?.meta?.address || "";
    form.customer.country = value?.meta?.country || "";
});
</script>

<style lang="scss" scoped></style>
