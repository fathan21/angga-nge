<template>
    <div>
        <v-select
            label="name"
            class="style-chooser"
            :filterable="false"
            :options="options"
            @search="onSearch"
            v-model="selected"
            placeholder="Pilih supplier"
        >
            <template v-slot:no-options="{}">
                ketik untuk mencari supplier..
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
import _debounce from "lodash/debounce";
import { ref, watch } from "vue";
export default {
    emits: ["on-change", "on-blur", "on-focus"],
    
    setup(props, { emit }) {
        const selected = ref(props.value);
        watch(selected, (newSelected) => {
            emit("on-change", newSelected);
        });
        return { selected };
    },
    props: {
        product_code: {
            type: [String, Number],
            default: function() {
                return null;
            }
        },
        params: {
            type: Object,
            default: function() {
                return {};
            }
        }
    },
    computed: {},
    mounted(){
        this.onSearch('');
    },
    data() {
        return {
            val: {},
            options: [],
        };
    },
    methods: {
        onSearch(search, loading) {
            if (search.length) {
                loading(true);
                this.search(loading, search, this);
            } else {
                this.search(loading, search, this);
            }
        },
        search: _debounce((loading, search, vm) => {
            vm.$axios
                .get("suppliers", { params: { q: escape(search), product_code: vm.product_code,...vm.params } })
                .then((res) => {
                    vm.options = res.data.data;
                })
                .finally(() => {
                    if(loading) {
                        loading(false);
                    }
                });
        }, 350),
    },
};
</script>