<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        <div>&nbsp;</div>
        <div class="row">
          <div class="col-12 col-md-4">
            <input-date-picker-range-base
              v-model:start_date="search.start_date"
              v-model:end_date="search.end_date"
            />
          </div>
          <div class="col-12 col-md-8 text-end">
            <button
              class="btn btn-outline-primary"
              type="button"
              @click="cetak"
            >
              Cetak
            </button>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <div class="table-responsive">
          <div class="row">
            <div class="col-sm-12">
              <b-overlay :show="loading" rounded="sm">
                <div id="table-cetak-2">
                  <table
                    class="table table-striped jambo_table bulk_action table-bordered"
                    id=""
                  >
                    <thead>
                      <tr>
                        <th
                          v-for="(header, i) in headers"
                          :key="i"
                          :class="[
                            header.sortable ? ' sortable ' : '',
                            header.field == options.sort &&
                            options.order == 'asc'
                              ? ' asc '
                              : header.field == options.sort &&
                                options.order == 'desc'
                              ? ' desc '
                              : 'both',
                          ]"
                          @click="header.sortable ? sorter(header.field) : null"
                        >
                          {{ header.label }}
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(user, i) in data" :key="i">
                        <td>
                          {{ $filters.dateTime(user.tanggal_transaksi) }}
                        </td>
                        <td>
                          {{ user.pelanggan ? user.pelanggan.nama : "" }}
                        </td>
                        <td class="text-end">
                              <div style="display: flex;justify-content: center;">                              
                                <StarRating read-only star-size="20" :rating="user.rating" />
                              </div>
                          <!-- {{ user.rating?$filters.currency(user.rating):'' }} -->
                        </td>
                        <td>
                          {{ user.admin ? user.admin.username : "" }}
                        </td>
                      </tr>
                      <tr v-if="data.length > 0">
                        <td colspan="2" class="font-weight-bold text-end">
                          <strong>Rata Rata Rating</strong>
                        </td>
                        <td class="text-bold text-end">
                            <!-- {{ $filters.currency(totalAll) }} -->
                              <div style="display: flex;justify-content: center;">                              
                                <StarRating read-only star-size="20" :rating="totalAll" />
                              </div>
                        </td>
                        <td></td>
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
          <div class="text-center"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import formatDate from "date-fns/format";
import StarRating from "vue-star-rating";
export default {
  components:{
    StarRating
  },
  data() {
    return {
      data: [],
      options: {
        current_page: 1,
        total_row: 10000,
        per_page: 1000000,
        sort: "id_transaksi",
        order: "desc",
      },
      search: {
        start_date: formatDate(new Date(), "yyyy-MM-dd"),
        end_date: formatDate(new Date(), "yyyy-MM-dd"),
      },
      loading: false,
    };
  },
  mounted() {
    this.searchData();
  },
  computed: {
    totalAll() {
      var rt = this.data.filter((v) => v.rating);
      var d = rt.reduce((p, c) => {
        return p + parseFloat(c.rating);
      }, 0);
      if (rt.length < 0) {
        return 0;
      }

      return d / rt.length;
    },
    headers() {
      return [
        {
          label: "Tanggal",
          field: "tanggal_transaksi",
          sortable: true,
        },
        {
          label: "Pelanggan",
          field: "id_pelanggan",
          sortable: true,
        },
        {
          label: "Rating",
          field: "rating",
          sortable: true,
        },
        {
          label: "Admin",
          field: "id_admin",
          sortable: true,
        },
      ];
    },
    moreParams() {
      return {
        per_page: this.options.per_page,
        page: this.options.current_page,
        start_date: this.search.start_date,
        end_date: this.search.end_date,
        sort: this.options.sort + "|" + this.options.order,
      };
    },
  },
  watch: {
    "search.start_date": {
      handler: function () {
        this.loadData();
      },
    },
    "search.end_date": {
      handler: function () {
        this.loadData();
      },
    },
  },
  methods: {
    cetak() {
      const printContent = document.getElementById("table-cetak-2").innerHTML;
      const myWindow = window.open("", "", "width=800,height=600");
      var html =
        `
        <html>
          <head>
            <title>Print </title>
            <link rel="stylesheet" href="/vendors/bootstrap-5.0.2-dist/css/bootstrap.min.css">
            <style>
            
.vue-star-rating-rating-text {
  display: none !important;
}
  .sr-only{
  display: none !important;
  }
            </style>
          </head>
          <body>
            ${printContent}
          </body>
        <script>
          window.onload = function() {
            window.print();
          };
          </scr` +
        `ipt>
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
  },
};
</script>
