<template>
    <div class="container body" style="min-height: 100vh">
        <div  v-if="user && user.username" class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0">
                        <!-- <a
                            href="javascript:;"
                            @click="$router.push({ name: 'app' })"
                            class="site_title"
                            >
                            <img src="Logo_Zonerone.png" style="width: 50px;" />
                            <span class="ps-1"> {{ $appName }}
                            </span>
                            </a
                        > -->
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img
                                src="/Logo_Zonerone.png"
                                class="img-circle profile_img"
                            />
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2>
                                {{ this.user ? this.user.username : "" }}
                            </h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <SideBar />
                    <!-- /sidebar menu -->
                </div>
            </div>

            <!-- top navigation -->
            <AdminTopNav />
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title">
                            <h3 id="title-page">
                                {{ $root.title }}
                                <!-- Tables -->
                                <!-- <small>Some examples to get you started</small> -->
                            </h3>
                            {{ 

                             }}
                            <div class="well" v-if="keterangan != '' || catatan != ''">
                                <div class="d-flex  gap-2">
                                    <strong v-if="keterangan != ''">Keterangan : </strong>
                                    <text v-if="keterangan != ''">{{ keterangan }}</text>
                                </div>
                                <div class="d-flex gap-2">
                                    <strong v-if="catatan != ''" class="red">Catatan Penting : </strong>
                                    <text v-if="catatan != ''" class="red">
                                        <span v-html="catatan"></span>
                                    </text>
                                </div>
                            </div>
                        </div>
                        
                        <div class="title_right">
                            
                            
                        </div>
                       
                    </div>
                     
                    <div class="clearfix"></div>

                    <div
                        class="row"
                    >
                        <router-view></router-view>
                    </div>
                </div>
            </div>
            <!-- /page content -->
            
            <!-- footer content -->
            <footer>
                <div class="pull-right">
                    {{ $appName }} @ {{  $version  }}
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
        <div v-else >
           <div class="text-center">
            <span class="loader-admin"></span>
           </div>
        </div>
    </div>
</template>

<script>
import SideBar from "./components/AdminSideBar.vue";
import AdminTopNav from "./components/AdminTopNav.vue";
import { mapGetters } from "vuex";

export default {
    props: {
        msg: String,
    },

    components: {
        SideBar,
        AdminTopNav,
    },

    computed: {
        ...mapGetters({
            user: "auth/user",
            loggedIn: "auth/loggedIn",
        }),
        titleRoot() {
            return this.$root.title;
        }
    },

    data() {
        return {
            title: "Dashboard",
            keterangan: '',
            catatan: '',

        };
    },

    watch: {
        "$route.path": function () {
            // let title = this.$route.meta.title || this.$route.name;
            this.keterangan = this.$route.meta.keterangan || '';
            this.catatan = this.$route.meta.catatan || '';
        },
        user: function () {
            if (this.user && this.user.role) {
                this.$store.dispatch("auth/getNotif");
            }
        },
    },

    mounted() {
        this.title = this.$route.meta.title || this.$route.name;
        this.keterangan = this.$route.meta.keterangan || '';
        this.catatan = this.$route.meta.catatan || '';
        this.initSidebar();
    },

    methods: {
        initSidebar() {
            if (this.loggedIn) {
                this.$store.dispatch("auth/getUserInfo");
            }
        },
    },
};
</script>

<style>

.loader-admin {
    width: 48px;
    height: 48px;
    border: 5px solid #FFF;
    border-bottom-color: #FF3D00;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
    }

    @keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
    } 
</style>