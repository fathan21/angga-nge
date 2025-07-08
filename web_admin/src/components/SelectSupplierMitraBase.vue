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
                ketik untuk mencari supplier mitra
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
                .get("supplier-mitra", { params: { q: escape(search),  product_code: vm.product_code  } })
                .then((res) => {
                    //console.log(res.data);
                    vm.options = res.data;
                    if(res.data.length <= 1) {
                        vm.options.unshift({
                            name : 'Core',
                            code : 'core',
                            id : 'core'
                        });
                        // vm.selected = vm.options[0];
                    }

                    if(res.data.length == 1) {
                        vm.selected = vm.options[0];
                    }
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