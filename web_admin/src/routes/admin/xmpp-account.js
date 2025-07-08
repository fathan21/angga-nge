import { RouterView } from "vue-router";

const Index = () =>
  import( "@/views/pages/xmpp-account/Index.vue");
  const Form = () =>
    import( "@/views/pages/xmpp-account/Form.vue");
const routes = [
  {
    path: "xmpp-account",
    name: "app.xmpp-account",
    meta: {
      title: "Akun Xmpp",
    },
    component: RouterView,
    children: [
      {
        path: "",
        name: "app.xmpp-account.list",
        meta: {
          title: "Akun Xmpp",
        },
        component: Index,
      },
      {
        path: "form/:id?/:action?",
        name: "app.xmpp-account.form",
        meta: {
          title: "Akun Xmpp Form",
        },
        component: Form,
      },
    ],
  },
];

export default routes;
