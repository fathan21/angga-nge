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
            <label class="col-form-label col-12 col-md-3  label-align" >Nama <span
                class="required">*</span>
            </label>
            <div class="col-12  col-md-6 col-sm-6">
              <Field name="name" class="form-control" v-model="form.name" :class="[{ 'p-error': errors.name }]"
                type="text" />
              <span class="p-error" v-if="errors.name">
                *{{ errors.name }}
              </span>
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3  label-align" >Username <span
                class="required">*</span>
            </label>
            <div class="col-12  col-md-3">
              <Field name="username" class="form-control" v-model="form.username" :class="[{ 'p-error': errors.username }]"
                type="text" />
              <span class="p-error" v-if="errors.username">
                *{{ errors.username }}
              </span>
            </div>
            <div class="">
              @
            </div>
            <div class="col-12  col-md-2">
              <Field name="host" class="form-control" v-model="form.host" :class="[{ 'p-error': errors.host }]"
                type="text"  as="select">
                  <option v-for="h in host" :key="h" :value="h" v-text="h"></option>
              </Field>
              <span class="p-error" v-if="errors.host">
                *{{ errors.host }}
              </span>
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3  label-align" >Phone <span
                class="required">*</span>
            </label>
            <div class="col-12  col-md-6 col-sm-6">
              <Field name="phone" class="form-control" v-model="form.phone" :class="[{ 'p-error': errors.phone }]"
                type="text" />
              <span class="p-error" v-if="errors.phone">
                *{{ errors.phone }}
              </span>
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3  label-align" >Telegram Id <span
                class="required">*</span>
            </label>
            <div class="col-12  col-md-6 col-sm-6">
              <Field name="telegram_id" class="form-control" v-model="form.telegram_id" :class="[{ 'p-error': errors.telegram_id }]"
                type="text" />
              <span class="p-error" v-if="errors.telegram_id">
                *{{ errors.telegram_id }}
              </span>
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-12 col-md-3  label-align" >Password <span
                class="required">*</span>
            </label>
            <div class="col-12  col-md-6 col-sm-6">
              <Field name="password" class="form-control" v-model="form.password"
                :class="[{ 'p-error': errors.password }]" type="text" />
              <span class="p-error" v-if="errors.password">
                *{{ errors.password }}
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
      name: yup.string().required(),
      host: yup.string().required(),
      phone: yup.string().required(),
      telegram_id: yup.string().nullable(),
      password: !this.$route.params.id
        ? yup.string().required().min(6)
        : yup.string(),
    });
    return {
      id: "",
      g_qr: "",
      form: {
        name: "",
        email: "",
        phone: "",
        password: "",
        telegram_id:"",
        host: "r1jabber.com",
      },
      old: {},
      schema,
      host: [],
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
        name: "",
        email: "",
        phone: "",
        password: "",
        host: "",
      };
    },

    laodHost() {
      this.$axios
        .get(`/app/xmpp-host`)
        .then((res) => {
          this.host = res.data.data;
        });
    },
    loadData() {
      this.$axios
        .get(`/app/xmpp-accounts/${this.id}`)
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
        return this.$axios.post("/app/xmpp-accounts", values);
      };

      if (this.id) {
        sendData = () => {
          return this.$axios.put(`/app/xmpp-accounts/${this.id}`, values);
        };
      }
      sendData()
        .then((res) => {
          this.$router.push({
            name: "app.xmpp-account.list",
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
    this.laodHost();
    if (this.$route.params.id) {
      this.id = this.$route.params.id;
      this.loadData();
    }
  },
};
</script>
