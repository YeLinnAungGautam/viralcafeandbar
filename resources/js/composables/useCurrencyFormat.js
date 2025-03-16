import { usePage } from "@inertiajs/vue3";

export default function currencyFormat(value, hasCurrency = true) {
    const { props } = usePage();
    const { settings } = props;

    if (value !== null && value !== undefined) {
        let parts = parseFloat(value)
            .toFixed(settings?.number_decimal)
            .split(".");

        var thousand_separator =
            settings.thousand_separator == null
                ? ""
                : settings.thousand_separator;

        parts[0] = parts[0].replace(
            /\B(?=(\d{3})+(?!\d))/g,
            thousand_separator
        );

        var decimal =
            settings.decimal_separator == null
                ? ""
                : settings.decimal_separator;

        var currency = hasCurrency
            ? settings.currency == null
                ? ""
                : settings.currency
            : "";

        return (
            parts.join(decimal) +
            `<span class="currency"> ${currency}</span>`
        );
    }

    return value;
}
