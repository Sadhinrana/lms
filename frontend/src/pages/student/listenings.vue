<template>
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__container">
            <!-- <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'home'}">Home</router-link>
              </li>
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'student_browse_courses'}">
                  Courses
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{course_content.title}}</li>
            </ol> -->
            <h1 class="h2">{{course_content.title}} Listening</h1>
            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <label id="listening_id">Let’s Listen</label>
                        <div class="card-body" style="padding-top: 0px !important;">

                            <div v-for="audio in audio_files">
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
                <div class="col-md-2">
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import {QuillDeltaToHtmlConverter} from "quill-delta-to-html";

    export default {
        data() {
            return {
                course_content: {
                    title: "",
                    quiz: {}
                },
                lesson_list: [],
                infolesson: [],
                instructor: [],
                checkblockquiz: [],
                audio_files: []
            };
        },

        mounted() {
            this.checkQuizBlock();
            this.fetchCourse();
            this.getCourseAudio()
        },
        watch: {
            "$route.params.idlesson": function () {
                this.fetchCourse();
            }
        },
        methods: {
            checkQuizBlock() {
                axios
                    .post(this.routes.quiz.GET_QUIZ_FOR_STUDENT, {course_id: this.$route.params.idcourse})
                    .then(response => {
                        this.checkblockquiz = response.data;
                    });
            },
            fetchCourse() {
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.course.GET_BY_ID + "/" + this.$route.params.idcourse)
                    .then(response => {
                        this.$store.dispatch("disableLoading");
                        this.course_content = response.data;

                        var converter = new QuillDeltaToHtmlConverter(
                            JSON.parse(this.course_content.description.toString()).ops,
                            {}
                        );
                        this.course_content.description = converter.convert();
                        this.fetchInstructor(response.data.instructor_id);
                    }).catch(err => {
                    this.$store.dispatch("disableLoading");
                });
            },
            fetchLesson() {
                this.$store.dispatch("enableLoading");
                axios
                    .get(
                        this.routes.lesson.LIST_LESSON_BY_COURSEID +
                        "/" +
                        this.$route.params.idcourse
                    )
                    .then(response => {
                        this.lesson_list = response.data;
                        this.$store.dispatch("disableLoading");
                    }).catch(err => {
                    this.$store.dispatch("disableLoading");
                });
            },
            fetchOneLesson() {
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.lesson.GET_BY_ID + "/" + this.$route.params.idlesson)
                    .then(response => {
                        this.infolesson = response.data;
                        this.$store.dispatch("disableLoading");
                    }).catch(err => {
                    this.$store.dispatch("disableLoading");
                });
            },
            fetchInstructor(id) {
                this.$store.dispatch("enableLoading");
                axios.get(this.routes.user.GET_INSTRUCTOR + "/" + id)
                    .then(response => {
                        this.instructor = response.data;
                        this.$store.dispatch("disableLoading");
                    }).catch(err => {
                    this.$store.dispatch("disableLoading");
                });
            },

            getCourseAudio() {
                axios
                    .post(this.routes.course.GET_COURSE_AUDIO, {course_id: this.$route.params.idcourse})
                    .then(res => {
                        this.audio_files = res.data;
                    });
            },
        }
    };
</script>

<style>
    #listening_id {
        font-size: large;
        background-color: #2196f3;
        color: white;
        text-align: center;
    }

    #file_name {
        width: 100%;
        color: white;
        background-color: orange;
        border-radius: 5px;
    }

</style>
