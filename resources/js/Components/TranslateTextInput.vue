<template>
    <div class="mb-5" v-for="(translate, index) in translates" :key="index">
        <InputGroup v-if="translate.label != 'en'">
            <InputGroupAddon>
                <span class="uppercase">
                    {{ translate.label }}
                </span>
            </InputGroupAddon>
            <InputText v-model="translate.name" autocomplete="off" />
            <Button
                @click.prevent="handleDelete(index)"
                icon="fa-solid fa-trash"
                severity="danger"
            />
        </InputGroup>
    </div>
</template>

<script setup>
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";

const props = defineProps({
    translates: Array,
});

const emits = defineEmits(["remove"]);

const handleDelete = (index) => {
    if (props.translates[index]?.id) {
        router.delete(
            route("admin.translates.delete", props.translates[index].id),
            {
                onSuccess: () => {
                    toast.add({
                        severity: "success",
                        summary: "Success",
                        detail: "Deleted successful.",
                        life: 3000,
                    });
                },
            }
        );
    }

    props.translates.splice(index, 1);
};
</script>

<style lang="scss" scoped></style>
