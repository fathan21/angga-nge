<template>
    <section class="ftco-section">
      <div class="container container-login">
        <div class="row justify-content-center">
          <div class="" style="">
            <div class="wrap">
              <div class="login-wrap p-4 p-md-5">
                <div class="d-flex">
                    <div class="w-100">
                        <h3 class="">Forgot Password</h3>
                    </div>
                </div>
                <Form
                  @submit="onSubmit"
                  :validation-schema="schema"
                  v-slot="{ errors }"
                  class="signin-form"
                >
                  <div class="form-group mt-3">
                    <Field
                      name="username"
                      class="form-control"
                      placeholder="Email / No Hp / Username"
                      v-model="form.username"
                      :class="[{ 'p-error': errors.username }]"
                      type="text"
                    />
                    <span class="p-error" v-if="errors.username">
                      *{{ errors.username }}
                    </span>
                  </div>
                  <div class="form-group">
                    <button
                      type="submit"
                      class="form-control btn btn-primary rounded submit px-3"
                    >
                      <i class="fa fa-btn fa-envelope"></i>
                      Send Password Reset
                    </button>
                  </div>
                  <div class="form-group row">
                    <div class="col-6 text-left">
                      
                    </div>
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
    methods: {
      login() {
        this.$router.push({
          name: "app",
        });
      },
      onSubmit(values) {
        this.$store.dispatch("loading", true);
        values.link = window.location.href.replace(
          "forgot-password",
          "forgot-password-change"
        );
        this.$axios
          .post("/forgot-password", values)
          .then((res) => {
            this.$root.notif(res.message, {
              type: "info",
              position: "top",
            });
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
  
    data() {
      const schema = yup.object({
        username: yup.string().required(),
      });
      return {
        form: {
          username: "",
          password: "",
        },
        schema,
      };
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
  