<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>
          {{ title }}
        </h2>
        <ul class="nav navbar-right panel_toolbox">
          <li>
            <button class="btn btn-sm btn-outline-secondary" type="button" @click="$router.back()">
              <i class="fa fa-arrow-left" />
              Kembali
            </button>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <Form @submit="onSubmit" :validation-schema="schema" v-slot="{ errors }" class="">
          
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3  label-align" >Nama Menu <span
                class="required">*</span>
            </label>
            <div class="col-12  col-md-6 col-sm-6">
              <Field name="nama_menu" class="form-control" v-model="form.nama_menu" :class="[{ 'p-error': errors.nama_menu }]"
                type="text" />
              <span class="p-error" v-if="errors.nama_menu">
                *{{ errors.nama_menu }}
              </span>
            </div>
          </div>
          
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3  label-align" >Harga <span
                class="required">*</span>
            </label>
            <div class="col-12  col-md-6 col-sm-6">
              <Field name="harga" class="form-control" v-model="form.harga" :class="[{ 'p-error': errors.harga }]"
                type="number" />
              <span class="p-error" v-if="errors.harga">
                *{{ errors.harga }}
              </span>
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3  label-align" >Kategori <span
                class="required">*</span>
            </label>
            <div class="col-12  col-md-6 col-sm-6">
              <Field name="kategori_menu" class="form-control" v-model="form.kategori_menu" :class="[{ 'p-error': errors.kategori_menu }]"
                type="text" />
              <span class="p-error" v-if="errors.kategori_menu">
                *{{ errors.kategori_menu }}
              </span>
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3  label-align" >Status <span
                class="required">*</span>
            </label>
            <div class="col-12  col-md-6 col-sm-6">
              <Field name="status" class="form-control" v-model="form.status"  as="select">
                <option value="Aktif">Aktif</option>
                <option value="Non Aktif">Non Aktif</option>
              </Field>
              <span class="p-error" v-if="errors.status">
                *{{ errors.status }}
              </span>
            </div>
          </div>
          
          <div class="item">
            <div class="col-12  col-md-6 col-sm-6 offset-0 offset-md-3 mt-3">
              <button class="btn btn-outline-primary me-2" type="button" @click="$router.back()">
                Cancel
              </button>
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
      nama_menu: yup.string().required(),
      harga: yup.string().required(),
      kategori_menu: yup.string().required(),
    });
    return {
      id: "",
      g_qr: "",
      form: {
        nama_menu: "",
        harga: "",
        status: "Aktif",
        kategori_menu:""
      },
      old: {},
      schema,
      roles: [],
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
    reset() {
      this.form = {
        nama_menu: "",
      };
    },

    loadData() {
      this.$axios
        .get(`/app/menu/${this.id}`)
        .then((res) => {
          let data = Object.assign({}, res.data);
          this.old = data;
          // con
          this.form = data;
        })
        .catch((res) => {
          this.$root.notif(res.message, {
            type: "error",
            position: "top",
          });
        })
        .finally(() => { });
    },

    onSubmit(values) {
      this.$store.dispatch("loading", true);
      let sendData = () => {
        return this.$axios.post("/app/menu", values);
      };

      if (this.id) {
        sendData = () => {
          return this.$axios.put(`/app/menu/${this.id}`, values);
        };
      }
      sendData()
        .then((res) => {
          this.$router.push({
            name: "app.menu.list",
          });
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
    if (this.$route.params.id) {
      this.id = this.$route.params.id;
      this.loadData();
    }
  },
};
</script>
