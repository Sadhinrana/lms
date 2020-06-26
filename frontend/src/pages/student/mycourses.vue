<template>
    <div class="container-fluid page__container">
        <!-- <ol class="breadcrumb">
              <li class="breadcrumb-item"><router-link :to="{ name: 'home'}">Home</router-link></li>
              <li class="breadcrumb-item active">My Courses</li>
          </ol> -->
        <div class="media mb-headings align-items-center">
            <div class="media-body">
                <h1 class="h2">My Courses</h1>
            </div>
            <div class="media-right">
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
                <router-link class="box-link"
                             :to="{
              name: 'take_course',
              params: { idcourse: course.course_id, idlesson: 1 }
            }">
                    <div class="card-header">
                        <div class="media">
                            <div class="media-left">
                                <router-link class="box-link"
                                             :to="{
                  name: 'take_course',
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
                                    <router-link class="box-link"
                                                 :to="{
                    name: 'take_course',
                    params: { idcourse: course.course_id, idlesson: 1 }
                  }"
                                    >{{ course.course_content.title }}
                                    </router-link
                                    >
                                </h4>
                                <small class="text-muted box-link">Exams: {{ course.quiz_completed_count }} of {{
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
              name: 'take_course',
              params: { idcourse: course.course_id, idlesson: 1 }
            }"
                                class="btn btn-primary btn-sm set_top box-link"
                        >
                            Continue
                            <i class="material-icons btn__icon--right box-link"
                            >play_circle_outline</i
                            ></router-link
                        >
                    </div>
                </router-link>
            </div>
        </div>

        <!-- Pagination -->
        <!-- <ul class="pagination justify-content-center pagination-sm">
           <li class="page-item disabled">
             <a class="page-link" href="#" aria-label="Previous">
               <span aria-hidden="true" class="material-icons">chevron_left</span>
               <span>Prev</span>
             </a>
           </li>
           <li class="page-item active">
             <a class="page-link" href="#" aria-label="1">
               <span>1</span>
             </a>
           </li>
           <li class="page-item">
             <a class="page-link" href="#" aria-label="1">
               <span>2</span>
             </a>
           </li>
           <li class="page-item">
             <a class="page-link" href="#" aria-label="Next">
               <span>Next</span>
               <span aria-hidden="true" class="material-icons">chevron_right</span>
             </a>
           </li>
         </ul>-->
        <!-- Pagination -->

        <ul class="pagination justify-content-center pagination-sm">
            <li v-if="pagination.current_page>1" class="page-item">
                <a @click.prevent="getQuizzes(pagination.current_page - 1)" class="page-link" href="#"
                   aria-label="Previous">
                    <span aria-hidden="true" class="material-icons">chevron_left</span>
                    <span>Prev</span>
                </a>
            </li>

            <li :key="page" v-for="page in pagesNumber"
                :class="[page == pagination.current_page ? 'active' : null , 'page-item']">
                <a href="#" v-on:click.prevent="getQuizzes(page)" class="page-link" aria-label="Previous">{{ page }}</a>
            </li>

            <li v-if="pagination.current_page < pagination.last_page">
                <a href="javascript:void(0)" aria-label="Next"
                   v-on:click.prevent="getQuizzes(pagination.current_page + 1)">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true" class="material-icons">chevron_right</span>
                </a>
            </li>
        </ul>
    </div>
</template>
<script>
    import {eventBus} from "@/main";

    export default {
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
        mounted() {
            this.fetchCourse();
            eventBus.$on("authUserFetched", e => {
                this.getQuizzes(this.pagination.current_page);
            });
            this.getQuizzes(this.pagination.current_page);
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
            fetchCourse() {
                this.$store.dispatch("enableLoading");
                var self = this;
                // Only run if authUser is fetched
                if (self.user_info.id) {
                    axios
                        .get(
                            this.routes.course.GET_COURSE_BY_STUDENT + "/" + self.user_info.id
                        ).then(response => {
                        self.list_course = response.data.data;
                        this.$store.dispatch("disableLoading");
                    }).catch(e => {
                        this.$store.dispatch("disableLoading");
                    });
                } else {
                    this.$store.dispatch("disableLoading");
                }

                //If this is the first time user visit page, wait until authUser is fetched
                eventBus.$on("authUserFetched", function () {
                    axios
                        .get(
                            this.routes.course.GET_COURSE_BY_STUDENT + "/" + self.user_info.id
                        ).then(response => {
                        self.list_course = response.data.data;
                    })
                });
            },
            getQuizzes(page) {
                var self = this;
                this.$store.dispatch("enableLoading");
                axios.get(this.routes.course.GET_COURSE_BY_STUDENT + "/" + self.user_info.id, {
                    params: {
                        page: page
                    }
                })
                    .then(response => {
                        self.list_course = response.data.data;
                        this.pagination.from = response.data.from;
                        this.pagination.to = response.data.to;
                        this.pagination.total = response.data.total;
                        this.pagination.per_page = response.data.per_page;
                        this.pagination.last_page = response.data.last_page;
                        this.pagination.current_page = response.data.current_page;
                        this.$store.dispatch("disableLoading");
                    }).catch(e => {
                    this.$store.dispatch("disableLoading");
                });
            },
        }
    };
</script>

<style>
    .set_top {
        margin-top: 15px !important;
    }

    .box-link:hover {
        text-decoration: none !important;
    }
</style>
