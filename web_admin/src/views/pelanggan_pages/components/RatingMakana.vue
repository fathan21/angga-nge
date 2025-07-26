<template>
  <div class="">
      <div class="x_panel">
          <div class="x_title">
              <h4>
                  Rating Menu</h4>
              <div class="clearfix"></div>
          </div>

          <div class="x_content text-start">
              <div class="row">
                  <div class="col-12">
                      <div class="mb-4">
                          <table class="table table-sm table-striped table-bordered">
                              <thead>
                                  <tr>
                                      <th>Menu</th>
                                      <th>Rating</th>
                                      <th>Ulasan</th>
                                  </tr>
                              </thead>
                              <tbody>

                                  <tr v-for="(item, i) in jawaban" :key="i">
                                      <td>{{ item.nama_menu }}</td>
                                      <td>
                                        
                                      <div style="width: 150px">
                                          <star-rating v-model:rating="item.rating" :star-size="25"></star-rating>
                                      </div>
                                      </td>
                                      <td>
                                          <input type="text" class="form-control" v-model="item.ulasan"  />
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>

                      <div class="text-end">
                          <button type="button" @click="$emit('close')" class="btn btn-danger">
                              Batal
                          </button>
                          <button type="button" @click="simpanRating" class="btn btn-primary">
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
  props: {
      modelValue: {
          type: Boolean,
          default: false,
      },
      pertanyaan: {
          type: Array,
          default: () => [],
      },
      data: {
          type: Object,
          default: () => ({}),
      },
  },
  data() {
      return {
          rating: 0,
          jawaban: [],
          form: {
              ulasan: "",
              rating: 0
          },
          details: [],
      };
  },
  mounted() {

  },
  computed: {
      grand_total() {
          return this.details.reduce((p, c) => {
              return p + c.subtotal;
          }, 0);
      },
  },
  watch: {
      modelValue(val) {
          if (val) {
              this.form.ulasan = "";
              this.form.rating = 0;
              this.jawaban = this.data.details.map((v) => {
                  return {
                      nama_menu: v.menu.nama_menu,
                      id_menu: v.id_menu,
                      id_transaksi: v.id_transaksi,
                      rating: 0,
                      ulasan:""
                  };
              });
          }
      },
  },
  methods: {
      
      simpanRating() {

          var params = {
            type: "menu"
          };
          params.details = this.jawaban.map((v) => {
              v.tanggal = format(new Date(), "yyyy-MM-dd HH:mm:ss");
              v.tipe = 'Makanan';
              return v;
          });
          


              this.$axios
                  .post("/app/ulasan", params)
                  .then(() => {
                      this.$root.notif("data berhasil disimpan", {
                          type: "info",
                          position: "top",
                      });
                      this.pelanggan = {};
                      this.details = [];
                      this.$emit('close');
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