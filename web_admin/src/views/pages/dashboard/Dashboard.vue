<template>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="row">
                <div class="col-12 col-md-4">
                    <b-card class="bg-info">
                        <h2>Total Pelanggan</h2>
                        <h1>
                            <strong  v-if="!loading">{{ $filters.currency(data.total_pelanggan)  }}</strong>
                            <b-skeleton v-else>&nbsp;&nbsp;&nbsp;</b-skeleton>
                        </h1>
                    </b-card>
                </div>
                <div class="col-12 col-md-4">
                    <b-card class="bg-warning">
                        <h2>Total Transaksi Hari ini</h2>
                        <h1>
                            <strong v-if="!loading">{{ $filters.currency(data.total_trx_hari_ini)  }}</strong>
                            <b-skeleton v-else>&nbsp;&nbsp;&nbsp;</b-skeleton>
                        </h1>
                    </b-card>
                </div>
                <div class="col-12 col-md-4">
                    <b-card class="bg-success text-white">
                        <h2>Total Pemasukan</h2>
                        <h1>
                            <strong v-if="!loading">{{ $filters.currency(data.total_pemasukan)  }}</strong>
                            <b-skeleton v-else>&nbsp;&nbsp;&nbsp;</b-skeleton>
                        </h1>
                    </b-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data: {
                total_pelanggan: 0,
                total_trx_hari_ini:0,
                total_pemasukan: 0
            },
            loading: false,
        };
    },
    mounted() {
        this.loadData();
    },
    computed: {
        
    },
    watch: {
    },
    methods: {
        loadData() {
            this.loading = true;
            this.$axios
                .get("app/dashboard")
                .then((res) => {
                    this.loading = false;
                    this.data = res.data;
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

