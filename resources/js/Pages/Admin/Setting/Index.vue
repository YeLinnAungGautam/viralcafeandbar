<template>
    <Dashboard title="Settings">
        <BreadcrumbDefault page-title="Settings" />
        <Tabs value="0">
            <TabList>
                <Tab value="0">App info</Tab>
                <Tab value="2">Contact us</Tab>
                <Tab value="3">Currency options</Tab>
                <Tab value="4">Email template</Tab>
            </TabList>
            <TabPanels>
                <TabPanel value="0">
                    <div class="mb-5">
                        <InputLabel for="name" value="App name" />
                        <InputText
                            id="name"
                            class="w-full"
                            type="text"
                            autocomplete="off"
                            v-model="form.app_name"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel for="mail_email" value="Main email" />
                        <InputText
                            id="mail_email"
                            class="w-full"
                            type="text"
                            autocomplete="off"
                            v-model="form.main_email"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel
                            for="price"
                            value="Enter 1 Reward Point Value"
                        />
                        <InputText
                            id="price"
                            class="w-full"
                            type="number"
                            autocomplete="off"
                            v-model="form.calculate_price"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel for="price" value="Calculation text" />
                        <InputText
                            id="price_txt"
                            class="w-full"
                            autocomplete="off"
                            v-model="form.calculate_txt"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel for="main_logo" value="Main logo" />
                        <InputText
                            id="main_logo"
                            class="w-full"
                            autocomplete="off"
                            type="file"
                            @change="handleMainFileChange"
                        />
                        <div v-if="settings.main_logo" class="mt-3">
                            <Image
                                class="h-32 w-32 object-cover"
                                preview
                                :src="settings.main_logo"
                            />
                        </div>
                    </div>
                    <div class="mb-5">
                        <InputLabel
                            for="secondary_logo"
                            value="Secondary logo"
                        />
                        <InputText
                            id="secondary_logo"
                            class="w-full"
                            autocomplete="off"
                            type="file"
                            @change="handleSecondaryFileChange"
                        />
                        <div v-if="settings.secondary_logo" class="mt-3">
                            <Image
                                class="h-32 w-32 object-cover"
                                preview
                                :src="settings.secondary_logo"
                            />
                        </div>
                    </div>
                </TabPanel>
                <TabPanel value="2">
                    <div class="mb-5">
                        <InputLabel for="map" value="Google map address" />
                        <InputText
                            id="map"
                            class="w-full"
                            type="text"
                            autocomplete="off"
                            v-model="form.google_map"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel for="address" value="Address" />
                        <Textarea
                            id="address"
                            class="w-full"
                            type="text"
                            autoResize
                            rows="3"
                            autocomplete="off"
                            v-model="form.address"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel for="phone" value="Phone" />
                        <InputText
                            id="phone"
                            class="w-full"
                            type="text"
                            autocomplete="off"
                            v-model="form.phone"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel for="email" value="Email" />
                        <InputText
                            id="email"
                            class="w-full"
                            type="email"
                            autocomplete="off"
                            v-model="form.email"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel for="fb" value="Facebook" />
                        <InputText
                            id="fb"
                            class="w-full"
                            autocomplete="off"
                            v-model="form.facebook"
                        />
                    </div>
                </TabPanel>
                <TabPanel value="3">
                    <div class="mb-5">
                        <InputLabel value="Currency" />
                        <PSelect
                            id="attribute"
                            v-model="form.currency"
                            :options="currencyCode"
                            optionLabel="name"
                            optionValue="symbol"
                            placeholder="Select Currency"
                            showClear
                            display="chip"
                            filter
                            class="w-full"
                        />
                        <InputError :message="form.errors.currency" />
                    </div>
                    <!-- <div class="mb-5">
                        <InputLabel for="map" value="Currency position" />
                        <InputText
                            id="map"
                            class="w-full"
                            type="text"
                            autocomplete="off"
                            v-model="form.google_map"
                        />
                    </div> -->
                    <div class="mb-5">
                        <InputLabel
                            for="thousand_separator"
                            value="Thousand separator"
                        />
                        <InputText
                            id="thousand_separator"
                            type="text"
                            autocomplete="off"
                            v-model="form.thousand_separator"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel
                            for="decimal_separator"
                            value="Decimal separator"
                        />
                        <InputText
                            id="decimal_separator"
                            type="text"
                            autocomplete="off"
                            v-model="form.decimal_separator"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel
                            for="number_decimal"
                            value="Number of decimals"
                        />
                        <InputText
                            id="number_decimal"
                            type="text"
                            autocomplete="off"
                            v-model="form.number_decimal"
                        />
                    </div>
                    <div class="flex">
                        <span>Example :</span>
                        <span
                            v-html="currencyFormat(1000000)"
                            class="ms-2 font-bold"
                        >
                        </span>
                    </div>
                </TabPanel>
                <TabPanel value="4">
                    <div class="mb-5">
                        <InputLabel value="OTP" />
                        <PSelect
                            id="attribute"
                            :options="templates"
                            optionLabel="title"
                            optionValue="id"
                            placeholder="Select Template"
                            showClear
                            display="chip"
                            filter
                            class="w-full"
                            v-model="form.otp_template"
                        />
                        <InputError :message="form.errors.otp_template" />
                    </div>
                    <div class="mb-5">
                        <InputLabel value="Order Pending" />
                        <PSelect
                            id="attribute"
                            :options="templates"
                            optionLabel="title"
                            optionValue="id"
                            placeholder="Select Template"
                            showClear
                            display="chip"
                            filter
                            class="w-full"
                            v-model="form.order_pending_template"
                        />
                        <InputError
                            :message="form.errors.order_pending_template"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel value="Order Confirmed" />
                        <PSelect
                            id="attribute"
                            :options="templates"
                            optionLabel="title"
                            optionValue="id"
                            placeholder="Select Template"
                            showClear
                            display="chip"
                            filter
                            class="w-full"
                            v-model="form.order_confirmed_template"
                        />
                        <InputError
                            :message="form.errors.order_confirmed_template"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel value="Order Cancelled" />
                        <PSelect
                            id="attribute"
                            :options="templates"
                            optionLabel="title"
                            optionValue="id"
                            placeholder="Select Template"
                            showClear
                            display="chip"
                            filter
                            class="w-full"
                            v-model="form.order_cancel_template"
                        />
                        <InputError
                            :message="form.errors.order_cancel_template"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel value="Order Footer" />
                        <PSelect
                            id="attribute"
                            :options="templates"
                            optionLabel="title"
                            optionValue="id"
                            placeholder="Select Template"
                            showClear
                            display="chip"
                            filter
                            class="w-full"
                            v-model="form.order_footer_template"
                        />
                        <InputError
                            :message="form.errors.order_footer_template"
                        />
                    </div>
                    <div class="mb-5">
                        <InputLabel value="Terms and Conditions" />
                        <PSelect
                            id="attribute"
                            :options="templates"
                            optionLabel="title"
                            optionValue="id"
                            placeholder="Select Template"
                            showClear
                            display="chip"
                            filter
                            class="w-full"
                            v-model="form.terms_and_conditions_template"
                        />
                        <InputError
                            :message="form.errors.terms_and_conditions_template"
                        />
                    </div>
                </TabPanel>
            </TabPanels>
        </Tabs>
        <div class="mt-3">
            <Button
                severity="contrast"
                size="small"
                type="submit"
                label="Submit"
                @click.prevent="handleSubmit"
            />
        </div>
    </Dashboard>
