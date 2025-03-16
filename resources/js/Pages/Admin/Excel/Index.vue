<script lang="ts" setup>
import useFormSubmit from "@/composables/useFormSubmit";
import { router, useForm } from "@inertiajs/vue3";
import FileUpload from "primevue/fileupload";
import { useToast } from "primevue/usetoast";
import { ref } from "vue";

defineProps({
    title: String,
    errors: Object,
});

const form = useForm({
    uploaded: null,
    images: null,
});

const fileupload = ref(undefined);
const images = ref();
const showImg = ref([]);
const showExcel = ref(null);
const toast = useToast();

const fileChange = () => {
    if (showImg.value.length > 0) {
        showImg.value = [];
    }
    const files = Array.from(images.value.files).map((file) => {
        showImg.value = [...showImg.value, file];
    });
};

const upload = () => {
    const options = {
        onSuccess: () => {
            form.reset();
            showImg.value = [];
            showExcel.value = null;
            toast.add({
                severity: "success",
                summary: "Success",
                detail: `Excel import is done successfully..`,
                life: 3000,
            });
        },
        onError: (errors) => {
            form.errors = errors;
        },
    };

    // console.log(fileupload.value.files, images.value.files);
    // return;
    router.post(
        "/admin/products/excel-import",
        {
            uploaded: fileupload.value.files[0],
            images: images.value.files,
        },
        options
    );
};
</script>

<template>
    <Dashboard title="Products">
        <section class="mx-auto">
            <div class="mb-5">Excel Import</div>
            <form
                method="post"
                class="bg-white px-5 py-6 rounded-md shadow-md mb-14"
                enctype="multipart/form-data"
            >
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input
                            id="image-upload"
                            ref="images"
                            type="file"
                            name="image-upload"
                            accept="image/png, image/jpeg"
                            multiple
                            hidden
                            @change="fileChange"
                        />
                        <label
                            for="image-upload"
                            class="btn-primary capitalize"
                        >
                            <i class="fa-solid fa-upload me-2"></i> Image
                            Upload</label
                        >
                        <div class="mt-2">
                            <span
                                class="block"
                                v-for="(img, i) in showImg"
                                :key="i"
                            >
                                {{ img.name }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <input
                            id="excel-upload"
                            ref="fileupload"
                            type="file"
                            name="excel-upload"
                            accept=".xls, .xlsx"
                            hidden
                            @change="
                                () => {
                                    showExcel = fileupload?.files[0]?.name;
                                }
                            "
                        />
                        <label
                            for="excel-upload"
                            class="btn-primary capitalize"
                        >
                            <i class="fa-solid fa-upload me-2"></i>
                            Upload Excel File</label
                        >
                        <div class="mt-2">
                            <span class="block">
                                {{ showExcel }}
                            </span>
                        </div>
                        <div class="text-sm mt-3 text-slate-500">
                            Noted: all format need to be same Example
                        </div>
                    </div>

                    <!-- <div>
                        <div class="flex">
                            <FileUpload
                                ref="fileupload"
                                mode="basic"
                                name="demo[]"
                                url="/admin/excel-import"
                                accept=".xls, .xlsx"
                                :maxFileSize="1000000"
                                chooseLabel="Upload File"
                            />
                        </div>

                        <div class="text-sm mt-3 text-slate-500">
                            Noted: all format need to be same Example
                        </div>
                    </div> -->
                </div>
                <div class="mt-5">
                    <Button
                        @click.prevent="upload"
                        type="submit"
                        label="Submit"
                        size="small"
                        severity="contrast"
                    />
                    <!-- <FormButton class="me-3" text="Import" /> -->
                    <!-- <a :href="`/${title}.xlsx`"> Download Example </a> -->
                </div>
            </form>
        </section>
        <div class="mt-4">
            <h6 class="text-sm mb-2 uppercase">Error logs</h6>
            <div class="bg-white rounded p-2 h-60 max-h-60 overflow-y-auto">
                <div v-for="(error, index) in errors" :key="index">
                    <InputError :message="error" />
                </div>
            </div>
        </div>
    </Dashboard>
</template>
<style>
.p-button {
    background-color: black !important;
    border: 1px solid black !important;
}
</style>
