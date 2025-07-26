<template>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <div class="wrap">

                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">

                                    <div class="text-center">
                                        <!-- <img class="logo-login" :src="`/images/logo-s.png`" style="width: 200px;" /> -->
                                        Create Your Account
                                    </div>
                                </div>
                            </div>
                            <Form @submit="onSubmit" :validation-schema="schema" v-slot="{ errors }"
                                class="signin-form">
                                <div class="form-group mt-3">
                                    <label class="form-control-placeholder" for="username">Full Name</label>
                                    <Field name="nama" class="form-control" v-model="form.nama" :class="[
                                        { 'p-error': errors.nama },
                                    ]" type="text" placeholder="Enter your full name" />
                                    <span class="p-error" v-if="errors.nama">
                                        *{{ errors.nama }}
                                    </span>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="form-control-placeholder" for="username">Email</label>
                                    <Field name="email" class="form-control" v-model="form.email" :class="[
                                        { 'p-error': errors.email },
                                    ]" type="text" placeholder="Enter your email" />
                                    <span class="p-error" v-if="errors.email">
                                        *{{ errors.email }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-placeholder" for="password">Password</label>
                                    <Field name="password" class="form-control" v-model="form.password" :class="[
                                        { 'p-error': errors.password },
                                    ]" type="password" placeholder="Enter your password" />
                                    <span class="p-error" v-if="errors.password">
                                        *{{ errors.password }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-placeholder" for="password">Confirm Password</label>
                                    <Field name="con_password" class="form-control" v-model="form.con_password" :class="[
                                        { 'p-error': errors.con_password },
                                    ]" type="password" placeholder="Re-enter your password" />
                                    <span class="p-error" v-if="errors.con_password">
                                        *{{ errors.con_password }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="
                                            form-control
                                            btn btn-primary
                                            rounded
                                            submit
                                            px-3
                                        ">
                                        Register Now
                                    </button>
                                </div>
                                <div class="form-group text-center"
                                    style="border-bottom: 2px solid lightgray;position: relative; margin: 30px 0px;">
                                    <div style="position: absolute;
    background: white;
    left: 20%;
    padding-left: 10px;
    padding-right: 10px;
    top: -10px;">
                                        Already have an account?
                                    </div>
                                </div>
                                <div class="form-group">

                                    <button type="button" class="
                                            form-control
                                            btn btn-outline-primary
                                            rounded
                                            submit
                                            px-3
                                        " @click="$router.push({ name: 'app.login' })">
                                        Log In
                                    </button>
                                </div>
                                <div class="form-group row">

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

            this.$axios
                .post("/app/pelanggan-reg", values)
                .then((res) => {
                    this.$root.notif(res.message, {
                        type: "info",
                        position: "top",
                    });
                    // window.location.href = "/";
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
            email: yup.string().required(),
            nama: yup.string().required(),
            con_password: yup.string().required().oneOf([yup.ref('password'), null], 'Passwords must match'),
            password: yup.string().required().min(5),
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

@media (min-width: 1200px) {
    .container {
        max-width: 1140px;
    }
}
</style>
