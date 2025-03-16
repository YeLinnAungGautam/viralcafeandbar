<script setup>
import { Link } from "@inertiajs/vue3";

import { useSidebarStore } from "@/stores/sidebar";
// import { useRoute } from "vue-router";
import SidebarDropdown from "./SidebarDropdown.vue";
import _ from "lodash";

const sidebarStore = useSidebarStore();

const props = defineProps(["item", "index", "active"]);

// interface SidebarItem {
//     label: string;
// }

const handleItemClick = () => {
    const pageName =
        sidebarStore.page === props.item.label ? "" : props.item.label;
    sidebarStore.page = pageName;

    if (props.item.children) {
        return props.item.children.some(
            (child) => sidebarStore.selected === child.label
        );
    }
};
</script>

<template>
    <li v-if="item.active">
        <Link
            preserve-scroll
            :href="item.route"
            class="text-sm group relative flex items-center gap-2 rounded-xl py-2 px-3 text-zinc-500 duration-300 ease-in-out hover:text-white hover:bg-primary-dark"
            :class="{
                ' !text-white !bg-primary-dark': $page.component.startsWith(
                    `Admin/${item.path}`
                ),
            }"
            @click="handleItemClick"
        >
            <div class="w-5 text-center">
                <i :class="item.icon"></i>
            </div>

            <div>
                {{ item.label }}
            </div>

            <svg
                v-if="item.children"
                class="absolute right-2 top-1/2 -translate-y-1/2 transition duration-500 fill-current"
                :class="{
                    'rotate-180': sidebarStore.page === item.label,
                    'transition duration-500': sidebarStore.page === item.label,
                }"
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M4.41107 6.9107C4.73651 6.58527 5.26414 6.58527 5.58958 6.9107L10.0003 11.3214L14.4111 6.91071C14.7365 6.58527 15.2641 6.58527 15.5896 6.91071C15.915 7.23614 15.915 7.76378 15.5896 8.08922L10.5896 13.0892C10.2641 13.4147 9.73651 13.4147 9.41107 13.0892L4.41107 8.08922C4.08563 7.76378 4.08563 7.23614 4.41107 6.9107Z"
                    fill=""
                />
            </svg>
        </Link>

        <div class="flex flex-col gap-1.5">
            <SidebarDropdown
                class="dropdown"
                :class="{ 'dropdown-show': sidebarStore.page === item.label }"
                :items="item.children"
                :page="item.label"
            />
        </div>
    </li>
</template>
<style>
.dropdown {
    overflow: hidden;
    max-height: 0;
    opacity: 0;
    transition: max-height 0.7s ease, opacity 0.7s ease !important;
}

.dropdown-show {
    max-height: 200px;
    opacity: 1;
}
</style>
