<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        <ul class="nav navbar-right panel_toolbox">
          <li>&nbsp;</li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content text-center">
        <div class="row">
          <div class="col-12">
            <h1>Rating</h1>
          </div>
          <div class="col-12">
            <h3>Bagaimana dengan pelayanan kami?</h3>
            <br />
            <div class="my-2" style="display: flex; justify-content: center">
              <div style="width: 250px">
                <star-rating v-model:rating="rating"></star-rating>
              </div>
            </div>
            <br />
            <br />
            <div>
              <button
                type="button"
                @click="simpanRating"
                style="width: 200px"
                class="btn btn-primary"
              >
                Kirim
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import format from "date-fns/format";
import StarRating from "vue-star-rating";
export default {
  components: {
    StarRating,
  },
  data() {
    return {
      rating: 0,
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
    simpanRating() {
      if (!this.rating) {
        this.$root.notif("rating tidak boleh kosong", {
          type: "error",
          position: "top",
        });
      }
      this.$store.dispatch("loading", true);
      setTimeout(() => {
        this.$root.notif("terima kasih telah memberikan rating", {
          type: "info",
          position: "top",
        });
        this.rating = 0;
        this.$store.dispatch("loading", false);
      }, 500);
    },
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
        tanggal_transaksi: format(new Date(), "yyyy-MM-dd HH:mm:ss"),
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
<style>
.vue-star-rating-rating-text {
  display: none !important;
}
</style>