</template>

<script setup>
import Tabs from "primevue/tabs";
import TabList from "primevue/tablist";
import Tab from "primevue/tab";
import TabPanels from "primevue/tabpanels";
import TabPanel from "primevue/tabpanel";
import { useForm } from "@inertiajs/vue3";
import useFormSubmit from "@/composables/useFormSubmit";
import { useToast } from "primevue/usetoast";
import UploadFile from "@/Components/UploadFIle.vue";
import Image from "primevue/image";
import currencyCode from "@/assets/currency-code.json";
import currencyFormat from "@/composables/useCurrencyFormat";
import Textarea from "primevue/textarea";
import draggable from "vuedraggable";
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";

const toast = useToast();

const props = defineProps({
    settings: Array,
    templates: Array,
});

const form = useForm({
    app_name: props.settings?.app_name ?? import.meta.env.VITE_APP_NAME,
    calculate_price: props.settings?.calculate_price ?? 1000000,
    calculate_txt: props.settings?.calculate_txt ?? "",
    main_logo: props.settings?.main_logo ?? "",
    secondary_logo: props.settings?.secondary_logo ?? "",
    address: props.settings?.address ?? "",
    phone: props.settings?.phone ?? "",
    email: props.settings?.email ?? "",
    facebook: props.settings?.facebook ?? "",
    google_map: props.settings?.google_map ?? "",
    currency: props.settings?.currency ?? null,
    thousand_separator: props.settings?.thousand_separator ?? null,
    decimal_separator: props.settings?.decimal_separator ?? null,
    number_decimal: props.settings?.number_decimal ?? null,
    main_email: props.settings?.main_email ?? null,
    otp_template: Number(props.settings?.otp_template) ?? null,
    order_pending_template:
        Number(props.settings?.order_pending_template) ?? null,
    order_confirmed_template:
        Number(props.settings?.order_confirmed_template) ?? null,
    order_cancel_template:
        Number(props.settings?.order_cancel_template) ?? null,
    order_footer_template:
        Number(props.settings?.order_footer_template) ?? null,
    terms_and_conditions_template: Number(
        props.settings?.terms_and_conditions_template ?? null
    ),
});

const handleNew = () => {
    form.promotion_labels.push({
        name: "",
    });
};

const handleRemove = (index) => {
    form.promotion_labels.splice(index, 1);
};

const handleMainFileChange = (event) => {
    const file = event.target.files[0];
    form.main_logo = file;
};

const handleSecondaryFileChange = (event) => {
    const file = event.target.files[0];
    form.secondary_logo = file;
};

const { onFormSubmit } = useFormSubmit(form, "settings", true);

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Settings updated successful.`,
                life: 3000,
            });
        },
    };
    onFormSubmit(options);
};
</script>

<style lang="scss" scoped></style>
