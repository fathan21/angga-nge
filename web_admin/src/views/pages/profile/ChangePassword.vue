<template>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_content">
                <br />
                <Form
                    @submit="onSubmit"
                    :validation-schema="schema"
                    v-slot="{ errors }"
                    class="signin-form"
                >
                    <div class="item form-group">
                        <label
                            class="col-form-label col-md-3 col-sm-3 label-align"
                            for="first-name"
                            >Password Lama <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <Field
                                name="password_old"
                                class="form-control"
                                v-model="form.password_old"
                                :class="[{ 'p-error': errors.password_old }]"
                                type="password"
                            />
                            <span class="p-error" v-if="errors.password_old">
                                *{{ errors.password_old }}
                            </span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label
                            class="col-form-label col-md-3 col-sm-3 label-align"
                            for="first-name"
                            >Password Baru <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <Field
                                name="password"
                                class="form-control"
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
                        <label
                            class="col-form-label col-md-3 col-sm-3 label-align"
                            for="first-name"
                            >Konfirmasi Password Baru
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <Field
                                name="password_confirmation"
                                class="form-control"
                                v-model="form.password_confirmation"
                                :class="[
                                    { 'p-error': errors.password_confirmation },
                                ]"
                                type="password"
                            />
                            <span
                                class="p-error"
                                v-if="errors.password_confirmation"
                            >
                                *{{ errors.password_confirmation }}
                            </span>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
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
            password_old: yup.string().required(),
            password_confirmation: yup.string().required(),
            password: !this.$route.params.id
                ? yup.string().required().min(6)
                : yup.string(),
        });
        return {
            id: "",
            form: {
                password_old: "",
                password_confirmation: "",
                password: "",
            },
            old: {},
            schema,
        };
    },
    computed: {},
    methods: {
        reset() {
            this.form = {
                password_old: "",
                password_confirmation: "",
                password: "",
            };
        },
        onSubmit(values, { resetForm }) {
            this.$store.dispatch("loading", true);

            let sendData = () => {
                return this.$axios.post("/change-password", values);
            };

            sendData()
                .then((res) => {
                    this.$root.notif(res.message);
                    resetForm();
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

    mounted() {},
};
</script>

