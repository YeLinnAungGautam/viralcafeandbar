import { ref } from "vue";

export function useTagStatus() {
    const getSeverityForOrder = (status) => {
        switch (status) {
            case "pending":
                return "warn";

            case "confirmed":
                return "success";

            case "cancel":
                return "danger";
        }
    };
    const getSeverityForPayment = (status) => {
        switch (status) {
            case "unpaid":
                return "danger";

            case "paid":
                return "success";
        }
    };
    const orderStatus = ref([
        {
            key: "pending",
            value: "Pending",
        },
        {
            key: "confirmed",
            value: "Confirmed",
        },
        {
            key: "cancel",
            value: "Cancel",
        },
    ]);

    const paymentStatus = ref([
        {
            key: "paid",
            value: "Paid",
        },
        {
            key: "unpaid",
            value: "Unpaid",
        },
    ]);

    return {
        getSeverityForOrder,
        getSeverityForPayment,
        orderStatus,
        paymentStatus,
    };
}
