import { RouterView } from "vue-router";

import InputTransaksi from "@/views/pelanggan_pages/InputTransaksi.vue";
import Ulasan from "@/views/pelanggan_pages/Ulasan.vue";
import DaftarPesanan from "@/views/pelanggan_pages/DaftarPesanan.vue";
import Loyal from "@/views/pelanggan_pages/Loyal.vue";
import Promo from "@/views/pelanggan_pages/Promo.vue";
import HistoryTransaksi from "@/views/pelanggan_pages/HistoryTransaksi.vue";

const routes = [
  {
    path: "pelanggan",
    meta: {
      title: "pelanggan",
    },
    component: RouterView,
    children: [
      {
        path: "input-transaksi",
        name: "pelanggan.input-transaksi",
        meta: {
            title: "Input Transaksi",
        },
        component: InputTransaksi
      },
      {
        path: "ulasan",
        name: "pelanggan.ulasan",
        meta: {
            title: "Ulasan",
        },
        component: Ulasan
      },
      {
        path: "pesanan",
        name: "pelanggan.pesanan",
        meta: {
            title: "Daftar Pesanan",
        },
        component: DaftarPesanan
      },
      {
        path: "loyal",
        name: "pelanggan.loyal",
        meta: {
            title: "Pelanggan Loyal",
        },
        component: Loyal
      },
      {
        path: "history",
        name: "pelanggan.history-transaksi",
        meta: {
            title: "History Transaksi",
        },
        component: HistoryTransaksi
      },
      {
        path: "promo",
        name: "pelanggan.promo",
        meta: {
            title: "Promo",
        },
        component: Promo
      },
    ],
  },
];

export default routes;
