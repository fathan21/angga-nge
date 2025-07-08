<template>
    <section class="ftco-section">
      <div class="container container-login">
        <div class="row justify-content-center">
          <div class="" style="max-width: 450px; width: 100%">
            <div class="wrap">
                
              <div class="login-wrap px-5 py-2" v-if="!tokenTrue">
                Data tidak ditemukan
              </div>
              <div class="login-wrap px-5 py-2" v-else-if="isSuccess">
                <div class="alert alert-warning">
                  Pergantian password berhasil,
                  <router-link to="/login" class="btn btn-link text-white"
                    >Login</router-link
                  >
                </div>
              </div>
              <div class="login-wrap px-5 py-2" v-else>
                <div class="text-center fw-bold text-12 mt-3 mb-3 h3">
                  Ganti Password
                </div>
                <Form
                  @submit="onSubmit"
                  :validation-schema="schema"
                  v-slot="{ errors }"
                  class="signin-form"
                >
                  <div class="item form-group">
                    <div class="col-12">
                      <Field
                        name="username"
                        class="form-control"
                        placeholder="Phone"
                        v-model="form.username"
                        disabled
                      />
                    </div>
                  </div>
                  <div class="item form-group">
                    <div class="col-12">
                      <Field
                        name="password"
                        class="form-control"
                        placeholder="Password Baru"
                        v-model="form.password"
                        :class="[{ 'p-error': errors.password }]"
                        type="password"
                      />
                      <span class="p-error" v-if="errors.password">
                        *{{ errors.password }}
                      </span>
                    </div>
                  </div>
                  <div class="item form-group">
                    <div class="col-12">
                      <Field
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Konfirmasi Password Baru"
                        v-model="form.password_confirmation"
                        :class="[
                          {
                            'p-error': errors.password_confirmation,
                          },
                        ]"
                        type="password"
                      />
                      <span class="p-error" v-if="errors.password_confirmation">
                        *{{ errors.password_confirmation }}
                      </span>
                    </div>
                  </div>
                  <div class="form-group">
                    <button
                      type="submit"
                      class="form-control btn btn-primary rounded submit px-3"
                    >
                      Change
                    </button>
                  </div>
                  <div class="form-group row">
                    <div class="col-6 text-left"></div>
                    <div class="col-6 text-grey text-danger text-end">
                      <router-link to="/login">Login</router-link>
                    </div>
                  </div>
                </Form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
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
        password_confirmation: yup.string().required("password baru"),
        password: !this.$route.params.id
          ? yup.string().required(" konifrmasi password baru").min(6)
          : yup.string(),
      });
      return {
        id: "",
        form: {
          username: "",
          password_confirmation: "",
          password: "",
        },
        old: {},
        tokenTrue: false,
        isSuccess: false,
        schema,
      };
    },
    computed: {},
    methods: {
      reset() {
        this.form = {
          username: "",
          password_confirmation: "",
          password: "",
        };
      },
      cekToken() {
        this.$store.dispatch("loading", true);
  
        let sendData = () => {
          return this.$axios.get("/forgot-password/" + this.$route.params.token);
        };
  
        sendData()
          .then((res) => {
            let data = res.data;
            this.form.username = data.username;
            this.tokenTrue = true;
          })
          .catch(() => {
            this.tokenTrue = false;
          })
          .finally(() => {
            this.$store.dispatch("loading", false);
          });
      },
      onSubmit(values, { resetForm }) {
        this.$store.dispatch("loading", true);
        values.token = this.$route.params.token;
        let sendData = () => {
          return this.$axios.post("/forgot-password-change", values);
        };
  
        sendData()
          .then((res) => {
            this.$root.notif(res.message);
            this.isSuccess = true;
            resetForm();
          })
          .catch((res) => {
            this.$root.notif(res.message, {
              type: "error",
              position: "top",
            });
            this.cekToken();
          })
          .finally(() => {
            this.$store.dispatch("loading", false);
          });
      },
    },
  
    mounted() {
      this.cekToken();
    },
  };
  </script>
  
  <style scoped>
  .wrap {
    width: 100%;
    overflow: hidden;
    background: #fff;
    border-radius: 5px;
    -webkit-box-shadow: 0 10px 34px -15px rgb(0 0 0 / 24%);
    -moz-box-shadow: 0 10px 34px -15px rgba(0, 0, 0, 0.24);
    box-shadow: 0 10px 34px -15px rgb(0 0 0 / 24%);
    font-size: 16px;
  }
  .container-login {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  </style>
  