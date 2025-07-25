import Login from "@/views/pages/extras/Login.vue";
import Register from "@/views/pages/extras/Register.vue";
import ForgotPassword from "@/views/pages/extras/ForgotPassword";
import ForgotPasswordChange from "@/views/pages/extras/ForgotPasswordChange";
const routes = [
  {
    path: "/login",
    name: "app.login",
    meta: {
      title: "Admin",
    },
    component: Login
  },
  {
    path: "/register",
    name: "register",
    meta: {
      title: "Admin",
    },
    component: Register
  },
  {
    path: "/forgot-password",
    name: "app.forgot-password",
    meta: {
      title: "Forgot Password",
    },
    component: ForgotPassword,
  },
  {
    path: "/forgot-password-change/:token",
    name: "app.forgot-password-change",
    meta: {
      title: "Change Password",
    },
    component: ForgotPasswordChange,
  },
];

export default routes;
