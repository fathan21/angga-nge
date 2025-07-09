<template>
    <div class="top_nav">
        <div class="nav_menu">
            <div class="nav toggle" @click="toggleMenu">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <nav class="nav navbar-nav">
                <ul class="navbar-right">
                    <li
                        class="nav-item dropdown open"
                        style="padding-left: 15px"
                    >
                        <a
                            href="javascript:;"
                            class="user-profile dropdown-toggle"
                            aria-haspopup="true"
                            id="navbarDropdown"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <img src="/images/img-user.png" alt="" />
                                {{ this.user ? this.user.username : "" }}
                        </a>
                        <div
                            class="dropdown-menu dropdown-usermenu pull-right"
                            aria-labelledby="navbarDropdown"
                        >
                            <a class="dropdown-item" href="javascript:;" @click="$router.push({name:'app.profile'})">
                                Profile</a
                            >
                            <a class="dropdown-item" href="javascript:;" @click="$router.push({name:'app.change-password'})">
                                <!-- <span class="badge bg-red pull-right">50%</span> -->
                                <span>Ganti Password</span>
                            </a>
                            <a
                                class="dropdown-item"
                                href="javascript:;"
                                @click="logout"
                                ><i class="fa fa-sign-out pull-right"></i> Keluar</a
                            >
                        </div>
                    </li>

                    <!-- <li role="presentation" class="nav-item dropdown open">
                        <a
                            href="javascript:;"
                            class="dropdown-toggle info-number"
                            id="navbarDropdown1"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge bg-green">6</span>
                        </a>
                        <ul
                            class="dropdown-menu list-unstyled msg_list"
                            role="menu"
                            aria-labelledby="navbarDropdown1"
                        >
                            <li class="nav-item">
                                <a class="dropdown-item">
                                    <span class="image"
                                        ><img
                                            src="images/img.jpg"
                                            alt="Profile Image"
                                    /></span>
                                    <span>
                                        <span>John Smith</span>
                                        <span class="time">3 mins ago</span>
                                    </span>
                                    <span class="message">
                                        Film festivals used to be do-or-die
                                        moments for movie makers. They were
                                        where...
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="dropdown-item">
                                    <span class="image"
                                        ><img
                                            src="images/img.jpg"
                                            alt="Profile Image"
                                    /></span>
                                    <span>
                                        <span>John Smith</span>
                                        <span class="time">3 mins ago</span>
                                    </span>
                                    <span class="message">
                                        Film festivals used to be do-or-die
                                        moments for movie makers. They were
                                        where...
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="dropdown-item">
                                    <span class="image"
                                        ><img
                                            src="images/img.jpg"
                                            alt="Profile Image"
                                    /></span>
                                    <span>
                                        <span>John Smith</span>
                                        <span class="time">3 mins ago</span>
                                    </span>
                                    <span class="message">
                                        Film festivals used to be do-or-die
                                        moments for movie makers. They were
                                        where...
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="dropdown-item">
                                    <span class="image"
                                        ><img
                                            src="images/img.jpg"
                                            alt="Profile Image"
                                    /></span>
                                    <span>
                                        <span>John Smith</span>
                                        <span class="time">3 mins ago</span>
                                    </span>
                                    <span class="message">
                                        Film festivals used to be do-or-die
                                        moments for movie makers. They were
                                        where...
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <div class="text-center">
                                    <a class="dropdown-item">
                                        <strong>See All Alerts</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li> -->
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    props: {
        msg: String,
    },

    methods: {
        toggleMenu() {
            let nvMD = document.body.classList.contains("nav-md");
            if (nvMD) {
                document.body.classList.remove("nav-md");
                document.body.classList.add("nav-sm");
            } else {
                document.body.classList.remove("nav-sm");
                document.body.classList.add("nav-md");
            }
        },

        logout() {
            this.$router.push({ name: "app.login" });
            this.$store.dispatch("auth/logout");
        },
    },
    computed: {
        ...mapGetters({
            user: "auth/user",
        }),
    },
};
</script>
