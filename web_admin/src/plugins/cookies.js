"use strict";

var Cookies = {
  get: key => {
    var name = key + "=";
    var ca = document.cookie.split(";");
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return null;
  },

  set: (key, value, expiry, withExpiredTime = false) => {
    var d = new Date();
    // d.setTime(d.getTime() + (expiry * 24 * 60 * 60 * 1000)); // hari expiry
    let expireSecond = expiry * 1000;
    d.setTime(d.getTime() + expireSecond); // expiry in second
    var expires = "expires=" + d.toUTCString();
    document.cookie = key + "=" + value + ";" + expires + ";path=/";

    if (withExpiredTime) {
      var expiredTime =
        d.getFullYear() +
        "-" +
        +(d.getMonth() + 1) +
        "-" +
        +d.getDate() +
        " " +
        +d.getHours() +
        ":" +
        +d.getMinutes() +
        ":" +
        +d.getSeconds();
      document.cookie =
        key + "-expired-at" + "=" + expiredTime + ";" + expires + ";path=/";
    }
  },

  clear: key => {
    if (Cookies.get(key)) {
      document.cookie =
        key + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
    }
    if (Cookies.get(key + "-expired-at")) {
      document.cookie =
        key +
        "-expired-at" +
        "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
    }
  }
};

module.exports = Cookies;
