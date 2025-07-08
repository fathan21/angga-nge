import { RouterView } from "vue-router";

import Profile from "@/views/pages/profile/Profile.vue";
import ChangePassword from "@/views/pages/profile/ChangePassword.vue";
const routes = [
  {
    path: "p",
    meta: {
      title: "p",
    },
    alias: "app.profile",
    component: RouterView,
    children: [
      {
        path: "",
        name: "app.profile",
        meta: {
            title: "Profile",
        },
        component: Profile
      },

      {
        path: "change-password",
        name: "app.change-password",
        meta: {
            title: "Change Password",
        },
        component: ChangePassword
      }
    ],
  },
];

export default routes;
