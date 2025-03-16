<template>
    <div id="id">
        <FilePond
            :id="id"
            name="files"
            ref="pond"
            :labelIdle="`<i class='pi pi-cloud-upload'></i>`"
            :acceptedFileTypes="acceptedFileTypes"
            :server="serverConfig"
            :files="internalFiles"
            :allow-multiple="multiple"
            :allowReorder="true"
            :max-file-size="'3MB'"
            :image-preview-height="200"
            :image-resize-target-width="200"
            :image-resize-target-height="200"
            :image-preview-crop="true"
            @init="handleFilePondInit"
            @processfile="handleFileUpload"
            @removefile="handleFileRemove"
            @updatefiles="handleFileUpdate"
            @reorderfiles="handleReorderFiles"
        />
        <Dialog
            v-model:visible="open"
            modal
            header="Confirmation"
            :style="{ width: '300px' }"
            id="form-header"
        >
            <h3 class="px-2">Are you sure to delete !</h3>
            <div class="card mt-5 flex flex-wrap gap-2 justify-end">
                <Button
                    label="Cancel"
                    size="small"
                    icon="pi pi-times"
                    severity="secondary"
                    @click="
                        () => {
                            open = false;
                        }
                    "
                ></Button>
                <Button
                    label="Confirm"
                    size="small"
                    icon="pi pi-check"
                    severity="danger"
                    @click="
                        () => {
                            deleteOption = true;
                            handleDelete(error, dialogFile);
                        }
                    "
                ></Button>
            </div>
        </Dialog>
    </div>
</template>

<script setup>
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import "filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css";

import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFilePoster from "filepond-plugin-file-poster";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";

import { ref } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import { onClickOutside } from "@vueuse/core";

const page = usePage();

var { languages, api_token } = page.props;

const FilePond = vueFilePond(
    FilePondPluginImagePreview,
    FilePondPluginFilePoster,
    FilePondPluginFileValidateType
);

const props = defineProps({
    modelValue: {
        type: [String, Array], // Assuming modelValue can be a string or an array for file input
        default: "", // Default to an empty string or array
    },
    isEdit: {
        type: [Boolean, Object],
        default: false, // Default value for 'isEdit' prop
    },
    multiple: {
        type: Boolean,
        default: false, // Default value for 'multiple' prop
    },
    acceptedFileTypes: {
        type: Array,
        default: () => ["image/jpeg", "image/png"], // Default file types
    },
    id: {
        type: String,
        default: "two-col",
    },
});

const emits = defineEmits(["update:modelValue"]);

const internalFiles = ref([]);
const filesRes = ref([]);
const pond = ref();
const open = ref(false);
const deleteOption = ref(false);
const dialogFile = ref({});
const actionDone = ref({
    start: 0,
    done: 0,
});

const serverConfig = {
    process: {
        url: "/api/media",
        method: "POST",
        withCredentials: false,
        timeout: 7000,
        headers: {
            "API-TOKEN": api_token,
        },
        onload: (response) => {
            const result = JSON.parse(response);
            filesRes.value.push(result);
            emits(
                "update:modelValue",
                filesRes.value.map((f) => f.name)
            );
            return result.name; // Return the unique file ID
        },
        onerror: (response) => response.data,
    },
    revert: async (uniqueFileId, load, error) => {
        try {
            await axios.delete(`/api/media/${uniqueFileId}`, {
                headers: {
                    "API-TOKEN": api_token,
                },
            });
            filesRes.value = filesRes.value.filter(
                (f) => f.name !== uniqueFileId
            );
            emits(
                "update:modelValue",
                filesRes.value.map((f) => f.name)
            );
            load();
        } catch (error) {
            console.error(error);
        }
    },
};

function handleFilePondInit() {
    if (props.modelValue) {
        if (props.multiple) {
            internalFiles.value = props.modelValue.map((item) => ({
                source: item.url,
                options: {
                    type: "local",
                    name: item.file_name,
                    metadata: {
                        poster: item.url,
                    },
                },
            }));
            emits(
                "update:modelValue",
                props.modelValue.map((item) => item.file_name)
            );
        } else {
            if (props.modelValue.url) {
                internalFiles.value = [
                    {
                        source: props.modelValue.url ?? null,
                        options: {
                            type: "local",
                            name: props.modelValue.file_name ?? "default",
                            metadata: {
                                poster: props.modelValue.url ?? null,
                            },
                        },
                    },
                ];
                emits("update:modelValue", props.modelValue.file_name);
            }
        }
    }
}

function handleFileUpload(error, file) {
    console.log(file.file);

    if (error) {
        console.error("File upload error:", error);
    }
}

function handleReorderFiles(files) {
    const response = files.map((file) => file.file.name);

    if (!props.isEdit) {
        const data = sortFiles(filesRes.value, response);
        emits(
            "update:modelValue",
            data.map((item) => item.name)
        );
    } else {
        const updatedResponse = response.map((item) => {
            const found = filesRes.value.find(
                (obj) => obj.original_name === item
            );
            return found ? found.name : item;
        });
        emits("update:modelValue", updatedResponse);
    }
}

function sortFiles(files, sortOrder) {
    return files.sort(
        (a, b) =>
            sortOrder.indexOf(a.original_name) -
            sortOrder.indexOf(b.original_name)
    );
}

function handleFileRemove(error, file) {
    if (actionDone.value.done !== actionDone.value.start) {
        actionDone.value.done = 0;
        console.log("done second");
        return;
    }

    const upload = internalFiles.value.find((d) => d.source === file.source);
    if (!upload) return;

    const newFilesArray = [...internalFiles.value];
    internalFiles.value = newFilesArray;
    open.value = true;

    dialogFile.value = file;
}

const handleDelete = async (error, file) => {
    if (!deleteOption.value) return;
    actionDone.value.done++;

    try {
        await axios.delete(`/api/media/${file.file.name}`, {
            headers: { "API-TOKEN": api_token },
            data: { is_action: true },
        });
        internalFiles.value = internalFiles.value.filter(
            (d) => d.source !== file.source
        );
    } catch (error) {
        console.error("Error deleting file:", error);
    } finally {
        open.value = false;
        deleteOption.value = false;
        dialogFile.value = {};
    }
};

function handleFileUpdate(remove, files) {
    if (!remove) return;
}
onClickOutside(open, () => {
    open.value = false;
});
</script>

<style scoped lang="css"></style>
