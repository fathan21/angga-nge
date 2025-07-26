<template>
    <div class="">
        <div class="x_panel">
            <div class="x_title">
                <h4>
                    Rating Pelayanan</h4>
                <div class="clearfix"></div>
            </div>

            <div class="x_content text-start">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-4">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Pertanyaan</th>
                                        <th>Ya</th>
                                        <th>Tidak</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="(item, i) in jawaban" :key="i">
                                        <td>{{ item.pertanyaan }}</td>
                                        <td>
                                            <input type="radio" v-model="item.value" value="Ya" />
                                        </td>
                                        <td>
                                            <input type="radio" v-model="item.value" value="Tidak" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-2">
                            <div>Ulasan</div>
                            <div class="" style="display: flex; padding-left: 20px;">
                                <textarea v-model="form.ulasan" class="form-control" rows="3"
                                    placeholder="Tulis ulasan anda di sini..."></textarea>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div>Rating</div>
                            <div class="" style="display: flex; padding-left: 20px;">
                                <div style="width: 250px">
                                    <star-rating v-model:rating="form.rating" :star-size="25"></star-rating>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" @click="$emit('close')" class="btn btn-danger">
                                Batal
                            </button>
                            <button type="button" @click="simpanRating" class="btn btn-primary">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import format from "date-fns/format";
import StarRating from "vue-star-rating";
export default {
    components: {
        StarRating,
    },
    props: {
        modelValue: {
            type: Boolean,
            default: false,
        },
        pertanyaan: {
            type: Array,
            default: () => [],
        },
        data: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            rating: 0,
            jawaban: [],
            form: {
                ulasan: "",
                rating: 0
            },
            details: [],
        };
    },
    mounted() {

    },
    computed: {
        grand_total() {
            return this.details.reduce((p, c) => {
                return p + c.subtotal;
            }, 0);
        },
    },
    watch: {
        modelValue(val) {
            if (val) {
                this.form.ulasan = "";
                this.form.rating = 0;
                this.jawaban = this.pertanyaan.map((v) => {
                    return {
                        pertanyaan: v.pertanyaan,
                        value: 0,
                        tidak: "",
                    };
                });
            }
        },
    },
    methods: {
        
        simpanRating() {

            var params = Object.assign({}, this.form);
            params.pertanyaan = this.jawaban.map((v) => {
                return {
                    pertanyaan: v.pertanyaan,
                    jawaban: v.value,
                };
            });
            params.ulasan = "Ulasan :"+this.form.ulasan;

            var html = `<table class=" text-start table ">`;
            html += `
                <tr>
                    <td>No</td>
                    <td>Pertanyaan</td>
                    <td>Pelayanan</td>
                </tr>
                `;
            for (let index = 0; index < this.jawaban.length; index++) {
                const element = this.jawaban[index];
                html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${element.pertanyaan}</td>
                    <td>${element.value}</td>
                </tr>
                `;
            }
            html += `</table>`;
            params.ulasan += html;
            params.rating = this.form.rating;
            params.id_transaksi = this.data.id_transaksi;
            params.tipe = "Pelayanan";

            params.tanggal = format(new Date(), "yyyy-MM-dd HH:mm:ss"),

                this.$axios
                    .post("/app/ulasan", params)
                    .then(() => {
                        this.$root.notif("data berhasil disimpan", {
                            type: "info",
                            position: "top",
                        });
                        this.pelanggan = {};
                        this.details = [];
                        this.$emit('close');
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
<style>
.vue-star-rating-rating-text {
    display: none !important;
}
</style>