<template>
    <v-date-picker
        v-model="input"
        :min-date="minDate"
        :max-date="maxDate"
        @input="onInput"
        :masks="{ input: 'DD/MM/YYYY' }"
        :disabled="disabled"
        is-range
        :columns="$screens({ default: 1, lg: 2 })"
    >
        <template v-slot="{ inputValue, inputEvents }">
            <div class="d-flex align-items-center">
                <div class="input-group">
                    <div class="input-group-text">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input
                        :value="inputValue.start"
                        v-on="inputEvents.start"
                        :disabled="disabled"
                        ref="input"
                        class="form-control"
                    />
                </div>
                <div class="px-2 mb-1">ke</div>
                <div class="input-group">
                    <div class="input-group-text">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input
                        :value="inputValue.end"
                        v-on="inputEvents.end"
                        :disabled="disabled"
                        ref="input"
                        class="form-control"
                    />
                </div>
            </div>
        </template>
    </v-date-picker>
</template>

<script>
import format from "date-fns/format";
// import { ref } from "vue";
export default {
    props: {
        start_date: {
            default: null,
            required: true
        },
        end_date: {
            default: null,
            required: true
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

    // setup(props) {
    //     const input = ref({});
    //     if(props.start_date) {
    //         input.value ={
    //             start: props.start_date,
    //             end: props.end_date,
    //         };
    //     }
    //     return { input };
    // },
    // computed: {},
    data() {
        return {
            input: {
                start: null,
                end: null,
            },
        };
    },
    watch: {
        start_date() {
            this.input = {
                start: this.start_date,
                end: this.end_date,
            };
        },
        input(value) {
            // this.setValue(value);
            if(value.start) {
                this.onInput(value);
            }
        },
    },
    mounted() {
        this.setValue({
            start: this.start_date,
            end: this.end_date,
        });
    },
    methods: {
        setValue(value) {
            this.input = value;
        },
        onInput(value) {
            if(value.start && value.start instanceof Date ){
                this.$emit('update:start_date', format(value.start,'yyyy-MM-dd'));
                this.$emit('update:end_date', format(value.end,'yyyy-MM-dd'));
            }
        },
    },
};
</script>
<style scoped>
</style>