<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        <div>

        &nbsp;
        </div>
        <div class="text-end">
          
            <button class="btn btn-outline-primary" type="button" @click="cetak">
              Cetak
            </button>
        </div>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <div class="table-responsive">
          
          <div class="row">
            <div class="col-sm-12">
              <b-overlay :show="loading" rounded="sm">
                <div id="table-cetak">
                  <table class="table table-striped jambo_table bulk_action table-bordered" id="">
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
                        <td>
                          {{ user.nama }}
                        </td>
                        <td>
                          {{ $filters.currency(user.total_transaksi) }}
                        </td>
                        <td>
                          {{ $filters.currency(user.total_poin) }}
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
          <div class="text-center"> 
          </div>
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
        per_page: 1000000,
        sort: "total_transaksi",
        order: "desc",
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
          label: "Nama",
          field: "nama",
          sortable: true,
        },
        {
          label: "Total Belanja",
          field: "total_transaksi",
          sortable: true,
        },
        {
          label: "Total Point",
          field: "total_poin",
          sortable: true,
        }
      ];
    },
    moreParams() {
      return {
        per_page: this.options.per_page,
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
    cetak() {
      const printContent = document.getElementById('table-cetak').innerHTML;
      const myWindow = window.open('', '', 'width=800,height=600');
      var html = `
        <html>
          <head>
            <title>Print </title>
            <link rel="stylesheet" href="${this.$PUBLIC_PATH}/vendors/bootstrap-5.0.2-dist/css/bootstrap.min.css">
          </head>
          <body>
            ${printContent}
          </body>
        <script>
          window.onload = function() {
            window.print();
          };
          </scr` + `ipt>
        </html>
      `;
      // myWindow.document.close();
      
      myWindow.document.open();
      myWindow.document.write(html);
      myWindow.document.close();
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
      this.loadData();
    },
    loadData() {
      this.loading = true;
      this.$axios
        .get("/app/pelanggan", { params: this.moreParams })
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
  },
};
</script>
