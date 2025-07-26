<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">

        <ul class="nav navbar-right panel_toolbox">
          <li>
            <button class="btn btn-sm btn-primary" type="button" @click="addItem()">
              <i class="fa fa-plus" />
              Tambah
            </button>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <div class="table-responsive">

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
                          {{ user.pertanyaan }}
                        </td>
                        <!-- <td style="width: 150px;">
                          {{ user.tipe }}
                        </td> -->
                        <td style="width: 100px;">
                          <button class="btn btn-sm btn-primary " type="button" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Ubah" @click="editItem(user)">
                            <i class="fa fa-pencil" />
                          </button>
                          <button class="btn btn-sm btn-danger " type="button" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Hapus" @click="removeItem(user)">
                            <i class="fa fa-trash" />
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
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {

  },
  data() {
    return {
      data: [],
      options: {
        current_page: 1,
        total_row: 10000,
        per_page: 1,
        sort: "id",
        order: "asc",
      },
      search: {
        q: "",
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
          label: "Pertanyaan",
          field: "nama",
          sortable: false,
        },
        // {
        //   label: "Tipe",
        //   field: "tipe",
        //   sortable: false,
        // },
        {
          label: "Action",
          sortable: false,
        },
      ];
    },
    moreParams() {
      return {
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
        .get("/app/ulasan-pertanyaan", { params: this.moreParams })
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
        .delete(`/app/ulasan-pertanyaan/${user.id}`, params)
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
          width: 350,
          title: "Tambah Ulasan",
          focusConfirm: false,
          html: `
            <div class="text-start">
              Pertanyaan
            </div>
            <input value="${item.pertanyaan}" id="swal-pertanyaan" class="form-control mb-2" placeholder="Pertanyaan">
          `,
          showCancelButton: true,
          preConfirm: () => {
            return [
              document.getElementById("swal-pertanyaan").value,
            ];
          },
          confirmButtonText: "Simpan",
        })
        .then((result) => {
          if (result.isConfirmed) {
            if (result.value) {
              let pertanyaan = result.value[0];
              let tipe = result.value[1];
              if (pertanyaan) {
                this.$axios
                  .put("/app/ulasan-pertanyaan/" + item.id, {
                    pertanyaan: pertanyaan,
                    tipe: tipe,
                  })
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
    addItem() {
      this.$swal
        .fire({
          width: 350,
          title: "Tambah Ulasan",
          focusConfirm: false,
          html: `
            <div class="text-start">
              Pertanyaan
            </div>
            <input id="swal-pertanyaan" class="form-control mb-2" placeholder="Pertanyaan">
          `,
          showCancelButton: true,
          preConfirm: () => {
            return [
              document.getElementById("swal-pertanyaan").value
            ];
          },
          confirmButtonText: "Simpan",
        })
        .then((result) => {
          if (result.isConfirmed) {
            if (result.value) {
              let pertanyaan = result.value[0];
              let tipe = result.value[1];
              if (pertanyaan) {
                this.$axios
                  .post("/app/ulasan-pertanyaan", {
                    pertanyaan: pertanyaan,
                    tipe: tipe,
                  })
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
