<template>
    <div>
        <v-select
            label="name"
            class="style-chooser"
            :filterable="false"
            :options="options"
            @search="onSearch"
            v-model="selected"
            placeholder="Pilih mitra user"
        >
            <template v-slot:no-options="{}">
                ketik untuk mencari user mitra..
            </template>
            <template v-slot:option="option">
                <div class="">{{ option.name }} - ({{ option.mitra.username }})</div>
            </template>
            <template v-slot:selected-option="option">
                <div class="text-nowrap">
                    {{ option.name }} - ({{ option.mitra.username }})
                </div>
            </template>
        </v-select>
    </div>
</template>

<script>
import _debounce from "lodash/debounce";
import { mapGetters } from "vuex";
import { ref, watch } from "vue";
export default {
    // emits: ["on-change", "on-blur", "on-focus"],
    props:["modelValue", "idType","value"],
    emits: ["on-change", "on-blur", "on-focus", "update:modelValue"],
    setup(props, { emit }) {

        let selected = ref(null);
        if (props.value && props.value.id) {
            selected = ref(props.value);
        }

        watch(selected, (newSelected) => {
            emit("on-change", newSelected);
            console.log(newSelected);
            if (newSelected) {
                let id = selected.value.id;
                emit("update:modelValue", id);
            } else {
                emit("update:modelValue", "");
            }
        });
        return { selected };
    },
    computed: {
        ...mapGetters({
        isMarketing: "auth/isMarketing",
        }),
    },
    mounted() {
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
            let url = 'mitra';
            if(vm.isMarketing) {
                url = '/salesman/mitra';
            }
            vm.$axios
                .get(`${url}`, { params: { q: escape(search) } })
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