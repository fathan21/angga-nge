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
                          {{ user.nama }}
                        </td>
                        <td style="width: 150px;">
                          <input type="number" class="form-control" @change="updateData(user)" v-model="user.poin" />
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
  components:{
    
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
          label: "Parameter Loyalitas",
          field: "nama",
          sortable: false,
        },
        {
          label: "Score",
          field: "total_poin",
          sortable: false,
        }
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
        .get("/app/loyal", { params: this.moreParams })
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
        .put("/app/loyal/"+user.id, user )
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
        .delete(`/app/loyal/${user.id_loyal}`, params)
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
      this.$router.push({
        name: "app.loyal.form",
        params: {
          id: item.id_loyal,
        },
      });
    },
  },
};
</script>
