<template>
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <b-overlay :show="loadingUserInfo" rounded="sm">
      <div class="menu_section">
        <ul class="nav side-menu" :class="isMitra ? 'nav-mitra' : ''">
          <template v-for="(menu, i) in menus" :key="i">
            <li v-if="cekMenu(menu)" :class="[{ active: menu.active }]">
              <a
                @click="onClickMenu(menu, 0)"
                class="pointer"
                v-if="menu.childs && menu.childs.length > 0"
              >
                <i :class="menu.icon"></i>
                {{ menu.label }}
                <span
                  class="fa fa-chevron-down"
                  v-if="menu.childs && menu.childs.length > 0"
                ></span>
                <span
                  class="badge bg-green badge-notif"
                  v-if="menu.notif"
                  v-text="menu.notif"
                >
                </span>
              </a>
              <router-link v-else :to="{ name: menu.route }">
                <i :class="menu.icon"></i>
                {{ menu.label }}

                <span
                  class="fa fa-chevron-down"
                  v-if="menu.childs && menu.childs.length > 0"
                ></span>
                <span
                  class="badge bg-green badge-notif"
                  v-if="menu.notif"
                  v-text="menu.notif"
                >
                </span>
              </router-link>

              <ul
                v-if="menu.childs && menu.childs.length > 0"
                class="nav child_menu"
                :style="menu.active ? 'display: block' : ''"
              >
                <li
                  v-for="(child, c) in menu.childs"
                  :key="c"
                  :class="[child.route == $route.name ? 'current-page' : '']"
                  v-show="cekMenu(child)"
                >
                  <router-link
                    v-if="!child.childs && cekMenu(child)"
                    :to="{ name: child.route, query: child.query }"
                  >
                    {{ child.label }}
                    <span
                      class="fa fa-chevron-down"
                      v-if="child.childs && child.childs.length > 0"
                    >
                    </span>
                    <span
                      class="badge bg-green badge-notif-2"
                      v-if="child.notif"
                      v-text="child.notif"
                    ></span>
                  </router-link>

                  <template v-else-if="cekMenu(child) && child.childs">
                    <a
                      href="javascript:;"
                      @click="child.active = !child.active"
                    >
                      {{ child.label }}
                      <span class="fa fa-chevron-down"></span>
                      <span
                        class="badge bg-green badge-notif"
                        v-if="child.notif"
                        v-text="child.notif"
                      >
                      </span>
                    </a>
                    <ul
                      class="nav child_menu"
                      :style="child.active ? 'display: block' : ''"
                    >
                      <li v-for="(child_child, cc) in child.childs" :key="cc">
                        <router-link
                          :to="{
                            name: child_child.route,
                            query: child_child.query,
                          }"
                          v-if="cekMenu(child_child)"
                        >
                          {{ child_child.label }}
                          <span
                            class="badge bg-green badge-notif-2"
                            v-if="child_child.notif"
                            v-text="child_child.notif"
                          ></span>
                        </router-link>
                      </li>
                    </ul>
                  </template>
                </li>
              </ul>
            </li>
          </template>
          <li>
            <a @click="logout" class="pointer">
              <i :class="'fa fa-lock'"></i>

              Keluar
            </a>
          </li>
        </ul>
      </div>
    </b-overlay>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import _nav from "./_nav";
