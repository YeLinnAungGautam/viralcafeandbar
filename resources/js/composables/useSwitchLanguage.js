import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";

export function useSwitchLanguage() {
const switchLanguage = ref("en");

const page = usePage();
const { languages } = page.props;

return { switchLanguage, languages };
}