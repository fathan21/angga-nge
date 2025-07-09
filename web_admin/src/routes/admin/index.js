
import user from "./user";
import pelanggan from "./pelanggan";
import menu from "./menu";
import transaksi from "./transaksi";
import laporan from "./laporan";
import promo from "./promo.js";

const route = [
  ...menu,
  ...transaksi,
  ...laporan,
  ...pelanggan,
  ...promo,
  ...user,

];

export default route;
