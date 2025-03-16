import { router } from "@inertiajs/vue3";

export default function useFormSubmit(form, uri = null, hasFile = false) {
    const onFormSubmit = (options, edit, url = uri, clone = false) => {
        if (!edit) {
            form.post(route(`admin.${url}.store`), options);
        } else if (edit && clone) {
            form.post(route(`admin.${url}.store`), options);
        } else if (edit) {
            editFormRequest(edit, options, url);
        }
    };

    function editFormRequest(edit, options, url) {
        let routeName = route(`admin.${url}.update`, edit.id);
        if (hasFile) {
            router.post(
                routeName,
                {
                    _method: "put",
                    ...form,
                },
                options
            );
        } else {
            form.put(routeName, options);
        }
    }

    return { onFormSubmit };
}
