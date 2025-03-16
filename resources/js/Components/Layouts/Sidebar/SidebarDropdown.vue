<script setup lang="ts">
import { useSidebarStore } from "@/stores/sidebar";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const sidebarStore = useSidebarStore();

const props = defineProps(["items", "page"]);
const items = ref(props.items);

const handleItemClick = (index: number) => {
    const pageName =
        sidebarStore.selected === props.items[index].label
            ? ""
            : props.items[index].label;
    sidebarStore.selected = pageName;
};
</script>

<template>
    <ul class="flex flex-col gap-1.5 pl-2 mt-1">
        <template v-for="(childItem, index) in items" :key="index">
            <li v-if="childItem.active">
                <Link
                    preserve-state
                    :href="childItem.route"
                    @click="handleItemClick(index)"
                    class="text-sm group relative flex items-center gap-2.5 rounded-xl py-2 px-3 text-zinc-500 duration-300 ease-in-out hover:text-white hover:bg-primary-dark"
                    :class="{
                        ' !text-white bg-primary-dark':
                            $page.component.startsWith(
                                `Admin/${childItem.path}`
                            ),
                    }"
                >
                    <div class="w-5 text-center">
                        <i :class="childItem.icon"></i>
                    </div>
                    {{ childItem.label }}
                </Link>
            </li>
        </template>
    </ul>
</template>
