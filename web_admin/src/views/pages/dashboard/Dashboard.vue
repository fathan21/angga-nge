<template>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            
            &nbsp;
            <!-- <b-overlay :show="loading" rounded="sm">
                <div class="x_content">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col">
                                <div v-for="(news, i) in data" :key="i">
                                    <div class="media d-flex">
                                        <template v-if="i > 0">
                                            <img
                                                class="me-3"
                                                :src="news.image"
                                                :alt="news.title"
                                                v-if="news.image"
                                                width="100"
                                            />
                                        </template>
                                        <div class="media-body" v-if="i > 0">
                                            <h5 class="mt-0">
                                                {{ news.title }}
                                            </h5>

                                            <div
                                                class="
                                                    text-left
                                                    font-italic
                                                    text-black-50
                                                    mb-1
                                                    text-sm
                                                "
                                            >
                                                {{
                                                    $filters.dateTime(
                                                        news.published_at
                                                    )
                                                }}
                                            </div>
                                            <div>
                                                <span>
                                                    <span
                                                        v-html="
                                                            news.content_split
                                                        "
                                                    ></span>
                                                    <span>...</span>
                                                </span>
                                                <a
                                                    class="btn btn-link btn-sm"
                                                    @click="
                                                        $router.push({
                                                            name: 'app.news.publish',
                                                            params: {
                                                                id: news.id,
                                                            },
                                                        })
                                                    "
                                                >
                                                    Baca Selengkapnya
                                                </a>
                                            </div>
                                        </div>
                                        <div class="media-body" v-else>
                                            <h3 class="mt-0">
                                                {{ news.title }}
                                            </h3>
                                            <div
                                                class="
                                                    text-left text-black-50
                                                    mb-1
                                                    text-sm
                                                    font-italic
                                                "
                                            >
                                                {{
                                                    $filters.dateTime(
                                                        news.published_at
                                                    )
                                                }}
                                            </div>
                                            <div class="text-center">
                                                <img
                                                    class="mb-3"
                                                    :src="news.image"
                                                    alt="Generic placeholder image"
                                                    v-if="news.image"
                                                    style="max-width: 100%"
                                                />
                                            </div>
                                            <div v-html="news.content"></div>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                                <div class="row" v-if="data.length <= 0">
                                    <div class="col">No News Update</div>
                                </div>
                            </div>
                        </div>
          <paging-base v-model="options.current_page" :options="options" v-if="data.length > 0" />
                    </div>
                </div>
            </b-overlay> -->
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data: [],
            options: {
                current_page: 1,
                total_row: 0,
                per_page: 1,
            },
            search: {
                q: "",
            },
            loading: false,
        };
    },
    mounted() {
        
    },
    computed: {
        moreParams() {
            return {
                page: this.options.current_page,
                q: this.search.q,
                // per_page: this.options.per_page
            };
        },
    },
    watch: {
        "options.current_page": {
            handler: function () {
                this.loadData();
            },
        },
    },
    methods: {
        loadData() {
            this.loading = true;
            this.$axios
                .get("news/publish", { params: this.moreParams })
                .then((res) => {
                    this.loading = false;
                    this.data = res.data.data;
                    this.options.total_row = res.data.meta.total;
                    this.options.per_page = res.data.meta.per_page;
                })
                .catch((res) => {
                    this.$root.notif(res.message, {
                        type: "error",
                        position: "top",
                    });
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>

