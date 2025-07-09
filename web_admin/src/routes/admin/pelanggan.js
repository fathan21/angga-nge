import { RouterView } from "vue-router";

const Index = () =>
  import(/* webpackChunkName: "Pelanggan" */ "@/views/pages/pelanggan/Index.vue");
  const Form = () =>
    import(/* webpackChunkName: "Pelanggan-form" */ "@/views/pages/pelanggan/Form.vue");
const routes = [
  {
    path: "pelanggan",
    meta: {
      
    },
    component: RouterView,
    children: [
      {
        path: "",
        name: "app.pelanggan.list",
        meta: {
          title: "Pelanggan",
        },
        component: Index,
      },
      {
        path: "form/:id?/:action?",
        name: "app.pelanggan.form",
        meta: {
          title: "Pelanggan Form",
        },
        component: Form,
      },
    ],
  },
];

export default routes;
