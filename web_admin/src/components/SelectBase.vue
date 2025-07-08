<template>
    <div>
        <v-select
            label="name"
            class="style-chooser"
            :options="options"
            v-model="selected"
            :placeholder="placeholder"
            v-bind="$attrs"
        >
            <template v-slot:no-options="{}">
                ketik untuk mencari
            </template>
            <template v-slot:option="option">
                <div class="">{{ option.name }} </div>
            </template>
            <template v-slot:selected-option="option">
                <div class="text-nowrap">
                    {{ option.name }}
                </div>
            </template>
        </v-select>
    </div>
</template>

<script>
import { ref, watch } from "vue";
export default {
    emits: ["on-change", "on-blur", "on-focus","update","update:modelValue"],
    setup(props, { emit }) {
        const selected = ref(props.value);
        watch(selected, (newSelected) => {
            emit("on-change", newSelected);
            emit('update:modelValue', newSelected);
        });
        return { selected };
    },
    props: {
        placeholder: {
            type:String,
            default: "Pilih salah satu"
        },
        options: {
            type: Array,
            required: true,
            default: function() {
                return [];
            }
        }
    },
    computed: {},
    data() {
        return {
        };
    },
};
</script>