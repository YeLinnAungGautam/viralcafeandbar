<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    links: Array,
    state: {
        default: false,
    },
    scroll: {
        default: false,
    },
});
</script>
<template>
    <div v-if="links && links.length" class="flex mt-5 items-center justify-start gap-3">
        <Link
            v-for="link in links"
            :preserve-state="state"
            :preserve-scroll="scroll"
            :key="link"
            :href="link.url || ''"
            @click="$emit('pagination', link.url)"
        >
            <span
                class="relative rounded-full flex justify-center items-center text-center w-5 h-5 p-4 text-slate-500"
                :class="{ 'bg-black': link.active }"
            >
                <span
                    class="absoulute left-0 top-0"
                    :class="{ 'text-white': link.active }"
                >
                    <span class="page-link" v-html="link.label"></span>
                </span>
            </span>
        </Link>
    </div>
</template>
