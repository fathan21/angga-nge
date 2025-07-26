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
          <div class="row mb-2">
            <div class="col-12 col-sm-6 col-md-4">
              <select class="form-control" v-model="search.tipe">
                <option value="Makanan">Makanan</option>
                <option value="Pelayanan">Pelayanan</option>
              </select>
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
                          <template v-if="search.tipe == 'Pelayanan'">

                            {{ user.transaksi.pelanggan.nama }}
                          </template>
                          <template v-else>
                            {{ user.menu.nama_menu }}
                          </template>
                        </td>
                        <td>
                          {{ user.tanggal }}
                        </td>
                        <td>
                          <!-- {{ user.rating }} -->
                          <div style="display: flex;justify-content: center;">
                            <StarRating read-only :star-size="20" :rating="user.rating" />
                          </div>
                        </td>
                        <td>
                          <div v-html="user.ulasan">

                          </div>
                        </td>
                        <td>
                          <!-- <button class="btn btn-sm btn-primary " type="button" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Ubah" @click="editItem(user)">
                            Respon
                          </button> -->

                          <div v-html="user.respon">

                          </div>

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
  </div>
</template>

<script>
import { format } from "date-fns";
import StarRating from "vue-star-rating";
export default {
  components: {
    StarRating
  },
  data() {
    return {
      data: [],
      options: {
        current_page: 1,
        total_row: 10000,
        per_page: 1,
        sort: "id",
        order: "desc",
      },
      search: {
        q: "",
        tipe: "Pelayanan", // Default type for filtering
      },
      loading: false,
    };
  },
  mounted() {
    this.searchData();
  },
  computed: {
    headers() {
      return [
        {
          label: "No",
          sortable: false,
        },
        {
          label: this.search.tipe == 'Pelayanan' ? "Nama" : "Nama Menu",
          field: "nama",
          sortable: false,
        },
        {
          label: "Tanggal",
          field: "nama",
          sortable: false,
        },
        {
          label: "Rating",
          field: "Rating",
          sortable: false,
        },
        {
          label: "Ulasan",
          field: "Ulasan",
          sortable: false,
        },
        {
          label: "Respon",
          sortable: false,
        },
      ];
    },
    moreParams() {
      return {
        // id_pelanggan: this.$store.state.auth.user.id,
        tipe: this.search.tipe,
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
    "search.tipe": {
      handler: function () {
        this.data = [];
        this.loadData();
      },
    },
  },
  methods: {
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
        .get("/app/ulasan", { params: this.moreParams })
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
    updateData(user) {
      this.$axios
        .put("/app/loyal/" + user.id, user)
        ;
    },
    removeItem(item) {
      let labelStatus = "Hapus Data";
      let status = item.status == 1 ? 0 : 1;
      this.$swal
        .fire({
          width: 350,
          title: "Yakin?",
          showCancelButton: true,
          confirmButtonText: labelStatus,
        })
        .then((result) => {
          if (result.isConfirmed) {
            this._remove(item, status);
          }
        });
    },
    _remove(user, newStatus) {
      let params = Object.assign({}, user);
      params.status = newStatus;
      this.$axios
        .delete(`/app/ulasan/${user.id}`, params)
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
    editItem(item) {


      this.$swal
        .fire({
          width: 500,
          title: "Respon",
          focusConfirm: false,
          html: `
            <div class="text-start">
              Ulasan
            </div>
            <div class="mb-4 text-start pe-2" style="padding-left:10px">
              ${item.ulasan}
            </div>
            <div class="text-start">
              Respon
            </div>
            <textarea id="swal-respons" class="form-control mb-2" placeholder="Respon">${item.respon ? item.respon : ''}</textarea>
          `,
          showCancelButton: true,
          preConfirm: () => {
            return [
              document.getElementById("swal-respons").value
            ];
          },
          confirmButtonText: "Simpan",
        })
        .then((result) => {
          if (result.isConfirmed) {
            if (result.value) {
              let respon = result.value[0];
              item.respon = respon;
              item.tanggal_repon = format(new Date(), "yyyy-MM-dd HH:mm:ss");
              if (respon) {
                this.$axios
                  .put("/app/ulasan/" + item.id, item)
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
              } else {
                this.$root.notif("Semua field harus diisi", {
                  type: "error",
                  position: "top",
                });
              }
            }
          }
        });

    },
  },
};
</script>
