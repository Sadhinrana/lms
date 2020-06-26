<template>
    <div class="container-fluid page__container">
        <div
                class="alert alert-dismissible bg-success text-white border-0 fade"
                :class="added_success?'show':''"
                role="alert"
        >
            <strong>{{ success_text }}</strong>
        </div>
        <div
                v-if="added_error"
                class="alert alert-dismissible bg-danger text-white border-0 fade"
                :class="added_error?'show':''"
                v-for="error in added_error_messages.errors"
                role="alert"
        >
            <strong>Error ! </strong> {{error[0]}}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Take Listening</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 py-2" :key="course.id" v-for="course in get_course"
                                 @click="getCourseAudio(course.id)">
                                <button class="btn btn-success" style="width:100% !important">
                                    <div class="rotate">
                                        <i style="font-size: 40px;"
                                           class="sidebar-menu-icon sidebar-menu-icon--left material-icons">school</i>
                                    </div>
                                    <h6 class="text-uppercase">{{course.title}}</h6>
                                </button>
                            </div>
                        </div>

                        <div class="card" v-if="audio_files.length">
                            <div class="card-body" style="padding-top: 0px !important;">
                                <div v-for="audio in audio_files" :key="audio.id">
                                    <div class="row" style="padding-top: 10px !important;">
                                        <label id="file_name"><i class="material-icons">arrow_downward</i>{{audio.file_name}}</label>
                                        <audio controls controlsList="nodownload" style="width:100%;">
                                            <source
                                                    :src="routes.server_api + audio.file_url"
                                                    type="audio/mpeg"
                                            >
                                            Your browser does not support the audio tag.
                                        </audio>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getCourseAudio();
            this.fetchCourse();
        },
        computed: {
            user_info() {
                return this.$store.getters.authUser;
            }
        },

        data() {
            return {
                quill: null,
                added_success: false,
                added_error: false,
                added_error_messages: [],
                course: {
                    title: "",
                    description: "",
                    video_link: "",
                    cbc_cate_id: null,
                    duration: "",
                    is_published: false,
                    grade_to_pass: 5,
                    course_image: "",
                    lesson: [],
                    course_level: ""
                },
                course_audio: null,
                lesson: [],
                categories: [],
                get_course: [],
                success_text: "",
                audio_files: []
            };
        },

        methods: {
            fetchCourse() {
                this.$store.dispatch("enableLoading");
                var self = this;
                // Only run if authUser is fetched
                if (self.user_info.id) {
                    axios
                        .get(
                            this.routes.course.GET_COURSES_BY_COMPANY_ID
                        ).then(response => {
                        self.get_course = response.data;
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
                            this.routes.course.GET_COURSES_BY_COMPANY_ID
                        ).then(response => {
                        self.get_course = response.data;
                        console.log(self.get_course)
                        self.$store.dispatch("disableLoading");
                    })
                });
            },
            getCourseAudio(course_id) {
                this.$store.dispatch("enableLoading");
                axios
                    .post(this.routes.course.GET_COURSE_AUDIO, {course_id: course_id})
                    .then(res => {
                        this.audio_files = res.data;
                        this.$store.dispatch("disableLoading");
                    });
            }
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
