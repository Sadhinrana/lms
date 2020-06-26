<template>
    <div class="mdk-drawer-layout__content page">
        <div class="container-fluid page__container">
            <h1 class="h2">{{course_content.title}}</h1>
            <div class="row">
                <div class="col-md-12">
                    <ul class="card list-group list-group-fit">
                        <li class="list-group-item" v-for="(quiz, index) in checkblockquiz" :key="index">
                            <div class="media">
                                <div class="media-left">
                                    <div class="text-muted">{{index + 1}}.</div>
                                </div>
                                <div class="media-body">
                                    <span v-if="quiz.is_block !==1">
                                        <router-link :to="{ name: 'take_quiz', params:{quiz_id:quiz.quiz_id}}">{{quiz.quiz_name}}</router-link>
                                    </span>
                                    <span v-else>
                                        <a>{{quiz.quiz_name}}</a>
                                    </span>
                                </div>

                                <div class="media-body" v-if="quiz.time != undefined">
                                    <div class="col" style="padding-right: 0rem !important; padding-left: 0rem !important;">
                                        <div style="text-align: center;">
                                            <a class="btn btn-secondary"
                                               style="color: #fff; font-size: 17px; padding: 7px;">
                                                {{ quiz.time.hours }}:{{ quiz.time.minutes }}:{{ quiz.time.seconds }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="media-right" v-if="quiz.is_block !==1">
                                    <i class="material-icons text-muted-light">lock_open</i>
                                </div>
                                <div class="media-right" v-else>
                                    <i class="material-icons text-muted-light">lock</i>
                                </div>
                            </div>
                        </li>
                    </ul>
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
                audio_files: [],
                time: [],
                intervalTimeTick: null,
            };
        },
        created() {
            this.checkQuizBlock();
        },
        mounted() {
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
                this.intervalTimeTick != null ? clearInterval(this.intervalTimeTick) : '';
                axios
                    .post(this.routes.quiz.GET_QUIZ_FOR_STUDENT, {course_id: this.$route.params.idcourse})
                    .then(response => {
                        let _this = this;
                        this.intervalTimeTick = setInterval(() => {
                            _this.checkblockquiz = [];
                            response.data.data.forEach(function (value, index, array) {
                                if (value.is_block !== 1) {
                                    value['time'] = _this.initQuizClock(value.updated_at, response.data.timeZone, value.duration);
                                }
                                _this.checkblockquiz.push(value);
                            });
                        }, 1000);
                    });
            },
            initQuizClock(time, timezone, duration) {
                let quiz_duration = duration;
                let startTime = moment(time);
                let endTime = startTime.add(quiz_duration, "minutes").format("DD/MM/YYYY HH:mm:ss");
                let currentTime = new Date().toLocaleString("en-US", {timeZone: timezone});

                currentTime = new Date(currentTime);
                let result = moment
                    .utc(moment(endTime, "DD/MM/YYYY HH:mm:ss").diff( moment(currentTime, "DD/MM/YYYY HH:mm:ss") ))
                    .format("HH:mm:ss")
                    .split(":");
                let timeData = {hours: '00', minutes: '00', seconds: '00'};
                timeData.hours = result[0];
                timeData.minutes = result[1];
                timeData.seconds = result[2];

                if (
                    parseInt(timeData.hours) == 0 &&
                    parseInt(timeData.minutes) == 0 &&
                    parseInt(timeData.seconds) == 0
                ) {
                    clearInterval(this.intervalTimeTick);
                    this.checkQuizBlock();
                }

                return timeData;
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
        },
        beforeDestroy() {
            clearInterval(this.intervalTimeTick);
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
