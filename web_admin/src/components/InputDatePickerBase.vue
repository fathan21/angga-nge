<template>
    <v-date-picker
        v-model="val"
        :min-date="minDate"
        :max-date="maxDate"
        @input="onInput"
        :disabled="disabled"
    >
        <template v-slot="{ inputValue, inputEvents }">
            <div class="input-group">
                <div class="input-group-text">
                    <i class="fa fa-calendar"></i>
                </div>
                <input
                    :value="inputValue"
                    v-on="inputEvents"
                    :disabled="disabled"
                    ref="input"
                    class="form-control"
                />
            </div>
        </template>
    </v-date-picker>
</template>

<script>
import format from "date-fns/format";
export default {
    props: {
        value: {
            default: null,
        },
        minDate: {
            default: null,
        },
        maxDate: {
            default: null,
        },
        disabled: {
            default: false,
        },
    },
    computed: {},
    data() {
        return {
            val: null,
        };
    },
    watch: {
        value(value) {
            this.setValue(value);
        },
    },
    mounted() {
        this.setValue(this.value);
    },
    methods: {
        setValue(value) {
            this.val = value;
        },
        onInput(value) {
            let v = format(value).format("yyyy-MM-DD");
            this.$emit("input", v);
        },
    },
};
</script>
<style scoped>
</style>