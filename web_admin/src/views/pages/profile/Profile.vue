<template>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content" style="min-height: 100px">
                    <table class="table text-16" v-if="data.role != 'pelanggan'">
                        <tbody>
                            <tr>
                                <td class="text-grey-light" style="width: 100px">
                                    Username
                                </td>
                                <td class="font-weight-bold">
                                    :{{ data.username }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="data.role == 'pelanggan'">


                        <div class="item form-group">
                            <label class="col-form-label col-12 col-md-3 label-align">Nama <span
                                    class="required">*</span>
                            </label>
                            <div class="col-12 col-md-6 col-sm-6">
                                <input name="email" class="form-control" v-model="form.nama" type="text"  />
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-12 col-md-3 label-align">No Hp <span
                                    class="required">*</span>
                            </label>
                            <div class="col-12 col-md-6 col-sm-6">
                                <input name="email" class="form-control" v-model="form.no_hp" type="text" />
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-12 col-md-3 label-align">Email <span
                                    class="required">*</span>
                            </label>
                            <div class="col-12 col-md-6 col-sm-6">
                                <input name="email" class="form-control" v-model="form.email" type="text" disabled />
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-12 col-md-3 label-align">Tgl Daftar <span
                                    class="required">*</span>
                            </label>
                            <div class="col-12 col-md-6 col-sm-6">
                                <input name="email" class="form-control" v-model="form.created_at" type="text"
                                    disabled />
                            </div>
                        </div>

                        <div class="item">
                            <div class="col-12 col-md-6 col-sm-6 offset-0 offset-md-3 mt-3">
                                <button type="button" @click="onSubmit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    computed: {
        ...mapGetters({
            data: "auth/user",
        }),
    },
    data: function () {
        return {
            form: {
                nama: "",
                no_hp: "",
                email: "",
            },
            errors: {},
        };
    },
    watch: {
        data: function (val) {
            this.setData(val);
        },
    },
    mounted() {
        this.setData(this.data);
    },
    methods: {
        setData(data) {
            console.log(data);
            this.form.nama = data.nama || "";
            this.form.no_hp = data.no_hp || "";
            this.form.email = data.email || "";
            this.form.created_at = data.created_at || "";
        },

        onSubmit() {
            this.$store.dispatch("loading", true);

            var sendData = () => {
                return this.$axios.put(`/app/pelanggan/${this.data.id}`, this.form);
            };
            sendData()
                .then((res) => {

                    this.$root.notif(res.message);
                })
                .catch((res) => {
                    this.$root.notif(res.message, {
                        type: "error",
                        position: "top",
                    });
                })
                .finally(() => {
                    this.$store.dispatch("loading", false);
                });
        },
    },
};
</script>
