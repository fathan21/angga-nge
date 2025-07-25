import { RouterView } from "vue-router";

const Setting = () =>
  import(/* webpackChunkName: "Setting" */ "@/views/pages/ulasan/Setting.vue");
  const Form = () =>
    import(/* webpackChunkName: "ulasan-form" */ "@/views/pages/ulasan/Ulasan.vue");
const routes = [
  {
    path: "ulasan",
    meta: {
      
    },
    component: RouterView,
    children: [
      {
        path: "setting",
        name: "app.ulasan.setting",
        meta: {
          title: "Setting Parameter",
        },
        component: Setting,
      },
      {
        path: "ulasan",
        name: "app.ulasan.ulasan",
        meta: {
          title: "Ulasan",
        },
        component: Form,
      },
    ],
  },
];

export default routes;
