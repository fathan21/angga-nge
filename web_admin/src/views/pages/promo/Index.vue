<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>&nbsp;</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li></li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <Form
          @submit="onSubmit"
          :validation-schema="schema"
          v-slot="{ errors }"
          class=""
        >
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3 label-align"
              >Nama Promo <span class="required">*</span>
            </label>
            <div class="col-12 col-md-6 col-sm-6">
              <Field
                name="nama_promo"
                class="form-control"
                v-model="form.nama_promo"
                :class="[{ 'p-error': errors.nama_promo }]"
                type="text"
              />
              <span class="p-error" v-if="errors.nama_promo">
                *{{ errors.nama_promo }}
              </span>
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3 label-align"
              >&nbsp;
            </label>
            <div class="col-12 col-md-6 col-sm-6">
              <div>
                <button
                  class="btn btn-outline-primary btn-sm mb-2"
                  type="button"
                  @click="addItem"
                >
                  Tambah
                </button>
                <table class="table table-sm table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Min. Trx</th>
                      <th>Max. Trx</th>
                      <th>Poin Yang didapat</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(d, i) in details" :key="i">
                      <td>
                        {{ i+1 }}
                      </td>
                      <td>
                        <input
                          class="form-control"
                          v-model="d.min_trx"
                          type="number"
                        />
                      </td>
                      <td>
                        <input
                          class="form-control"
                          v-model="d.max_trx"
                          type="number"
                        />
                      </td>
                      <td>
                        <input
                          class="form-control"
                          v-model="d.poin"
                          type="number"
                        />
                      </td>
                      <td>
                        <button
                          class="btn btn-sm btn-danger"
                          type="button"
                          @click="removeItem(i)"
                        >
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="col-12 col-md-6 col-sm-6 offset-0 offset-md-3 mt-3">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </Form>
      </div>
    </div>
  </div>
</template>

<script>
import { Form, Field } from "vee-validate";
import * as yup from "yup";

export default {
  components: {
    Form,
    Field,
  },
  data() {
    const schema = yup.object({
      nama_promo: yup.string().required(),
    });
    return {
      id: "",
      g_qr: "",
      form: {
        nama_promo: "",
      },
      old: {},
      schema,
      roles: [],
      details: [],
    };
  },
  computed: {
    title() {
      if (this.id) {
        return "Ubah";
      }
      return "Tambah";
    },
  },
  methods: {
    addItem() {
      this.details.push({});
    },
    removeItem(i) {
      this.details.splice(i, 1);
    },
    reset() {
      this.form = {
        nama_promo: "",
      };
    },

    loadData() {
      this.$axios
        .get(`/app/promo`)
        .then((res) => {
          let data = Object.assign({}, res.data);
          this.old = data;
          // con
          this.form = data;
          this.details = data.details;
        })
        .catch((res) => {
          this.$root.notif(res.message, {
            type: "error",
            position: "top",
          });
        })
        .finally(() => {});
    },

    onSubmit(values) {
      values.details = this.details;
      this.$store.dispatch("loading", true);
      let sendData = () => {
        return this.$axios.post("/app/promo", values);
      };
      sendData()
        .then((res) => {
          this.$root.notif(res.message);
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
  },

  mounted() {
    this.loadData();
  },
};
</script>
