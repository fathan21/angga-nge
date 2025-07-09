import { RouterView } from "vue-router";

const trx = () => import("@/views/pages/laporan/Transaksi.vue");
const pelanggan = () => import("@/views/pages/laporan/Pelanggan.vue");
const routes = [
  {
    path: "laporan",
    component: RouterView,
    children: [
      {
        path: "trx",
        name: "app.laporan.trx",
        meta: {
          title: "Laporan Transaksi",
        },
        component: trx,
      },
      {
        path: "pelanggan",
        name: "app.laporan.pelanggan",
        meta: {
          title: "Laporan Pelanggan Loyal",
        },
        component: pelanggan,
      },
    ],
  },
];

export default routes;
