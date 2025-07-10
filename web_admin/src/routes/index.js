import {  createRouter } from "vue-router";
// import { createWebHistory } from "vue-router";
import { createWebHashHistory } from "vue-router";
import Admin from "@/views/layouts/Admin.vue";
import Cookies from "../plugins/cookies";

import adminRoute from "./admin";
import profileRoute from "./profile.js";
import authRoute from "./auth.js";


import Dashbaord from "@/views/pages/dashboard/Dashboard.vue";
const routes = [
  {
    path: "/",
    redirect: "/app",
  },
  {
    path: "/app",
    name: "app",
    component: Admin,
    meta: {
      title: "Dashboard",
      auth: true,
    },
    alias: "/app",
    children: [
      {
        path: "",
        name: "app.dashboard",
        meta: {
          title: "Dashboard",
        },
        component: Dashbaord
      },
      ...adminRoute,
      ...profileRoute
    ],
  },

  ...authRoute,
];

const router = createRouter({
  // history: createWebHistory(),
  history: createWebHashHistory(),
  routes,
});



router.beforeEach((to, from, next) => {
  /** set auth */
  const requiresAuth = to.matched
    .slice()
    .reverse()
    .find(r => r.meta && r.meta.auth);
    if (requiresAuth) {
      let isAuth = Cookies.get("access_token") || null;
      if (isAuth == null) {
        next({
          name: "app.login",
          query: { redirect: to.fullPath }
        });
        return;
      }
    }


  /** set title */
  const nearestWithTitle = to.matched
    .slice()
    .reverse()
    .find((r) => r.meta && r.meta.title);
  if (nearestWithTitle) {
    document.title = nearestWithTitle.meta.title;
  }

  next();
});
// router.afterEach(() => {
// })

export default router;
