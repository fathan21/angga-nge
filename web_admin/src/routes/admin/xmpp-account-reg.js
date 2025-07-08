import { RouterView } from "vue-router";

const Index = () =>
  import( "@/views/pages/xmpp-account-reg/Index.vue");

const routes = [
  {
    path: "xmpp-account-reg",
    name: "app.xmpp-account-reg",
    meta: {
      title: "Regis Akun Xmpp",
    },
    component: RouterView,
    children: [
      {
        path: "",
        name: "app.xmpp-account-reg.list",
        meta: {
          title: "Regis Akun Xmpp",
        },
        component: Index,
      }
    ],
  },
];

export default routes;
