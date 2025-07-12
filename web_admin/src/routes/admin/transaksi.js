import { RouterView } from "vue-router";

const Index = () =>
  import(/* webpackChunkName: "transaksi" */ "@/views/pages/transaksi/Index.vue");
  const Form = () =>
    import(/* webpackChunkName: "transaksi-form" */ "@/views/pages/transaksi/Form.vue");
  const Rating = () =>
    import(/* webpackChunkName: "Rating-form" */ "@/views/pages/transaksi/Rating.vue");
const routes = [
  {
    path: "transaksi",
    component: RouterView,
    children: [
      {
        path: "",
        name: "app.transaksi.list",
        meta: {
          title: "Transaksi",
        },
        component: Index,
      },
      {
        path: "form/:id?/:action?",
        name: "app.transaksi.form",
        meta: {
          title: "Admin Form",
        },
        component: Form,
      },
      {
        path: "rating",
        name: "app.transaksi.rating",
        meta: {
          title: "Rating",
        },
        component: Rating,
      },
    ],
  },
];

export default routes;
