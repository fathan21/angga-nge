import { RouterView } from "vue-router";

const Setting = () =>
  import(/* webpackChunkName: "Setting" */ "@/views/pages/loyal/Setting.vue");
  const Form = () =>
    import(/* webpackChunkName: "Pelanggan-form" */ "@/views/pages/loyal/Loyal.vue");
const routes = [
  {
    path: "loyal",
    meta: {
      
    },
    component: RouterView,
    children: [
      {
        path: "setting",
        name: "app.loyal.setting",
        meta: {
          title: "Setting Parameter",
        },
        component: Setting,
      },
      {
        path: "pelanggan",
        name: "app.loyal.pelanggan",
        meta: {
          title: "Pelanggan Loyal",
        },
        component: Form,
      },
    ],
  },
];

export default routes;
