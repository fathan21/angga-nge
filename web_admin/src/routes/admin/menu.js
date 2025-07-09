import { RouterView } from "vue-router";

const Index = () =>
  import(/* webpackChunkName: "menu" */ "@/views/pages/menu/Index.vue");
  const Form = () =>
    import(/* webpackChunkName: "menu-form" */ "@/views/pages/menu/Form.vue");
const routes = [
  {
    path: "menu",
    name: "app.menu",
    component: RouterView,
    children: [
      {
        path: "",
        name: "app.menu.list",
        meta: {
          title: "Menu",
        },
        component: Index,
      },
      {
        path: "form/:id?/:action?",
        name: "app.menu.form",
        meta: {
          title: "Menu Form",
        },
        component: Form,
      },
    ],
  },
];

export default routes;
