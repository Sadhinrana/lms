<template>
    <div class="container-fluid page__container">
        <div class="media mb-headings align-items-center">
            <div class="media-body">
                <h1 class="h2">Listening</h1>
            </div>
            <div class="media-right" style="text-align:center;">
                <div class="btn-group btn-group-sm">
                    <a href="#" class="btn btn-white active"
                    ><i class="material-icons">list</i></a
                    >
                    <a href="#" class="btn btn-white"
                    ><i class="material-icons">dashboard</i></a
                    >
                </div>
            </div>
        </div>

        <div class="card-columns">
            <div class="card" v-for="course in list_course">
                <div class="card-header">
                    <div class="media">
                        <div class="media-left">
                            <router-link
                                    :to="{
                  name: 'take_listenings',
                  params: { idcourse: course.course_id, idlesson: 1 }
                }"
                            >
                                <img
                                        :src="routes.server_api+ '/'+course.course_content.course_image"
                                        alt="Card image cap"
                                        :width="100"
                                        class="rounded"
                                />
                            </router-link>
                        </div>
                        <div class="media-body">
                            <h4 class="card-title m-0">
                                <router-link
                                        :to="{
                    name: 'take_listenings',
                    params: { idcourse: course.course_id, idlesson: 1 }
                  }"
                                >{{ course.course_content.title }}
                                </router-link
                                >
                            </h4>
                            <small class="text-muted">Exams: {{ course.quiz_completed_count }} of {{
                                course.total_quizzes_count }}</small>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0">
                    <div
                            class="progress-bar progress-bar-striped bg-primary"
                            role="progressbar"
                            :style="{ 'width' : course.quiz_completed_percentage + '%' }"
                            :aria-valuenow="course.quiz_completed_percentage"
                            aria-valuemin="0"
                            aria-valuemax="100"
                    ></div>
                </div>
                <div class="card-footer bg-white">
                    <router-link
                            :to="{
              name: 'take_listenings',
              params: { idcourse: course.course_id, idlesson: 1 }
            }"
                            class="btn btn-primary btn-sm set_top"
                    >
                        Continue
                        <i class="material-icons btn__icon--right"
                        >play_circle_outline</i
                        ></router-link
                    >
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <ul class="pagination justify-content-center pagination-sm">
            <li v-if="pagination.current_page>1" class="page-item">
                <a @click.prevent="fetchCourse(pagination.current_page - 1)" class="page-link" href="#"
                   aria-label="Previous">
                    <span aria-hidden="true" class="material-icons">chevron_left</span>
                    <span>Prev</span>
                </a>
            </li>

            <li :key="page" v-for="page in pagesNumber"
                :class="[page == pagination.current_page ? 'active' : null , 'page-item']">
                <a href="#" v-on:click.prevent="fetchCourse(page)" class="page-link" aria-label="Previous">{{ page
                    }}</a>
            </li>

            <li v-if="pagination.current_page < pagination.last_page">
                <a href="javascript:void(0)" aria-label="Next"
                   v-on:click.prevent="fetchCourse(pagination.current_page + 1)">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true" class="material-icons">chevron_right</span>
                </a>
            </li>
        </ul>
        <!-- Pagination -->
    </div>
</template>

<script>
    import {eventBus} from "@/main";

    export default {
        mounted() {
            this.fetchCourse(this.pagination.current_page);
        },
        data() {
            return {
                list_course: [],
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
            };
        },
        computed: {
            user_info() {
                return this.$store.getters.authUser;
            },
            pagesNumber() {
                if (!this.pagination.to) {
                    return [];
                }
                let from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                let pagesArray = [];
                for (let page = 1; page <= this.pagination.last_page; page++) {
                    pagesArray.push(page);
                }
                return pagesArray;
            },
        },
        methods: {
            fetchCourse(page) {
                this.$store.dispatch("enableLoading");
                var self = this;
                let data = {};

                // Only run if authUser is fetched
                if (self.user_info.id) {
                    if (self.user_info.role == 'head_teacher') {
                        data = {
                            page: page,
                            company_id: self.user_info.company_id,
                        };
                    } else {
                        data = {
                            page: page,
                        };
                    }

                    axios
                        .get(
                            this.routes.course.GET_COURSE_BY_INSTRUCTOR + "/" + self.user_info.id, {
                                params: data
                            }).then(response => {
                        self.list_course = response.data.data;
                        self.pagination.from = response.data.from;
                        self.pagination.to = response.data.to;
                        self.pagination.total = response.data.total;
                        self.pagination.per_page = response.data.per_page;
                        self.pagination.last_page = response.data.last_page;
                        self.pagination.current_page = response.data.current_page;
                        self.$store.dispatch("disableLoading");
                    }).catch(e => {
                        this.$store.dispatch("disableLoading");
                    });
                } else {
                    this.$store.dispatch("disableLoading");
                }

                //If this is the first time user visit page, wait until authUser is fetched
                eventBus.$on("authUserFetched", function () {
                    if (self.user_info.role == 'head_teacher') {
                        data = {
                            page: page,
                            company_id: self.user_info.company_id,
                        };
                    } else {
                        data = {
                            page: page,
                        };
                    }

                    axios
                        .get(
                            this.routes.course.GET_COURSE_BY_INSTRUCTOR + "/" + self.user_info.id, {
                                params: data
                            }).then(response => {
                        self.list_course = response.data.data;
                        self.pagination.from = response.data.from;
                        self.pagination.to = response.data.to;
                        self.pagination.total = response.data.total;
                        self.pagination.per_page = response.data.per_page;
                        self.pagination.last_page = response.data.last_page;
                        self.pagination.current_page = response.data.current_page;
                    })
                });
            },
        }
    };
</script>

<style lang="scss">
    .media img {
        max-width: 100%;
        height: auto;
        display: block;
    }
</style>
