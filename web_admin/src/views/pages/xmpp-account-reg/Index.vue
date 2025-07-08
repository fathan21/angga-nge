<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>&nbsp;</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li>

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
                <div class="table-responsive">
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
                      <tr v-for="(item, i) in data" :key="i">
                        <td style="width: 50px;">
                          {{
                            (options.current_page - 1) * options.per_page +
                            i +
                            1
                          }}
                        </td>
                        <td>
                          {{ item.name }}
                        </td>
                        <td style="width: 200px;">
                          {{ item.username }}
                        </td>
                        <td style="width: 150px;">
                          {{ item.host }}
                        </td>
                        <td style="width: 150px;">
                          {{ item.phone }}
                        </td>
                        <td style="width: 150px;">
                          {{ item.telegram_id }}
                        </td>
                        <td style="width: 150px;">
                          <!-- {{ item.status }} -->
                          <statusbadge-base v-if="item.status == 1" status="Active">
                            Active
                          </statusbadge-base>
                          <statusbadge-base v-else-if="item.status == 0" status="Pending">
                            Pending
                          </statusbadge-base>
                          <statusbadge-base v-else-if="item.status == 11" status="Deactive">
                            Deactive
                          </statusbadge-base>
                        </td>
                        <td style="width: 200px;">

                          <button class="btn btn-sm btn-outline-success " type="button" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Set Active" @click="changeStatus(item, 1)">
                            <i class="fa fa-check" />
                          </button>
                          <button class="btn btn-sm btn-outline-danger " type="button" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Set Deactive" @click="changeStatus(item, 11)">
                            <i class="fa fa-times" />
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
        sort: "name",
        order: "asc",
      },
      search: {
        q: "",
        status: "0"
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
          label: "#",
          sortable: false,
        },
        {
          label: "Name",
          field: "name",
          sortable: true,
        },
        {
          label: "Username",
          field: "username",
          sortable: true,
        },
        {
          label: "Host",
          field: "host",
          sortable: true,
        },
        {
          label: "Phone",
          field: "phone",
          sortable: true,
        },
        {
          label: "Telegram ID",
          field: "phone",
          sortable: false,
        },
        {
          label: "Status",
          field: "status",
          sortable: true,
        },
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
        status: this.search.status,
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
        .get("/app/xmpp-accounts", { params: this.moreParams })
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
        .delete(`/app/xmpp-accounts/${user.id}`, params)
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

    changeStatus(item, status) {
      let labelStatus = status == 1 ? "Active Data" : "Deactive Data";
      this.$swal
        .fire({
          width: 350,
          title: "Yakin?",
          showCancelButton: true,
          confirmButtonText: labelStatus,
        })
        .then((result) => {
          if (result.isConfirmed) {
            this._changeStatus(item, status);
          }
        });
    },
    _changeStatus(user, newStatus) {
      let params = Object.assign({}, user);
      params.status = newStatus;
      var action = newStatus == 1 ? 'active' : 'deactive';
      this.$axios
        .put(`/app/xmpp-accounts/${user.id}/${action}`, params)
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
        name: "app.xmpp-account.form",
        params: {
          id: item.id,
        },
      });
    },
  },
};
</script>
