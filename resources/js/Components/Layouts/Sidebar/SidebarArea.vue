<script setup>
import { useSidebarStore } from "@/stores/sidebar";
import { onClickOutside } from "@vueuse/core";
import { ref } from "vue";
import SidebarItem from "../Sidebar/SidebarItem.vue";
import { useRolePermission } from "@/composables/useRolePermission";
const { hasPermission, hasRole } = useRolePermission();

const target = ref(null);

const sidebarStore = useSidebarStore();

onClickOutside(target, () => {
    sidebarStore.isSidebarOpen = false;
});

const menuGroups = ref([
    {
        name: "Main Menu",
        active: true,
        menuItems: [
            {
                icon: `fa-solid fa-house`,
                label: "Dashboard",
                path: "Dashboard",
                route: route("dashboard"),
                active: hasPermission("dashboard_access"),
            },
            {
                icon: `fa-solid fa-scale-balanced`,
                label: "Orders",
                path: "Order",
                route: route("admin.orders.index"),
                active: hasPermission("order_access"),
            },
            {
                icon: `fa-solid fa-cube`,
                label: "Products",
                route: "#",
                active: true,
                children: [
                    {
                        icon: `fa-solid fa-tags`,
                        label: "All Products",
                        path: "Product",
                        route: route("admin.products.index"),
                        active: hasPermission("product_access"),
                    },
                    {
                        icon: `fa-solid fa-box`,
                        label: "Categories",
                        path: "Category",
                        route: route("admin.categories.index"),
                        active: hasPermission("category_access"),
                    },
                    {
                        icon: `fa-solid fa-tag`,
                        label: "Tags",
                        path: "Tag",
                        route: route("admin.tags.index"),
                        active: hasPermission("tag_access"),
                    },
                ],
            },
            {
                icon: `fa-solid fa-user-group`,
                label: "Customers",
                path: "Customer",
                route: route("admin.customers.index"),
                active: hasPermission("customer_access"),
            },
            {
                icon: `fa-solid fa-sheet-plastic`,
                label: "Reports",
                path: "Reports",
                route: route("admin.reportings.index"),
                active: hasPermission("order_access"),
            },
            {
                icon: `fa-solid fa-file-lines`,
                label: "Pages",
                path: "Page",
                route: route("admin.pages.index"),
                active: hasPermission("page_access"),
            },
        ],
    },
    {
        name: "Marketing",
        active: hasPermission("marketing_management"),
        menuItems: [
            {
                icon: `fa-solid fa-gem`,
                label: "Memberships",
                path: "Membership",
                route: route("admin.memberships.index"),
                active: hasPermission("membership_access"),
            },
            {
                icon: `fa-solid fa-square-pen`,
                label: "Taglines",
                path: "CatchLine",
                route: route("admin.tag-lines.index"),
                active: hasPermission("tag_line_access"),
            },

            {
                icon: `fa-solid fa-bullhorn`,
                label: "Notifications",
                path: "Notification",
                route: route("admin.notifications.index"),
                active: hasPermission("promotion_access"),
            },
            {
                icon: `fa-solid fa-award`,
                label: "Banners",
                path: "Banner",
                route: route("admin.banners.index"),
                active: hasPermission("banner_access"),
            },
        ],
    },
    {
        name: "Settings",
        active: hasPermission("setting_management"),
        menuItems: [
            {
                icon: `fa-solid fa-sliders`,
                label: "Store Settings",
                path: "Setting",
                route: route("admin.settings.index"),
                active: hasPermission("setting_access"),
            },
            {
                icon: `fa-solid fa-calculator`,
                label: "Templates",
                path: "Template",
                route: route("admin.templates.index"),
                active: hasPermission("template_access"),
            },
            {
                icon: `fa-solid fa-money-bill`,
                label: "Payments",
                active: hasPermission("payment_management_access"),
                route: "#",
                children: [
                    {
                        icon: `fa-solid fa-dollar-sign`,
                        label: "Methods",
                        path: "PaymentMethod",
                        route: route("admin.payments.index"),
                        active: hasPermission("paymentmethod_access"),
                    },
                    {
                        icon: `fa-brands fa-cc-amazon-pay`,
                        label: "Banks",
                        path: "PaymentInfo",
                        route: route("admin.payment-infos.index"),
                        active: hasPermission("paymentinfo_access"),
                    },
                    {
                        icon: `fa-solid fa-file-invoice`,
                        label: "Accounts",
                        path: "PaymentAccount",
                        route: route("admin.payment-accounts.index"),
                        active: hasPermission("paymentaccount_access"),
                    },
                ],
            },
            {
                icon: `fa-solid fa-user`,
                label: "All Users",
                path: "User",
                route: route("admin.users.index"),
                active: hasRole("admin"),
            },
            {
                icon: `fa-solid fa-users`,
                label: "Users",
                route: "#",
                active: hasRole("superadmin"),
                children: [
                    {
                        icon: `fa-solid fa-user`,
                        label: "All Users",
                        path: "User",
                        route: route("admin.users.index"),
                        active: hasPermission("user_access"),
                    },
                    {
                        icon: `fa-solid fa-shield`,
                        label: "Roles",
                        path: "Role",
                        route: route("admin.roles.index"),
                        active: hasPermission("role_access"),
                    },
                    {
                        icon: `fa-solid fa-user-shield`,
                        label: "Permissions",
                        path: "Permission",
                        route: route("admin.permissions.index"),
                        active: hasPermission("permission_access"),
                    },
                ],
            },
            {
                icon: "fa-solid fa-braille",
                label: "Translations",
                route: "#",
                active: hasPermission("translation_access"),
                children: [
                    {
                        icon: "fa-solid fa-globe",
                        label: "Languages",
                        path: "Language",
                        route: route("admin.languages.index"),
                        active: hasPermission("language_access"),
                    },
                    {
                        icon: "fa-solid fa-language",
                        label: "Localization",
                        path: "Localization",
                        route: route("admin.localizations.index"),
                        active: hasPermission("localization_access"),
                    },
                ],
            },
            {
                icon: `fa-solid fa-list`,
                label: "Activity Logs",
                path: "ActivityLog",
                route: route("admin.activity-logs.index"),
                active: hasPermission("activity_log_access"),
            },
        ],
    },
]);
</script>

