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
                    &nbsp;&nbsp;&nbsp;&nbsp;{{ $store.state.auth.user.username  }}
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
        <ModalBase v-model="modalRating">
          <b-card>
            <div class="row text-center">
              <div class="col-12">
                <h1>Rating</h1>
              </div>
              <div class="col-12">
                <h5>Bagaimana dengan pelayanan kami?</h5>
                <br />
                <div class="my-2" style="display: flex; justify-content: center">
                  <div style="width: 250px">
                    <star-rating v-model:rating="rating"></star-rating>
                  </div>
                </div>
                <br />
                <br />
                <div class="text-end">
                  <button
                    type="button"
                    @click="closeRating"
                    class="btn btn-outline-primary"
                  >
                    Batal
                  </button>
                  <button
                    type="button"
                    @click="simpanRating"
                    class="btn btn-primary"
                  >
                    Kirim
                  </button>
                </div>
              </div>
            </div>
          </b-card>
        </ModalBase>
      </div>
    </div>
  </template>
  
  <script>
  import format from "date-fns/format";
  import MenuN from "../pages/transaksi/components/Product.vue";
  import StarRating from "vue-star-rating";
  export default {
    components: {
      MenuN,
      StarRating,
    },
    data() {
      return {
        modalRating: false,
        rating: 0,
        modalData: {},
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
      closeRating() {
        this.modalRating = false;
      },
      simpanRating() {
        if (!this.rating) {
          this.$root.notif("rating tidak boleh kosong", {
            type: "error",
            position: "top",
          });
        }
  
        this.$store.dispatch("loading", true);
        this.$axios
          .post("/app/transaksi-rating/" + this.modalData.id_transaksi, {
            rating: this.rating,
          })
          .then(() => {
            this.$root.notif("terima kasih telah memberikan rating", {
              type: "info",
              position: "top",
            });
            this.modalRating = false;
          })
          .catch((res) => {
            this.$root.notif(res.message, {
              type: "error",
              position: "top",
            });
          })
          .finally(() => {
            this.$store.dispatch("loading", false);
          });
      },
      simpan() {
        var params = {
          tanggal_transaksi: format(new Date(), "yyyy-MM-dd HH:mm:ss"),
          id_pelanggan: this.$store.state.auth.user.id,
          details: this.details.map((v) => {
            return {
              id_menu: v.id_menu,
              jumlah: v.jumlah,
              diskon: v.diskon,
              harga: v.harga_awal,
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
            this.$router.push({
              name: "pelanggan.pesanan",
            });
            // this.modalRating = true;
            // this.modalData = res.data;
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
            harga_awal: item.harga_awal,
            harga: item.harga,
            diskon: item.diskon,
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
  
  <style>
  .vue-star-rating-rating-text {
    display: none !important;
  }
  </style>
  