"use strict";
import CryptoJS from "crypto-js";
// import axios from "axios";
// import format from "date-fns/format";

const keyEnv = process.env.VUE_APP_KEY; // change to your key
// const apiKey = "123xxxyyyzzz"; // change to your api key
// const username = "chhumsina";
// const password = "chhumsina@123";
// const reqTime = format(new Date(), "yyyyMMddHHmmss");

// const encPassword = this.aesEncrypt(password)
// const hash = apiKey + reqTime + username + encPassword
// const encHash = this.aesEncrypt(hash)

// const data = {
//   req_time: reqTime,
//   username,
//   password: encPassword,
//   hash: encHash
// }

// const headers = {
//   'api-key': apiKey
// }

async function decrypt(encryptStr) {
  encryptStr = CryptoJS.enc.Base64.parse(encryptStr);
  let encryptData = encryptStr.toString(CryptoJS.enc.Utf8);
  encryptData = JSON.parse(encryptData);
  let iv = CryptoJS.enc.Base64.parse(encryptData.iv);
  var decrypted = CryptoJS.AES.decrypt(
    encryptData.value,
    CryptoJS.enc.Utf8.parse(keyEnv),
    {
      iv: iv,
      mode: CryptoJS.mode.CBC,
      padding: CryptoJS.pad.Pkcs7,
    }
  );
  decrypted = CryptoJS.enc.Utf8.stringify(decrypted);
  return decrypted;
} // decrypt

async function encrypt(data) {
  let iv = CryptoJS.lib.WordArray.random(16),
    key = CryptoJS.enc.Utf8.parse(keyEnv);
  let options = {
    iv: iv,
    mode: CryptoJS.mode.CBC,
    padding: CryptoJS.pad.Pkcs7,
  };
  let encrypted = CryptoJS.AES.encrypt(data, key, options);
  encrypted = encrypted.toString();
  iv = CryptoJS.enc.Base64.stringify(iv);
  let result = {
    iv: iv,
    value: encrypted,
    mac: CryptoJS.HmacSHA256(iv + encrypted, key).toString(),
  };
  result = JSON.stringify(result);
  result = CryptoJS.enc.Utf8.parse(result);
  return CryptoJS.enc.Base64.stringify(result);
}

const Sign = {
  encrypt: encrypt,

  decrypt: decrypt,
};

export default Sign;
