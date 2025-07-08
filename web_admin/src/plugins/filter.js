import formatDate from "date-fns/format";
import parseISO from "date-fns/parseISO";

var filters = {
  date: (date) => {
    if (!date) {
      return "";
    }
    return formatDate(
      parseISO(date),
      // "dd/MM/yyyy"
      "dd MMM yy"
    );
  },

  dateTime: (date) => {
    if (!date) {
      return "";
    }
    return formatDate(
      parseISO(date),
      "dd MMM yy HH:mm:ss"
    );
  },

  currency: (angka) => {
    if (!angka) {
      return "0";
    } else {
      angka = Math.ceil(angka);
      return new Intl.NumberFormat("locale", {
        // maximumSignificantDigits: 3,
        // style: currency
        // currency: "IDR",
        // minimumFractionDigits: decimal,
      }).format(angka);
    }
  },

  isNumber: (num)=>{
    if(isNaN(num)) {
      return 0
    } else {
      return parseFloat(num);
    }
  }

};

export default filters;
