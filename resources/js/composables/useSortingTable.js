import { ref } from "vue";
import _ from "lodash";
import { router } from "@inertiajs/vue3";

// const onSort = () => {
const sortOrder = ref();
const sortField = ref();

const useSortingTable = (url) => {
    const onSort = (event) => {
        sortOrder.value = event.sortOrder;
        sortField.value = event.sortField;

        var sort_by;
        switch (event.sortOrder) {
            case 1:
                sort_by = "asc";
                break;
            case -1:
                sort_by = "desc";
                break;
            default:
                sort_by = null;
                break;
        }

        // if (event.sortOrder && event.sortField) {
        _.delay(() => {
            router.get(
                url,
                {
                    sort_by: sort_by,
                    column: event.sortField,
                },
                {
                    preserveState: true,
                }
            );
        }, 300);
        // }
    };

    return { onSort, sortOrder, sortField };
};

// };

// export { sortOrder, sortField };

export default useSortingTable;
