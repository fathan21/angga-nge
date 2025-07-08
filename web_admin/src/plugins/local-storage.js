"use strict";

var localStorage = {
  get: key => {
    return window.localStorage.getItem(key);
  },

  set: (key, value, expiry, withExpiredTime = false) => {
    var d = new Date();
    // d.setTime(d.getTime() + (expiry * 24 * 60 * 60 * 1000)); // hari expiry
    let expireSecond = expiry * 1000;
    d.setTime(d.getTime() + expireSecond); // expiry in second
    // var expires = "expires=" + d.toUTCString();
    window.localStorage.setItem(key, value);

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
      window.localStorage.setItem(key + "-expired-at", expiredTime);
    }
  },

  clear: key => {
    if (localStorage.get(key)) {
      window.localStorage.removeItem(key);
    }
    if (localStorage.get(key + "-expired-at")) {
      window.localStorage.removeItem(key + "-expired-at");
    }
  }
};

module.exports = localStorage;
