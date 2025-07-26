<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        
        <ul class="nav navbar-right panel_toolbox">
          <li>
            &nbsp;
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <div class="table-responsive">
          <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="cari..." v-model="search.q"
                  @keyup.enter="searchData" />
                <button class="btn btn-primary" @click="searchData">
                  Cari
                </button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <b-overlay :show="loading" rounded="sm">
                <div class="">
                  <table class="table table-striped jambo_table bulk_action table-bordered">
                    <thead>
                      <tr>
                        <th v-for="(header, i) in headers" :key="i" :class="[
                          header.sortable ? ' sortable ' : '',
                          header.field == options.sort &&
                            options.order == 'asc'
                            ? ' asc '
                            : header.field == options.sort &&
                              options.order == 'desc'
                              ? ' desc '
                              : 'both',
                        ]" @click="header.sortable ? sorter(header.field) : null">
                          {{ header.label }}
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(user, i) in data" :key="i">
                        <td style="width: 50px;">
                          {{
                            (options.current_page - 1) * options.per_page +
                            i +
                            1
                          }}
                        </td>
                        <td>
                          {{ user.pelanggan ? user.pelanggan.nama:'' }}
                        </td>
                        <td>
                          {{ $filters.dateTime(user.tanggal_transaksi) }}
                        </td>
                        <td>
                          <a class="btn btn-link btn-sm" style="padding: 0px;" @click="openDetail(user)">
                            {{ user.id_transaksi }}
                          </a>
                        </td>
                        <td>
                          {{ $filters.currency(user.total) }}
                        </td>
                        <td>
                          <statusbadge-base :status="user.status">
                          </statusbadge-base>
                        </td>
                        <td>
                          <button  class="btn btn-sm btn-info " type="button" data-bs-toggle="tooltip"
                              data-bs-placement="top" title="Ubah" @click="rLayanan(user)">
                                Rating Pelayan
                            </button>
                          <button  class="btn btn-sm btn-info " type="button" data-bs-toggle="tooltip"
                              data-bs-placement="top" title="Ubah" @click="rMakanan(user)">
                                Rating Menu
                            </button>
                        </td>
                      </tr>

                      <tr v-if="data.length <= 0 && !loading">
                        <td colspan="8" class="text-center">
                          Data tidak ditemukan
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </b-overlay>
            </div>
          </div><paging-base v-model="options.current_page" :options="options" v-if="data.length > 0" />
        </div>
      </div>
    </div>

    <ModalBase v-model="modalRatingLayanan">
        <RatingPelayanan v-model="modalRatingLayanan" :data="modalData" :pertanyaan="pertanyaan" @close="modalRatingLayanan=false" />
    </ModalBase>
    <ModalBase v-model="modalRatingMakan" size="modal-lg">
        <RatingMakana v-model="modalRatingMakan"  :data="modalData"  :pertanyaan="pertanyaan"  @close="modalRatingMakan=false" />
    </ModalBase>
  </div>
</template>

<script>
import RatingMakana from './components/RatingMakana.vue';
import RatingPelayanan from './components/RatingPelayanan.vue';

export default {
  components: {
    RatingPelayanan,
    RatingMakana
  },
  data() {
    return {
      modalRatingMakan: false,
      modalRatingLayanan: false,
      modalData:{},
      data: [],
      options: {
        current_page: 1,
        total_row: 10000,
        per_page: 1,
        sort: "id_transaksi",
        order: "desc",
      },
      search: {
        q: "",
      },
      loading: false,
      pertanyaan:[]
    };
  },
  mounted() {
    this.searchData();
    this.loadPertanyaan();
  },
  computed: {
    headers() {
      return [
        {
          label: "No",
          sortable: false,
        },
        {
          label: "Nama Pelanggan",
          field: "nama",
          sortable: false,
        },
        {
          label: "Waktu Pesanan",
          field: "tanggal_transaksi",
          sortable: true,
        },
        {
          label: "Kode Pesanan",
          field: "id",
          sortable: false,
        },
        {
          label: "Total Transaksi",
          field: "total",
          sortable: true,
        },
        {
          label: "Status",
          field: "status",
          sortable: false,
        },
        {
          label: "Action",
          field: "status",
          sortable: false,
        },
      ];
    },
    moreParams() {
      return {
        id_pelanggan: this.$store.state.auth.user.id,
        status: "dibatalkan,selesai",
        page: this.options.current_page,
        q: this.search.q,
        sort: this.options.sort + "|" + this.options.order,
      };
    },
  },
  watch: {
    "options.current_page": {
      handler: function () {
        this.loadData();
      },
    },
  },
  methods: {
    rMakanan(user) {
      this.modalData = user;
      this.modalRatingMakan = true;
    },
    rLayanan(user) {
      this.modalData = user;
      this.modalRatingLayanan = true;
    },
    sorter(field) {
      if (this.options.sort == field) {
        this.options.order = this.options.order == "asc" ? "desc" : "asc";
      } else {
        this.options.sort = field;
        this.options.order = "asc";
      }
      this.loadData();
    },
    searchData() {
      this.options.current_page = 1;
      this.loadData();
    },
    loadData() {
      this.loading = true;
      this.$axios
        .get("/app/transaksi", { params: this.moreParams })
        .then((res) => {
          this.loading = false;
          this.data = res.data.data;
          this.options.total_row = res.data.meta.total;
          this.options.per_page = res.data.meta.per_page;
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
    loadPertanyaan() {
      this.loading = true;
      this.$axios
        .get("/app/ulasan-pertanyaan", { })
        .then((res) => {
          this.loading = false;
          this.pertanyaan = res.data.data;
          // console.log(this.pertanyaan);
        });
    },
    updateStatus(item, sl) {
      let labelStatus = sl;
      this.$swal
        .fire({
          width: 350,
          title: "Yakin?",
          showCancelButton: true,
          confirmButtonText: labelStatus.toUpperCase(),
        })
        .then((result) => {
          if (result.isConfirmed) {
            this._updateStatus(item, sl);
          }
        });
    },
    _updateStatus(user, newStatus) {
      let params = Object.assign({}, user);
      params.status = newStatus;
      this.$axios
        .put(`/app/transaksi/${user.id_transaksi}`, params)
        .then((res) => {
          this.$root.notif(res.message);
          this.loadData();
        })
        .catch((res) => {
          this.$root.notif(res.message, {
            type: "error",
            position: "top",
          });
        });
    },
    openDetail(user) {
      var html = `<table class=" text-start table table-striped jambo_table bulk_action table-bordered">`;
        html += `
          <tr>
            <td>No</td>
            <td>Nama Menu</td>
            <td>Qty</td>
            <td>Harga</td>
            <td>Rating</td>
          </tr>
        `;
      for (let index = 0; index < user.details.length; index++) {
        const element = user.details[index];
        html += `
          <tr>
            <td>${index + 1}</td>
            <td>${element.menu.nama_menu}</td>
            <td>${element.jumlah}</td>
            <td>${this.$filters.currency(element.subtotal)}</td>
            <td>${element.rating?element.rating.toFixed(2):''}</td>
          </tr>
        `;
      }
      html += `</table>`;
      this.$swal
        .fire({
          width: 600,
          title: "Detail Pesanan",
          html: html,
        });
      
    }
  },
};
</script>
