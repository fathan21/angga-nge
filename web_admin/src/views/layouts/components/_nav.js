const navAdmin = [
  {
    name: "Data Admin",
    label: "Dashboard",
    icon: "fa fa-home",
    route: "app.dashboard",
  },
  {
    name: "Data Pelanggan",
    label: "Data Pelanggan",
    icon: "fa fa-users",
    route: "app.pelanggan.list",
  },
  {
    name: "Input Transaksi",
    label: "Input Transaksi",
    icon: "fa fa-list",
    route: "app.transaksi.list",
  },
  // {
  //   name: "Rating",
  //   label: "Rating",
  //   icon: "fa fa-star",
  //   route: "app.transaksi.rating",
  // },
  {
    name: "Data Menu",
    label: "Data Menu",
    icon: "fa fa-cubes",
    route: "app.menu.list",
  },
  {
    name:"Laporan",
    label: "Laporan",
    icon: "fa fa-bar-chart",
    childs: [
      {
        label: "Laporan Transaksi",
        route: "app.laporan.trx",
      },
      {
        label: "Laporan Pelanggan Loyal",
        route: "app.laporan.pelanggan",
      },
      {
        label: "Laporan Rating",
        route: "app.laporan.rating",
      }
    ],
  },
  {
    name: "Promo & Poin",
    label: "Promo & Poin",
    icon: "fa fa-percent",
    route: "app.promo.list",
  },
  {
    name: "Data Admin",
    label: "Data Admin",
    icon: "fa fa-user",
    route: "app.users.list",
  },
  
];


export default {
  admin: navAdmin,
};
