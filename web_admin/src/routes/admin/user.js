import { RouterView } from "vue-router";

const Index = () =>
  import(/* webpackChunkName: "user" */ "@/views/pages/users/Index.vue");
  const Form = () =>
    import(/* webpackChunkName: "user-form" */ "@/views/pages/users/Form.vue");
const routes = [
  {
    path: "users",
    name: "app.users",
    meta: {
      title: "Admin",
    },
    component: RouterView,
    children: [
      {
        path: "",
        name: "app.users.list",
        meta: {
          title: "Admin",
        },
        component: Index,
      },
      {
        path: "form/:id?/:action?",
        name: "app.users.form",
        meta: {
          title: "Admin Form",
        },
        component: Form,
      },
    ],
  },
];

export default routes;
