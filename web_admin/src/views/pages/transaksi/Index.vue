<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        <ul class="nav navbar-right panel_toolbox">
          <li>&nbsp;</li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <div class="row">
          <div class="col-12 col-md-8">
            <MenuN @setMenu="setMenu" />
          </div>
          <div class="col-12 col-md-4">
            <div class="x_panel">
              <div class="x_content">
                <div>Pelanggan</div>
                <div class="mb-4">
                  <select-base
                    :options="
                      pelanggans.map((v) => {
                        return {
                          id: v.id_pelanggan,
                          name: v.nama + ' / ' + v.no_hp,
                        };
                      })
                    "
                    :placeholder="'Pilih Pelanggan'"
                    useModel="1"
                    v-model="pelanggan"
                  />
                </div>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Menu</th>
                      <th>Qty</th>
                      <th>Total</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(d, i) in details" :key="i">
                      <td>
                        {{ d.menu.nama_menu }}
                      </td>
                      <td style="width: 50px">
                        <input
                          class="form-control"
                          v-model="d.jumlah"
                          @change="updateT(i, $event)"
                          type="number"
                          style="width: 50px; padding: 2px"
                        />
                      </td>
                      <td class="text-end">
                        {{ $filters.currency(d.subtotal) }}
                      </td>
                      <td style="width: 50px">
                        <button
                          class="btn btn-sm btn-danger"
                          type="button"
                          @click="removeItem(i)"
                        >
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                    <tr v-if="details.length > 0">
                      <td colspan="2">
                        <strong> Sub Total </strong>
                      </td>
                      <td class="text-end">
                        <strong> {{ $filters.currency(grand_total) }}</strong>
                      </td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
                <button
                  @click="simpan"
                  type="button"
                  v-if="details.length > 0"
                  class="btn btn-primary btn-block"
                  style="width: 100%"
                >
                  Simpan Transaksi
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import format from "date-fns/format";
import MenuN from "./components/Product.vue";
export default {
  components: {
    MenuN,
  },
  data() {
    return {
      pelanggan: "",
      pelanggans: [],
      details: [],
    };
  },
  mounted() {
    this.loadPelanggan();
  },
  computed: {
    grand_total() {
      return this.details.reduce((p, c) => {
        return p + c.subtotal;
      }, 0);
    },
  },
  watch: {},
  methods: {
    simpan() {
      if (!this.pelanggan) {
        this.$root.notif("pelanggan tidak boleh kosong", {
          type: "error",
          position: "top",
        });
        return;
      }
      if (!this.pelanggan.id) {
        this.$root.notif("pelanggan tidak boleh kosong", {
          type: "error",
          position: "top",
        });
        return;
      }
      var params = {
        tanggal_taransaksi: format(new Date(), "yyyy-MM-dd HH:mm:ss"),
        id_pelanggan: this.pelanggan.id,
        details: this.details.map((v) => {
          return {
            id_menu: v.id_menu,
            jumlah: v.jumlah,
            subtotal: v.subtotal,
          };
        }),
      };

      this.$axios
        .post("/app/transaksi", params)
        .then(() => {
          this.$root.notif("data berhasil disimpan", {
            type: "info",
            position: "top",
          });
          this.pelanggan = {};
          this.details = [];
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
    updateT(cek, e) {
      this.details[cek].subtotal =
        parseInt(e.target.value) * parseInt(this.details[cek].menu.harga);
    },
    removeItem(i) {
      this.details.splice(i, 1);
    },
    setMenu(item) {
      var cek = this.details.findIndex((v) => v.id_menu == item.id_menu);
      if (cek > -1) {
        this.details[cek].jumlah = this.details[cek].jumlah + 1;
        this.details[cek].subtotal =
          this.details[cek].jumlah * parseInt(item.harga);
      } else {
        this.details.push({
          menu: item,
          id_menu: item.id_menu,
          jumlah: 1,
          subtotal: 1 * parseInt(item.harga),
        });
      }
    },
    loadPelanggan() {
      this.$axios
        .get("/app/pelanggan", { params: {} })
        .then((res) => {
          this.pelanggans = res.data.data;
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
