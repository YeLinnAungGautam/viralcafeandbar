<template>
    <AutoComplete
        forceSelection
        optionLabel="name"
        v-model="model"
        :suggestions="filteredItem"
        @complete="handleFilter"
        class="!w-full"
        inputClass="w-full"
        placeholder="Enter keyword"
    />
</template>

<script setup>
import _ from "lodash";
import axios from "axios";
import AutoComplete from "primevue/autocomplete";
import { ref } from "vue";

const props = defineProps(["url"]);

const filteredItem = ref();

const model = defineModel();

const handleFilter = _.debounce(async (event) => {
    var result = await axios(props.url, {
        params: {
            keyword: event.query,
        },
    });
    filteredItem.value = await result.data.data;
}, 500);
</script>

<style lang="scss" scoped></style>