<template>
    <aside
        class="absolute left-0 top-0 z-30 flex h-screen w-60 flex-col overflow-y-hidden bg-white duration-300 ease-linear dark:bg-boxdark lg:static lg:translate-x-0"
        :class="{
            'translate-x-0': sidebarStore.isSidebarOpen,
            '-translate-x-full': !sidebarStore.isSidebarOpen,
        }"
        ref="target"
    >
        <!-- SIDEBAR HEADER -->
        <div class="flex items-center justify-between gap-2 px-6 pt-2 pb-0">
            <a href="/">
                <img
                    src="@/assets/image/logo.png"
                    class="w-1/2 m-auto"
                    alt=""
                />
            </a>

            <button
                class="block lg:hidden"
                @click="sidebarStore.isSidebarOpen = false"
            >
                <svg
                    class="fill-current"
                    width="20"
                    height="18"
                    viewBox="0 0 20 18"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M19 8.175H2.98748L9.36248 1.6875C9.69998 1.35 9.69998 0.825 9.36248 0.4875C9.02498 0.15 8.49998 0.15 8.16248 0.4875L0.399976 8.3625C0.0624756 8.7 0.0624756 9.225 0.399976 9.5625L8.16248 17.4375C8.31248 17.5875 8.53748 17.7 8.76248 17.7C8.98748 17.7 9.17498 17.625 9.36248 17.475C9.69998 17.1375 9.69998 16.6125 9.36248 16.275L3.02498 9.8625H19C19.45 9.8625 19.825 9.4875 19.825 9.0375C19.825 8.55 19.45 8.175 19 8.175Z"
                        fill=""
                    />
                </svg>
            </button>
        </div>
        <!-- SIDEBAR HEADER -->

        <div
            class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear"
        >
            <!-- Sidebar Menu -->
            <nav class="py-4 px-4 lg:px-6">
                <template v-for="menuGroup in menuGroups" :key="menuGroup.name">
                    <div v-if="menuGroup.active">
                        <h3
                            class="mb-2 ml-2 text-sm font-medium text-bodydark2"
                        >
                            {{ menuGroup.name }}
                        </h3>

                        <ul class="mb-4 flex flex-col gap-1.5">
                            <SidebarItem
                                v-for="(menuItem, index) in menuGroup.menuItems"
                                :item="menuItem"
                                :key="index"
                                :index="index"
                            />
                        </ul>
                    </div>
                </template>
            </nav>
            <!-- Sidebar Menu -->
        </div>
    </aside>
</template>
