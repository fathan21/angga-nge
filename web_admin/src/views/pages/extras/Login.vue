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
                                    <img class="logo-login" :src="`/images/logo-s.png`" style="width: 200px;" />
                                </div>
                                </div>
                            </div>
                            <Form
                                @submit="onSubmit"
                                :validation-schema="schema"
                                v-slot="{ errors }"
                                class="signin-form"
                            >
                                <div class="form-group mt-3">
                                    <label
                                        class="form-control-placeholder"
                                        for="username"
                                        >Username</label
                                    >
                                    <Field
                                        name="username"
                                        class="form-control"
                                        v-model="form.username"
                                        :class="[
                                            { 'p-error': errors.username },
                                        ]"
                                        type="text"
                                    />
                                    <span
                                        class="p-error"
                                        v-if="errors.username"
                                    >
                                        *{{ errors.username }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label
                                        class="form-control-placeholder"
                                        for="password"
                                        >Password</label
                                    >
                                    <Field
                                        name="password"
                                        class="form-control"
                                        v-model="form.password"
                                        :class="[
                                            { 'p-error': errors.password },
                                        ]"
                                        type="password"
                                    />
                                    <span
                                        class="p-error"
                                        v-if="errors.password"
                                    >
                                        *{{ errors.password }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <button
                                        type="submit"
                                        class="
                                            form-control
                                            btn btn-primary
                                            rounded
                                            submit
                                            px-3
                                        "
                                    >
                                        Sign In
                                    </button>
                                </div>
                                <div class="form-group row">
                                    <div class="col-6 text-left">
                                    </div>
                                    <div
                                        class="
                                            col-6
                                            text-grey text-danger text-end
                                        "
                                    >
                                        <!-- <a href="#" @click="$router.push({name:'app.forgot-password'})">Forgot Password</a> -->
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

            this.$axios
                .post("/app/auth/login", values)
                .then((res) => {
                    this.$store.dispatch("auth/login", res.data);
                    if (res.data) {
                        this.$axios.defaults.headers.common["X-AUTH"] =
                            "" + res.data.access_token;
                    }
                    this.$router.push("/");
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
            username: yup.string().required(),
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
