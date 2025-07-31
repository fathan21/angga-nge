// const navAdmin = [
//   {
//     name: "Data Admin",
//     label: "Dashboard",
//     icon: "fa fa-home",
//     route: "app.dashboard",
//   },
//   {
//     name: "Data Pelanggan",
//     label: "Data Pelanggan",
//     icon: "fa fa-users",
//     route: "app.pelanggan.list",
//   },
//   {
//     name: "Input Transaksi",
//     label: "Input Transaksi",
//     icon: "fa fa-list",
//     route: "app.transaksi.list",
//   },
//   {
//     name: "Data Menu",
//     label: "Data Menu",
//     icon: "fa fa-cubes",
//     route: "app.menu.list",
//   },
//   {
//     name:"Laporan",
//     label: "Laporan",
//     icon: "fa fa-bar-chart",
//     childs: [
//       {
//         label: "Laporan Transaksi",
//         route: "app.laporan.trx",
//       },
//       {
//         label: "Laporan Pelanggan Loyal",
//         route: "app.laporan.pelanggan",
//       },
//       {
//         label: "Laporan Rating",
//         route: "app.laporan.rating",
//       }
//     ],
//   },
//   {
//     name: "Promo & Poin",
//     label: "Promo & Poin",
//     icon: "fa fa-percent",
//     route: "app.promo.list",
//   },
//   {
//     name: "Data Admin",
//     label: "Data Admin",
//     icon: "fa fa-user",
//     route: "app.users.list",
//   },

// ];
const navAdmin = [
  {
    name: "Data Admin",
    label: "Dashboard",
    icon: "fa fa-home",
    route: "app.dashboard",
  },
  {
    name: "Daftar Pesanan",
    label: "Daftar Pesanan",
    icon: "fa fa-list",
    route: "app.transaksi.pesanan",
    // notif_name: "notif_pesanan",
  },
  {
    name: "Data Menu",
    label: "Data Menu",
    icon: "fa fa-cubes",
    route: "app.menu.list",
  },
  {
    name: "Promo",
    label: "Promo",
    icon: "fa fa-bar-chart",
    childs: [
      {
        label: "Setting Promo",
        route: "app.promo.list",
      }
    ],
  },

  {
    name: "Pelanggan Loyal",
    label: "Pelanggan Loyal",
    icon: "fa fa-bar-chart",
    childs: [
      {
        name: "Pelanggan Loyal",
        label: "Pelanggan Loyal",
        route: "app.loyal.pelanggan",
      },
      {
        label: "Setting Parameter",
        route: "app.loyal.setting",
      }
    ],
  },
  {
    name: "Ulasan",
    label: "Ulasan",
    icon: "fa fa-bar-chart",
    childs: [
      {
        label: "Ulasan",
        route: "app.ulasan.ulasan",
      },
      {
        label: "Setting Ulasan",
        route: "app.ulasan.setting",
      }
    ],
  },
  {
    name: "Data Pelanggan",
    label: "Data Pelanggan",
    icon: "fa fa-users",
    route: "app.pelanggan.list",
  },
  {
    name: "Laporan",
    icon: "fa fa-bar-chart",

    label: "Laporan Transaksi",
    route: "app.laporan.trx",
  },
  {
    name: "Data Admin",
    label: "Data Admin",
    icon: "fa fa-user",
    route: "app.users.list",
  },

];
const navPl = [
  {
    name: "Input Transaksi",
    label: "Input Transaksi",
    icon: "fa fa-list",
    route: "pelanggan.input-transaksi",
  },
  {
    name: "Daftar Pesanan",
    label: "Daftar Pesanan",
    icon: "fa fa-list",
    route: "pelanggan.pesanan",
  },
  {
    name: "Promo",
    label: "Promo",
    icon: "fa fa-list",
    route: "pelanggan.promo",
  },
  {
    name: "Pelanggan Loyal",
    label: "Pelanggan Loyal",
    icon: "fa fa-list",
    route: "pelanggan.loyal",
  },
  {
    name: "Ulasan",
    label: "Ulasan",
    icon: "fa fa-list",
    route: "pelanggan.ulasan",
  },
  {
    name: "History Transaksi",
    label: "History Transaksi",
    icon: "fa fa-list",
    route: "pelanggan.history-transaksi",
  },

];


export default {
  admin: navAdmin,
  pl: navPl,
};
