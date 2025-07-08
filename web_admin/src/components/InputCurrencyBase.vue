<template>
    <input
        ref='inputRef'
        type='text'
        v-bind='$attrs'
    />
</template>

<script>
import { useCurrencyInput } from 'vue-currency-input';

export default {
    props: {
        modelValue: [Number, String],
    },
    setup() {
        // curency display
        //       'symbol' to use a localized currency symbol such as '€' (default value)
        // 'narrowSymbol' to use a narrow format symbol ('$100' rather than 'US$100')
        // 'code' to use the ISO currency code
        // 'name' to use a localized currency name such as 'dollar'
        // 'hidden' to hide the currency

        let options = {
            locale: 'id-ID',
            currency: 'IDR',
            // precision: 0, //decimal show
            allowNegative: false,
            valueAsInteger: false,
            autoDecimalMode: true,
            distractionFree: {
                hideNegligibleDecimalDigits: false,
                hideCurrencySymbol: true,
                hideGroupingSymbol: false,
            },
            currencyDisplay: 'narrowSymbol',
            hideCurrencySymbolOnFocus: false,
            hideGroupingSeparatorOnFocus: false,
            hideNegligibleDecimalDigitsOnFocus: false,
        };
        const { inputRef } = useCurrencyInput(options);

        return { inputRef };
    },
};
</script>
