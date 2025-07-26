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
                            <a class="btn btn-link btn-sm" style="padding: 0px;" @click="openDetail(user)">
                              {{ user.nama_promo }}
                            </a>
                        </td>
                        <td>
                          {{ user.deskripsi }}
                        </td>
                        <td>
                          {{ $filters.date(user.periode_mulai) }} - {{ $filters.date(user.periode_akhir) }}
                        </td>
                        <td>
                          
                          <statusbadge-base :status="user.status">
                            </statusbadge-base>
                        </td>
                        <!-- <td style="width: 100px;">
                          <button class="btn btn-sm btn-primary " type="button" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Ubah" @click="editItem(user)">
                            <i class="fa fa-pencil" />
                          </button>
                          <button class="btn btn-sm btn-danger " type="button" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Hapus" @click="removeItem(user)">
                            <i class="fa fa-trash" />
                          </button>
                        </td> -->
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
export default {
  data() {
    return {
      data: [],
      options: {
        current_page: 1,
        total_row: 10000,
        per_page: 1,
        sort: "nama",
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
          label: "Nama Promo",
          field: "nama_promo",
          sortable: true,
        },
        {
          label: "Deskripsi",
          field: "deskrpisi",
          sortable: false,
        },
        {
          label: "Periode",
          field: "periode",
          sortable: false,
        },
        {
          label: "Status",
          field: "status",
          sortable: false,
        },
        // {
        //   label: "Action",
        //   sortable: false,
        // },
      ];
    },
    moreParams() {
      return {
        status: 'aktif',
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
        .get("/app/promo", { params: this.moreParams })
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
        .delete(`/app/promo/${user.id_promo}`, params)
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
        var html = `<table class="table table-striped jambo_table bulk_action table-bordered table-sm">`;
          html += `
            <tr>
              <td>No</td>
              <td>Menu</td>
              <td>Discount</td>
            </tr>
          `;
        for (let index = 0; index < user.details.length; index++) {
          const element = user.details[index];
          html += `
            <tr>
              <td>${index + 1}</td>
              <td>${element.menu.nama_menu}</td>
              <td>${element.discount} %</td>
            </tr>
          `;
        }
        html += `</table>`;
        this.$swal
          .fire({
            width: 500,
            title: "Detail Promo",
            html: html,
          });
        
      },
    editItem(item) {
      this.$router.push({
        name: "app.promo.form",
        params: {
          id: item.id_promo,
        },
      });
    },
  },
};
</script>
