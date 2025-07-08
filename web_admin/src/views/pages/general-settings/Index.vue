<template>
    <div class="col-12  col-sm-12 col-md-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="table-responsive">
                    <ol class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-start" v-for="dt in data"
                            :key="dt.id">
                            <div class="row " style="width: 100%;">
                                <div class="col-12 col-md-6">
                                    {{ dt.label }}
                                    <div v-if="dt.note" class="text-10 text-danger">
                                        {{ dt.note }}
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div v-if="dt.type == 'option'">
                                        <select class="form-control" v-model="dt.value">
                                            <option v-for="(opt, i) in dt.options" :key="i" :value="i" v-text="opt">
                                            </option>
                                        </select>
                                    </div>
                                    <div v-else-if="dt.type == 'range_time'">
                                        <div class="row">
                                            <div class="col-12 col-lg-4">
                                                <input type="time" class="form-control" v-model="dt.value_1"
                                                    :placeholder="dt.placeholder" />
                                            </div>
                                            <div class="col-12 col-lg-2 col-12 col-lg-2 text-center pt-2">
                                                Sampai &nbsp;
                                            </div>
                                            <div class="col-12 col-lg-4">
                                                <input type="time" class="form-control" v-model="dt.value_2"
                                                    :placeholder="dt.placeholder" />
                                            </div>
                                        </div>

                                    </div>
                                    <div v-else-if="dt.type == 'tagable'">
                                        <!-- <v-select taggable multiple v-model="dt.value" /> -->
                                        <vue3-tags-input :tags="dt.value" placeholder="enter some tags"
                                            @on-tags-changed="handleChangeTag($event, dt.key)" />
                                    </div>
                                    <div v-else>
                                        <input :type="dt.type ? dt.type : 'text'" class="form-control" v-model="dt.value"
                                            :placeholder="dt.placeholder" />
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ol>

                    <div class="ln_solid"></div>
                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-primary" @click="simpan">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import _cloneDeep from "lodash/cloneDeep"
import { mapGetters } from "vuex";
export default {
    props: {
    },
    components: {

    },
    data() {
        return {
            data: [],
            loading: false,
        };
    },
    mounted() {
        this.loadData();
    },
    computed: {
        ...mapGetters({

        }),
        moreParams() {
            return {

            };
        },
    },
    watch: {

    },
    methods: {
        handleChangeTag(e, key) {
            var cek = this.data.findIndex(v => v.key == key);
            if (cek > -1) {
                this.data[cek].value = e;
            }
        },
        simpan() {
            var dt = _cloneDeep(this.data);
            let params = {
                data: dt.map(v => {
                    if (v.type == 'tagable' && v.value) {
                        v.value = v.value.join(',');
                    }
                    return v;
                })
            };
            this.loading = true;
            this.$axios
                .post("/app/general-settings", params)
                .then(() => {
                    this.$root.notif('success', {
                        type: "info",
                        position: "top",
                    });
                    this.loadData();
                })
                .catch((res) => {
                    this.$root.notif(res.message, {
                        type: "error",
                        position: "top",
                    });
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        loadData() {
            this.loading = true;
            this.$axios
                .get("/app/general-settings", { params: this.moreParams })
                .then((res) => {
                    this.loading = false;
                    this.data = res.data.map(v => {
                        if (v.type == 'tagable') {
                            if (v.value) {
                                v.value = v.value.split(',')
                            } else {
                                v.value = [];
                            }
                        }
                        return v;
                    });
                })
                .catch((res) => {
                    this.$root.notif(res.message, {
                        type: "error",
                        position: "top",
                    });
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>