const navAdmin = [
  {
    label: "Dashboard",
    icon: "fa fa-home",
    route: "app",
  },
  {
    label: "Registrasi R1 Jabber",
    name: "Registrasi R1 Jabber",
    icon: "fa fa-user",
    route: "app.xmpp-account-reg.list",
    notif_name: "account_xmpp_reg",
  },
  {
    label: "Akun R1 Jabber",
    name: "Akun R1 Jabber",
    icon: "fa fa-users",
    route: "app.xmpp-account.list",
  },
  {
    label: "Admin",
    name: "admin",
    icon: "fa fa-lock",
    route: "app.users.list",
  },
  {
    label: "General Setting",
    name: "general setting",
    icon: "fa fa-gear",
    route: "app.general-settings",
  },
  // {
  //   name: "Settings",
  //   label: "Setting",
  //   icon: "fa fa-gear",
  //   childs: [
  //     {
  //       key:'settings admin',
  //       label: "Admin",
  //       name: "admin",
  //       route: "app.users.list",
  //     }
  //   ],
  // },
  
];


export default {
  admin: navAdmin,
};
