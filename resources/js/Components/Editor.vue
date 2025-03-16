<template>
    <ckeditor
        :editor="editor"
        v-model="model"
        :config="editorConfig"
        @input="emits('handleInput')"
    ></ckeditor>
</template>

<script setup>
import { ref } from "vue";

import "ckeditor5/ckeditor5.css";

import {
    ClassicEditor,
    Bold,
    Essentials,
    Italic,
    Paragraph,
    Undo,
    Table,
    TableToolbar,
    TableProperties,
    TableCellProperties,
    List,
    Link,
    Heading,
    HorizontalLine,
    Font,
} from "ckeditor5";

const model = defineModel();

const editor = ref(ClassicEditor);
const editorConfig = ref({
    placeholder: "Type the content here!",
    plugins: [
        Bold,
        Essentials,
        Italic,
        Paragraph,
        Undo,
        Table,
        TableToolbar,
        TableProperties,
        TableCellProperties,
        List,
        Link,
        Heading,
        HorizontalLine,
        Font,
    ],
    toolbar: [
        "heading",
        "|",
        "bold",
        "italic",
        "link",
        "bulletedList",
        "numberedList",
        "|",
        "insertTable",
        "|",
        "undo",
        "redo",
        "horizontalLine",
        "fontColor",
    ],
    fontColor: {
        colors: [
            {
                color: "black",
                label: "Black",
            },
            {
                color: "red",
                label: "Red",
            },
        ],
        columns: 5,
    },
    table: {
        contentToolbar: [
            "tableColumn",
            "tableRow",
            "mergeTableCells",
            "tableProperties",
            "tableCellProperties",
        ],
    },
});

const emits = defineEmits(["handleInput"]);
</script>

<style scoped>
.ck-editor__editable_inline {
    min-height: 500px;
}
</style>
