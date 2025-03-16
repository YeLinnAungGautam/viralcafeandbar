import "./bootstrap";
import "../css/app.css";

import "primeicons/primeicons.css";

import "@vuepic/vue-datepicker/dist/main.css";

import { createApp, h } from "vue";
import { createPinia } from "pinia";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import Dashboard from "@/Layouts/Dashboard.vue";
import InputText from "primevue/inputtext";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Checkbox from "primevue/checkbox";
import BreadcrumbDefault from "@/Components/Breadcrumbs/BreadcrumbDefault.vue";
import PrimeVue from "primevue/config";
import Aura from "@primevue/themes/aura";
import Select from "primevue/select";
import DatePicker from "primevue/datepicker";
import Button from "primevue/button";
import ToastService from "primevue/toastservice";
import TableActions from "@/Components/Layouts/TableActions.vue";
import Ripple from "primevue/ripple";
import Pagination from "@/Components/Pagination.vue";
import { definePreset } from "@primevue/themes";
import ConfirmationService from "primevue/confirmationservice";
import CKEditor from "@ckeditor/ckeditor5-vue";
import FormButton from "@/Components/Layouts/FormButton.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import VueApexCharts from "vue3-apexcharts";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

const MyPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: "{blue.50}",
            100: "{blue.100}",
            200: "{blue.200}",
            300: "{blue.300}",
            400: "{blue.400}",
            500: "{blue.500}",
            600: "{blue.600}",
            700: "{blue.700}",
            800: "{blue.800}",
            900: "{blue.900}",
            950: "{blue.950}",
        },
    },
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(createPinia())
            .use(PrimeVue, {
                ripple: true,
                theme: {
                    preset: MyPreset,
                    options: {
                        prefix: "p",
                        darkModeSelector: "class",
                        cssLayer: false,
                    },
                },
            })
            .use(ToastService)
            .use(ConfirmationService)
            .use(CKEditor)
            .use(VueApexCharts)
            .component("Dashboard", Dashboard)
            .component("InputText", InputText)
            .component("InputError", InputError)
            .component("PrimaryButton", PrimaryButton)
            .component("Checkbox", Checkbox)
            .component("InputLabel", InputLabel)
            .component("BreadcrumbDefault", BreadcrumbDefault)
            .component("PSelect", Select)
            .component("DatePicker", DatePicker)
            .component("Button", Button)
            .component("TableActions", TableActions)
            .component("Pagination", Pagination)
            .component("FormButton", FormButton)
            .component("VueDatePicker", VueDatePicker)
            .directive("ripple", Ripple)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
