<script setup>
import HeaderArea from "@/Components/Layouts/Header/HeaderArea.vue";
import SidebarArea from "@/Components/Layouts/Sidebar/SidebarArea.vue";
import Toast from "primevue/toast";
import { Head } from "@inertiajs/vue3";
import { onMounted } from "vue";
import Footer from "@/Components/Layouts/Footer/Footer.vue";
import ConfirmDialog from "primevue/confirmdialog";
// import useNotification from "@/composables/useNotification";
// const { getNotificationPermission } = useNotification();
// getNotificationPermission();

defineProps({
    title: String,
});
</script>

<template>
    <Toast />
    <ConfirmDialog />

    <Head :title="title" />
    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
        <!-- ===== Sidebar Start ===== -->
        <SidebarArea />
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div
            class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden"
        >
            <!-- ===== Header Start ===== -->
            <HeaderArea />
            <!-- ===== Header End ===== -->

            <!-- ===== Main Content Start ===== -->
            <main class="min-h-full">
                <div
                    class="min-h-full mx-auto max-w-screen-xl p-4 md:p-6 2xl:p-10"
                >
                    <slot class="min-h-full"></slot>
                </div>
                <Footer />
            </main>
            <!-- ===== Main Content End ===== -->
        </div>
    </div>
    <!-- ===== Page Wrapper End ===== -->
</template>
