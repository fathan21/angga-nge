<template>
  <div>
    <b-overlay :show="$store.state.loading" rounded="sm" >
      <router-view></router-view>
    </b-overlay>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { useAbility } from "@casl/vue";
import { ABILITY_TOKEN } from "@casl/vue";

import { AbilityBuilder, Ability } from "@casl/ability";
export default {
  name: "App",

  inject: {
    $ability: { from: ABILITY_TOKEN },
  },
  setup() {
    const { can } = useAbility();
    return {
      // other props
      can,
    };
  },
  computed: {
    ...mapGetters({
      loggedIn: "auth/loggedIn",
    }),
  },
  data() {
    return {
      title: "",
    };
  },
  watch: {
    "$route.path": function () {
      this.title = this.$route.meta.title || this.$route.name;
    },
    user: {
      deep: true,
      handler: function () {
        // handler access right
        if (this.user && this.user.username) {
          const { can, rules } = new AbilityBuilder(Ability);
          // can("manage-data33");
          if (this.user && this.user.permissions) {
            for (let index = 0; index < this.user.permissions.length; index++) {
              const canAct = this.user.permissions[index];
              can(canAct);
            }
          }

          this.$ability.update(rules);
        }
      },
    },
  },
  methods: {
    notif(msg, options = {}) {
      this.$toast.show(msg, {
        type: options.type || "info",
        position: options.position || "top-right",
      });
    },
    setPageTitle(text) {
      this.title = text;
      document.title = text;
    },
    consoleColor(text, color = "red", fontSize = 50) {
      // 1. Pass the css styles in an array
      const styles = [
        "color: " + color,
        "font-size: " + fontSize + "px",
      ].join(";");
      console.log('%c' + text, styles);
    },
  },
  mounted() {
    this.consoleColor("Stop!");
    this.consoleColor(`Ini adalah fitur browser yang ditujukan untuk pengembang. Jika seseorang menyuruh Anda untuk menyalin dan menempelkan sesuatu di sini untuk mengaktifkan fitur atau "hack" akun seseorang, itu adalah penipuan dan akan memberi mereka akses ke akun Anda.`, 'grey', '18');
  },
};
</script>

<style>

</style>
