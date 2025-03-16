<template>
    <div
        class="mt-5 text-end sticky bottom-0 bg-white bg-opacity-90 backdrop-blur-sm py-3 px-3 rounded-lg"
    >
        <Link :href="route(`admin.${label}.index`)">
            <Button label="Back To List" size="small" severity="secondary"
        /></Link>
        <Button
            @click.prevent="emits('submit')"
            type="submit"
            label="Submit"
            size="small"
            class="ms-3"
            severity="contrast"
        />
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
const emits = defineEmits(["submit", "keyup"]);
defineProps({
    label: String,
});
const back = () => {
    window.history.back();
};
</script>
