<template>
    <div class="mb-3 flex gap-2 items-center justify-end">
        <slot name="other" />

        <Link :href="route(`admin.${label}.index`)">
            <Button
                label="View all"
                icon="fa-solid fa-list"
                severity="contrast"
                size="small"
            />
        </Link>
        <Link :href="route(`admin.${label}.edit`, data.id)">
            <Button
                label="Edit"
                icon="fa-solid fa-edit"
                severity="contrast"
                size="small"
            />
        </Link>
        <PSelect
            v-if="data.translates"
            style="width: 130px"
            v-model="switchLanguage"
            @change="emits('changes', switchLanguage)"
            :options="languages"
            optionValue="code"
            optionLabel="name"
        />
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { useSwitchLanguage } from "@/composables/useSwitchLanguage";

const { languages, switchLanguage } = useSwitchLanguage();

const emits = defineEmits(["changes"]);

defineProps({
    label: String,
    data: Object,
});
</script>

<style lang="scss" scoped></style>
