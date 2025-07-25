
import user from "./user";
import pelanggan from "./pelanggan";
import menu from "./menu";
import transaksi from "./transaksi";
import laporan from "./laporan";
import promo from "./promo.js";
import loyal from "./loyal.js";
import ulasan from "./ulasan.js";

const route = [
  ...menu,
  ...transaksi,
  ...laporan,
  ...pelanggan,
  ...promo,
  ...loyal,
  ...ulasan,
  ...user,

];

export default route;
