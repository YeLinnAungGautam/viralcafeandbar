<template>
    <div class="bg-white py-4">
        <div class="w-full my-center flex justify-center items-center gap-2">
            © {{ $page.props.site_name }} {{ moment().format("Y") }}. All Rights
            Reserved. Created by
            <a target="_blank" href="https://digitaldots.com.mm">
                <img
                    class="h-6"
                    src="@/assets/image/dd.png"
                    alt="Digital Dots"
                />
            </a>
        </div>
    </div>
</template>

<script setup>
import moment from "moment";

defineProps({
    site_name: String,
});
</script>

<style lang="scss" scoped></style>