export default {
  props: {
    msg: String,
  },

  data() {
    return {
      activeMenu: "dashboard",
      menus: [],
      isMitra: false,
      urlDoc: "",
      urlDocOld: "",
      notifInterval: null,
    };
  },
  computed: {
    ...mapGetters({
      user: "auth/user",
      notif: "auth/notif",
      loadingUserInfo: "auth/loadingUserInfo",
    }),
    isAdmin() {
      if (this.user && this.user.role) {
        if (this.user.role.indexOf("admin") > -1) {
          return true;
        }
      }
      return false;
    },
  },

  watch: {
    user() {
      this.initSidebar();
      this.getNotif();
    },
    notif() {
      this.initNotif();
    },

    "$route.path": function () {
      this.hideSidebarMobile();
    },
  },

  mounted() {
    this.initSidebar();
    this.getNotif();
    this.$store.dispatch("auth/getNotif");
  },

  created() {},
  unmounted() {
    clearInterval(this.notifInterval);
  },
  methods: {
    logout() {
      this.$router.push({ name: "app.login" });
      this.$store.dispatch("auth/logout");
    },
    hideSidebarMobile() {
      var classList = window.document.body.classList;
      if (classList.contains("nav-md")) {
        return;
      }
      this.menus.forEach((menu1) => {
        menu1.active = false;
        if (!menu1.childs) {
          return;
        }
        menu1.childs.forEach((menu2) => {
          menu2.active = false;
          if (!menu2.childs) {
            return;
          }

          menu2.childs.forEach((menu3) => {
            menu3.active = false;
          });
        });
      });
    },
    changeTitle() {
      let title =
        this.$route.meta && this.$route.meta.title
          ? this.$route.meta.title
          : "Dashboard";
      if (parseInt(this.notif.total) > 0) {
        window.document.title = this.notif.total + " Notif " + " | " + title;
      }
    },
    cekMenu(menu) {
      if (!menu.childs) {
        if (!menu.key) {
          return true;
        }
        return this.user.permissions.indexOf(menu.key) > -1;
      }

      let childs = [];

      for (let index = 0; index < menu.childs.length; index++) {
        const chd = menu.childs[index];
        if (chd.childs) {
          childs = [...chd.childs, ...childs];
        } else {
          childs.push(chd);
        }
      }

      const cekKeyAxist = childs
        .slice()
        .reverse()
        .find((r) => !r.key);
      if (cekKeyAxist) {
        return true;
      }

      const cekKey = childs
        .slice()
        .reverse()
        .find((r) => r.key && this.user.permissions.indexOf(r.key) > -1);

      return cekKey;
    },
    initNotif() {
      let notif = this.notif;
      this.changeTitle();
      if (!this.menus) {
        return;
      }

      for (let index = 0; index < this.menus.length; index++) {
        const menu = this.menus[index];
        if (notif[menu.notif_name]) {
          menu.notif = notif[menu.notif_name];
          continue;
        }
        if (!menu.childs) {
          continue;
        }

        let total1 = 0;
        for (let indexC = 0; indexC < menu.childs.length; indexC++) {
          const child = menu.childs[indexC];
          if (notif[child.notif_name]) {
            total1 += notif[child.notif_name];
            child.notif = notif[child.notif_name];
          }

          if (!child.childs) {
            continue;
          }

          let total2 = 0;
          for (let indexCC = 0; indexCC < child.childs.length; indexCC++) {
            const childC = child.childs[indexCC];
            if (notif[childC.notif_name]) {
              total1 += notif[childC.notif_name];
              total2 += notif[childC.notif_name];
              childC.notif = notif[childC.notif_name];
            }
          }
          child.notif = total2;
        }
        menu.notif = total1;
      }
    },

    getNotif() {
      if (this.notifInterval == null) {
        this.notifInterval = setInterval(() => {
          this.$store.dispatch("auth/getNotif");
        }, 60000); // 1 menit
      }
    },
    initSidebar() {
      this.menus = [];
      if (this.user) {
        this.menus = _nav.admin;
        this.menus = this.menus.map((menu) => {
          if (menu.childs) {
            var menu1 = menu.childs.map((menu2) => menu2.route);
            if (menu1.indexOf(this.$route.name) > -1) {
              menu.active = true;
            }
          }
          return menu;
        });
      }
    },

    async onClickMenu(menu, menuIndex, parentIndex) {
      if (menu.route) {
        await this.$router.push({ name: menu.route });
        // this.$router.re
        return;
      }

      if (menuIndex == 0) {
        let f = this.menus.findIndex((v) => v.name == menu.name);
        if (f > -1) {
          // toggle
          if (this.menus[f].active) {
            this.menus[f].active = 0;
            return;
          }

          this.menus = this.menus.map((v) => {
            v.active = 0;
            return v;
          });
          this.menus[f].active = 1;
        }
      } else if (parentIndex) {
        let f = this.menus[parentIndex].childs.findIndex(
          (v) => v.name == menu.name
        );

        // toggle
        if (this.menus[parentIndex].childs[f].active) {
          this.menus[parentIndex].childs[f].active = 0;
          return;
        }

        this.menus[parentIndex].childs = this.menus[parentIndex].childs.map(
          (v) => {
            v.active = 0;
            return v;
          }
        );
        this.menus[parentIndex].childs[f].active = 1;
      }
    },
  },
};
</script>
<style scoped>
.badge-notif {
  position: absolute;
  left: 3px;
  top: 0px;
}
.badge-notif-2 {
  position: absolute;
  top: 0px;
}
</style>
