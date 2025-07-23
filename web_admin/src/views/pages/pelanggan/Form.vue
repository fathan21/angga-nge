<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>
          {{ title }}
        </h2>
        <ul class="nav navbar-right panel_toolbox">
          <li>
            <button
              class="btn btn-sm btn-outline-secondary"
              type="button"
              @click="$router.back()"
            >
              <i class="fa fa-arrow-left" />
              Kembali
            </button>
          </li>
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
              >Nama <span class="required">*</span>
            </label>
            <div class="col-12 col-md-6 col-sm-6">
              <Field
                name="nama"
                class="form-control"
                v-model="form.nama"
                :class="[{ 'p-error': errors.nama }]"
                type="text"
              />
              <span class="p-error" v-if="errors.nama">
                *{{ errors.nama }}
              </span>
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3 label-align"
              >No Hp <span class="required">*</span>
            </label>
            <div class="col-12 col-md-6 col-sm-6">
              <Field
                name="no_hp"
                class="form-control"
                v-model="form.no_hp"
                :class="[{ 'p-error': errors.no_hp }]"
                type="text"
              />
              <span class="p-error" v-if="errors.no_hp">
                *{{ errors.no_hp }}
              </span>
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3 label-align"
              >Email <span class="required">*</span>
            </label>
            <div class="col-12 col-md-6 col-sm-6">
              <Field
                name="email"
                class="form-control"
                v-model="form.email"
                :class="[{ 'p-error': errors.email }]"
                type="text"
              />
              <span class="p-error" v-if="errors.email">
                *{{ errors.email }}
              </span>
            </div>
          </div>

          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3  label-align" >Password <span
                class="required">*</span>
            </label>
            <div class="col-12  col-md-6 col-sm-6">
              <Field name="password" class="form-control" v-model="form.password"
                :class="[{ 'p-error': errors.password }]" type="password" />
              <span class="p-error" v-if="errors.password">
                *{{ errors.password }}
              </span>
            </div>
          </div>

          <div class="item">
            <div class="col-12 col-md-6 col-sm-6 offset-0 offset-md-3 mt-3">
              <button
                class="btn btn-outline-primary me-2"
                type="button"
                @click="$router.back()"
              >
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
      nama: yup.string().required(),
      no_hp: yup.string().required(),
      password: !this.$route.params.id
        ? yup.string().required().min(5)
        : yup.string(),
    });
    return {
      id: "",
      g_qr: "",
      form: {
        no_hp: "",
        nama: ""
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
        username: "",
        email: "",
        phone: "",
        password: "",
        role: "",
      };
    },

    loadData() {
      this.$axios
        .get(`/app/pelanggan/${this.id}`)
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
        .finally(() => {});
    },

    onSubmit(values) {
      this.$store.dispatch("loading", true);
      let sendData = () => {
        return this.$axios.post("/app/pelanggan", values);
      };

      if (this.id) {
        sendData = () => {
          return this.$axios.put(`/app/pelanggan/${this.id}`, values);
        };
      }
      sendData()
        .then((res) => {
          this.$router.push({
            name: "app.pelanggan.list",
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
