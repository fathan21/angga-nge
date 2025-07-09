import { RouterView } from "vue-router";

const Index = () =>
  import(/* webpackChunkName: "promo" */ "@/views/pages/promo/Index.vue");
  const Form = () =>
    import(/* webpackChunkName: "promo-form" */ "@/views/pages/promo/Form.vue");
const routes = [
  {
    path: "promo",
    component: RouterView,
    children: [
      {
        path: "",
        name: "app.promo.list",
        meta: {
          title: "Promo",
        },
        component: Index,
      },
      {
        path: "form/:id?/:action?",
        name: "app.users.form",
        meta: {
          title: "Promo Form",
        },
        component: Form,
      },
    ],
  },
];

export default routes;
