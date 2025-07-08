
import user from "./user";
import xmppAccount from "./xmpp-account";
import xmppAccountReg from "./xmpp-account-reg";
import gn from "./general-settings";

const route = [
  ...user,
  ...xmppAccount,
  ...xmppAccountReg,
  gn,

];

export default route;
