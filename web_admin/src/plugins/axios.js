"use strict";

import axios from "axios";
import Cookies from "./cookies";
import Sign from "./encryption";

axios.defaults.headers.common["Content-Type"] = "application/json";
axios.defaults.headers.common["Accept"] = "application/json";

let token = Cookies.get("access_token");
if (token) {
  axios.defaults.headers.common["Authorization"] = "Bearer " + token;
}

let config = {
  baseURL: process.env.VUE_APP_API_URL,
};

// let keySecret = process.env.VUE_APP_ENCRYPTION_KEY;

const _axios = axios.create(config);
_axios.interceptors.request.use(
  async function (config) {
    // Do something before request is sent
    return config;
  },
  function (error) {
    // Do something with request error
    return Promise.reject(error);
  }
);

// Add a response interceptor
_axios.interceptors.response.use(
  async function (response) {
    if (response && response.data && response.data.payload) {
      const encryptText = response.data.payload;
      let decryptText = await Sign.decrypt(encryptText);
      response.data.data = JSON.parse(decryptText);
      return response.data;
    }

    // Do something with response data
    if (response && response.data) {
      return response.data;
    }
    return response;
  },
  async function (error) {
    // Do something with response error
    if (error && error.response && error.response.data) {
      if (error.response.status == 401) {
        Cookies.clear("access_token");
        window.location.href = "/";
      }
      return Promise.reject(error.response.data);
    }
    return Promise.reject(error);
  }
);

export default _axios;
