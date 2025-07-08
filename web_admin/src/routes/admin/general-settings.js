import { RouterView } from "vue-router";

const Index = () =>
  import(
    /* webpackChunkName: "general-settings-index" */ "@/views/pages/general-settings/Index.vue"
  );
  

const routes = {
  path: "general-settings",
  component: RouterView,
  children: [
    {
      path: "",
      name: "app.general-settings",
      meta: {
        title: "General Setting",
      },
      component: Index,
    }
  ],
};

export default routes;
