<template>
  <div class="">
    <div class="x_panel">
      <div class="x_content">
        <div class="table-responsive">
          <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
              <!-- <select  class="form-control"  v-model="search.kategori_menu" >
                  <option value="">Semua Kategori</option>
                  <option v-for="d in cats" :key="d" :value="d" v-text="d"></option>
                </select> -->

              <select-base
                :options="
                  cats.map((v) => {
                    return {
                      id: v,
                      name: v,
                    };
                  })
                "
                :placeholder="'Semua kategori'"
                useModel="1"
                v-model="search.kategori_menu"
              />
            </div>
            <div class="col-12 col-sm-6 col-md-4">
              <div class="input-group">
                <input
                  type="text"
                  class="form-control"
                  placeholder="cari..."
                  v-model="search.q"
                  @keyup.enter="searchData"
                />
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
                  <div
                    class="col-6 col-md-3 col-lg-3"
                    v-for="m in data"
                    :key="m.id_menu"
                  >
                    <b-card
                      class="mb-2 bg-success text-white"
                      style="cursor: pointer"
                      @click="$emit('setMenu', m)"
                    >
                      <div>
                        {{ m.kategori_menu }}
                      </div>
                      <div style="font-size: 16px">
                        {{ m.nama_menu }}
                      </div>
                      <div class="text-end">
                        {{ $filters.currency(m.harga) }}
                      </div>
                    </b-card>
                  </div>
                </div>
              </b-overlay>
            </div>
          </div>
          <paging-base
            v-model="options.current_page"
            :options="options"
            v-if="data.length > 0"
          />
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
        per_page: 24,
        sort: "nama_menu",
        order: "asc",
      },
      search: {
        q: "",
        kategori_menu: "",
      },
      cats: [],
      loading: false,
    };
  },
  mounted() {
    this.searchData();
    this.loadCat();
  },
  computed: {
    moreParams() {
      return {
        page: this.options.current_page,
        per_page: this.options.per_page,
        kategori_menu: this.search.kategori_menu
          ? this.search.kategori_menu.id
          : "",
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
    "search.kategori_menu": {
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
        .get("/app/menu", { params: this.moreParams })
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
    loadCat() {
      this.$axios
        .get("/app/menu-cats", { params: this.moreParams })
        .then((res) => {
          this.cats = res.data;
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
