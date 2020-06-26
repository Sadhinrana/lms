<template>
    <div class="container-fluid page__container">

        <div class="media flex-wrap align-items-center mb-headings">
            <div
                    class="media-left avatar avatar-lg avatar-4by3"
                    v-if="quiz.quiz_image"
            >
                <img
                        :src="routes.server_api + quiz.quiz_image"
                        alt=""
                        class="avatar-img rounded"
                />
            </div>
            <div class="media-body mb-3 mb-sm-0">
                <h1 class="h2 mb-0">{{ quiz.title }}</h1>
                <!-- <span class="text-muted">submited by</span> <a href="instructor-profile.html">Adrian Demian</a> -->
            </div>
        </div>

        <div class="card">
            <ul class="nav nav-tabs nav-tabs-card">
                <li class="nav-item">
                    <a class="nav-link active" href="#second" data-toggle="tab"
                    >All Exam</a
                    >
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="second">
                    <div class="table-responsive">
                        <table class="table table-middle">
                            <thead>
                            <tr>
                                <th>Student</th>
                                <th>Result</th>
                                <th>Points</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ user_info.first_name }} {{ user_info.last_name }}</td>
                                <td>
                                    <span id="pf_status"></span>
                                </td>
                                <td>{{ total_score }}</td>
                                <td>
                                    <span class="badge badge-light">{{ submitted }}</span>
                                </td>
                                <td>{{ spent_time }} min</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {eventBus} from "@/main";
    import moment from "moment";

    export default {
        data() {
            return {
                results: false,
                quiz: {
                    title: "",
                    description: "",
                    course_id: "",
                    lesson_id: "",
                    duration: "",
                    grade_to_pass: ""
                },
                questions: [],
                grade_to_pass: 0,
                total_score: 0,
                submitted: "",
                spent_time: ""
            };
        },
        mounted() {
            $('#pf_status').text("Exams results are calculating. Please wait….");
            $('#pf_status').addClass("badge badge-danger");
            this.$store.dispatch("enableLoading");
            this.fetchQuiz();
            var self = this;
            // Only run if authUser is fetched
            if (self.user_info.id) {
                self.fetchQuestion(self.user_info.id);
            }
            //If this is the first time user visit page, wait until authUser is fetched
            eventBus.$on("authUserFetched", function () {
                self.fetchQuestion(self.user_info.id);
            });
        },
        computed: {
            user_info() {
                return this.$store.getters.authUser;
            }
        },
        methods: {
            fetchQuiz() {
                axios
                    .get(this.routes.quiz.GET_BY_ID, {
                        params: {id: this.$route.params.quiz_id}
                    })
                    .then(res => {
                        this.quiz = res.data;
                        this.results = true;
                        // this.$store.dispatch("disableLoading");
                    })
                    .catch(err => {
                        this.$store.dispatch("disableLoading");
                        console.log(err.response);
                    });
            },
            fetchQuestion(user_id) {

                axios
                    .get(
                        this.routes.quiz.GET_RESULT_REVIEW +
                        "/" +
                        user_id +
                        "/" +
                        this.$route.params.quiz_id +
                        "/" +
                        this.$route.params.attempt_id
                    )
                    .then(res => {
                        axios
                            .get(
                                this.routes.quiz.GET_RESULT +
                                "/" +
                                user_id +
                                "/" +
                                this.$route.params.quiz_id +
                                "/" +
                                this.$route.params.attempt_id
                            )
                            .then(res => {
                                this.total_score = res.data[0][0].attempt.total_score;
                                this.submitted = moment(res.data[0][0].attempt.updated_at).format('DD/MM');
                                let start_time = moment(res.data[0][0].attempt.start_time);
                                let end_time = moment(res.data[0][0].attempt.end_time);
                                this.spent_time = moment.duration(end_time.diff(start_time)).minutes();

                                if (this.total_score >= 0) {
                                    axios
                                        .get(this.routes.quiz.GET_BY_ID, {
                                            params: {id: this.$route.params.quiz_id}
                                        })
                                        .then(res => {
                                            this.quiz = res.data;
                                            this.setStatus(res.data.grade_to_pass, this.total_score);
                                        });
                                }

                                // this.$store.dispatch("disableLoading");
                            })
                            .catch(err => {
                                this.$store.dispatch("disableLoading");
                                console.log(err.response);
                            });
                    })
                    .catch(err => {
                        this.$store.dispatch("disableLoading");
                        console.log(err.response);
                    });

            },
            setStatus(grade_to_pass, total_score) {
                console.log(grade_to_pass + '=' + total_score);
                console.log(this.user_info.id);
                const status = 1;
                if (grade_to_pass <= total_score) {
                    $('#pf_status').text("PASSED");
                    $('#pf_status').addClass("badge-success").removeClass('badge-danger');
                    this.blockQuiz(this.user_info.id, status);
                } else {
                    $('#pf_status').text("FAILED");
                }
                this.$store.dispatch("disableLoading");
            },
            blockQuiz(student_id, status) {

                axios
                    .post(this.routes.quiz.QUIZ_BLOCK_UPDATE_ATTEMPT, {
                        student_id: student_id,
                        quiz_id: this.$route.params.quiz_id,
                        is_block: status
                    }).then(response => {

                    console.log(response.data);

                });
            }
        }
    };
</script>

<style></style>
