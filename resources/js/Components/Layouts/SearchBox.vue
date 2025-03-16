<template>
    <div class="flex justify-between items-center my-5">
        <div class="flex gap-2 items-center">
            <Link
                v-if="hasPermission(permission)"
                :href="route(`admin.${label}.create`)"
                class="btn-primary"
            >
                Create
            </Link>
            <div v-show="excel" class="flex justify-end items-center gap-x-3">
                <template v-if="true">
                    <div class="">
                        <a
                            class="btn-primary me-3"
                            :href="
                                route('admin.excel-import.index', 'products')
                            "
                        >
                            Import Product
                        </a>
                        <!-- <a
                        class="btn-primary"
                        :href="route('admin.products.export')"
                    >
                        Export Product
                    </a> -->
                    </div>
                </template>
            </div>
        </div>
        <div class="flex justify-end">
            <IconField>
                <InputIcon class="pi pi-search" />
                <InputText
                    size="small"
                    type="text"
                    v-model="searchKey"
                    @input.prevent="emits('search', searchFunction)"
                    placeholder="Search...."
                    :show-clear-button="false"
                />
            </IconField>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import _ from "lodash";
import { Link, router, usePage } from "@inertiajs/vue3";
import InputIcon from "primevue/inputicon";
import IconField from "primevue/iconfield";
import { useRolePermission } from "@/composables/useRolePermission";

const props = defineProps({
    label: String,
    permission: String,
    excel: {
        type: Boolean,
        default: false,
    },
});
const { hasPermission } = useRolePermission();
const page = usePage();

const emits = defineEmits(["search"]);
const searchKey = ref("");
const searchFunction = _.debounce(() => {
    router.get(
        route(`admin.${props.label}.index`),
        {
            keyword: searchKey.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}, 300);
onMounted(() => {
    searchKey.value = route().params.keyword;
});
</script>

<style lang="scss" scoped></style>
