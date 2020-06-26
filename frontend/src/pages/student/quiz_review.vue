<template>
    <div class="container-fluid page__container">
        <h1 class="h2">Exam Review</h1>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="flex">
                            <h4 class="card-title">{{ questions_answered_count }}/{{ questions.length }} questions answered</h4>
                        </div>
                    </div>
                    <ul class="list-group list-group-fit mb-0">
                        <li class="list-group-item" :key="question.id" v-for="question in questions"
                            @click="reviewQuestion(question)">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a class="text-body" v-if="question.type != 'matching_as_image'">
                                        <strong>{{ question.title }}</strong>
                                    </a>
                                    <a class="text-body" v-if="question.type == 'matching_as_image'">
                                        <img v-if=" question.title" :src=" question.title"
                                             alt="something wrong in image" width="50" class="rounded">
                                    </a>
                                </div>
                                <div class="media-right">
                                    <div class="text-center">
                                        <span v-if="question.check_ans == 1"
                                              :class="['badge badge-pill', 'badge-success']">ANSWERED</span>
                                        <span v-if="question.check_ans == 0"
                                              :class="['badge badge-pill', 'badge-danger']">NOT ANSWERED</span>
                                        <span v-if="question.check_ans == 2"
                                              :class="['badge badge-pill', 'badge-warning']">Partially ANSWERED</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <div class="container">
                        <div class="row" style="padding-bottom: 10px !important; padding-top: 10px; !important">
                            <div class="col" style="padding-right: 0rem !important; padding-left: 0rem !important;">
                                <a href="#" @click.prevent="backToLastQuestion" class="btn btn-warning float-left ">
                                    <
                                    Back
                                </a>
                            </div>
                            <div class="col" style="padding-right: 0rem !important; padding-left: 0rem !important;">
                                <center><a class="btn btn-secondary"
                                           style="color: #fff; font-size: 17px; padding: 7px;">{{ this.hours }}:{{
                                    this.minutes }}:{{ this.seconds }}</a></center>
                            </div>
                            <div class="col" style="padding-right: 0rem !important; padding-left: 0rem !important;">
                                <a href="#" @click.prevent="markQuizAttemptAsDone" class="btn btn-success float-right ">
                                    Submit
                                    <i class="material-icons btn__icon--right">send</i>
                                </a>
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
        data() {
            return {
                quiz_duration: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
                intervalTimeTick: null,
                questions: [],
                question: [],
                count: 0,
                count1: 0,
                count2: 0,
                cnt: 0,
                questions_answered_count: 0,
                exam_start_time: '',
            };
        },

        created() {
            console.log(this.$route.params);
        },

        mounted() {
            this.$session.start();
            this.initQuizClock();
            this.getAllQuestionByQuizIDForReview();
        },

        methods: {
            backToLastQuestion() {
                if (this.questions[this.questions.length - 1].parent_id != null) {
                    var q_id = this.questions[this.questions.length - 1].parent_id;
                } else {
                    var q_id = this.questions[this.questions.length - 1].id;
                }

                this.$router.push(
                    {
                        name: "review_quiz_attempt_question",
                        params: {
                            quiz_id: this.$route.params.quiz_id,
                            question_id: q_id
                        }
                    }
                )
            },

            reviewQuestion(question) {
                if ((question.type == "matching") || (question.type == "matching_as_image") || (question.type == "matching_text_image")) {

                    this.$router.push(
                        {
                            name: "review_quiz_attempt_question",
                            params: {
                                quiz_id: this.$route.params.quiz_id,
                                question_id: question.parent_id
                            }
                        })
                } else if (question.parent_id != null) {
                    this.$router.push(
                        {
                            name: "review_quiz_attempt_question",
                            params: {
                                quiz_id: this.$route.params.quiz_id,
                                question_id: question.parent_id
                            }
                        })
                } else {
                    this.$router.push(
                        {
                            name: "review_quiz_attempt_question",
                            params: {
                                quiz_id: this.$route.params.quiz_id,
                                question_id: question.id
                            }
                        })

                }

            },

            markQuizAttemptAsDone() {
                axios
                    .post(this.routes.quiz.MARK_QUIZ_ATTEMPT_AS_DONE, {
                        quiz_id: this.$route.params.quiz_id,
                        attempt_id: this.$route.params.attempt_id
                    })
                    .then(response => {
                        this.$session.destroy();
                        this.$router.push({
                            name: "quiz_result",
                            params: {
                                quiz_id: this.$route.params.quiz_id,
                                attempt_id: this.$route.params.attempt_id
                            }
                        });

                        console.log('response.data_check');
                        console.log(response.data);

                    });
            },

            getAllQuestionByQuizIDForReview() {
                this.get_questions = [];
                this.$store.dispatch("enableLoading");

                axios
                    .get(this.routes.question.GET_QUEST_OF_QUIZ + "/" + this.$route.params.quiz_id)
                    .then(response => {
                        console.log('response.data');
                        console.log(response.data);
                        this.get_questions = response.data;
                        //this.$store.dispatch("disableLoading");
                    });

                axios
                    .get(this.routes.question.GET_ALL_QUESTION_BY_QUIZ_ID_FOR_REVIEW, {
                        params: {
                            quiz_id: this.$route.params.quiz_id,
                            attempt_id: this.$route.params.attempt_id
                        }
                    })
                    .then(response => {
                        this.question = [];

                        response.data.forEach(q => {
                            if ((q.type == "matching" || q.type == "matching_as_image" || q.type == "matching_text_image" || q.type == "parent" || q.type == "single_set") && q.parent_id == null) {

                            } else {
                                this.question.push(q);
                            }
                        });

                        var newObj = [];
                        this.get_questions.forEach(questionId => {
                            if (questionId.child_questions == '') {
                                var getObj = {id: questionId.id};
                                newObj.push(getObj);
                            } else {
                                questionId.child_questions.forEach(nwQues => {
                                    var getObjt = {id: nwQues.id};
                                    newObj.push(getObjt);
                                });
                            }
                        });

                        this.cnt = 0;
                        var initial = 1;
                        this.questions = [];
                        newObj.forEach(quill => {
                            this.question.forEach(q => {
                                if (quill.id == q.parent_id) {
                                    this.questions.push(q);
                                }
                                if (quill.id == q.id) {
                                    this.questions.push(q);
                                }

                            });
                            this.cnt++;
                        });

                        this.questions.forEach(q => {
                            this.count1 = 0;
                            this.count2 = 0;

                            q.student_answer.forEach(val => {
                                this.count1++;

                                var get_answer = val.answer_content;
                                if (get_answer != null) {
                                    var new_answer = get_answer.replace(new RegExp("0,", "g"), "");
                                    if (new_answer != '0' && new_answer != null && new_answer != '') {
                                        this.count2++;
                                    }
                                } else {
                                    if (new_answer != '0' && new_answer != null && new_answer != '') {
                                        this.count2++;
                                    }
                                }
                            });
                            if (this.count1 == this.count2 && this.count1 != 0 && this.count2 != 0) {
                                this.questions_answered_count++;
                                this.questions[this.count]['check_ans'] = 1;
                            } else if (this.count1 != this.count2 && this.count1 != 0 && this.count2 != 0) {
                                this.questions[this.count]['check_ans'] = 2;
                            } else {
                                this.questions[this.count]['check_ans'] = 0;
                            }
                            this.count++;
                        });

                        var self = this;
                        setTimeout(function () {
                            self.$store.dispatch("disableLoading");
                        }, 2000);
                    });
            },

            initQuizClock() {
                let _this = this;
                let attempt_id = this.$route.params.attempt_id;
                axios.get(this.routes.quiz.GET_EXAM_QUIZ_BY_ID + "/" + attempt_id)
                    .then(response => {
                        // let time = this.$session.get("startTime");
                        let time = response.data.start_time;
                        let timezone = this.$session.get("timezone");
                        let quiz_duration = this.$session.get("quiz_duration");

                        this.intervalTimeTick = setInterval(() => {
                            let startTime = moment(time);
                            let endTime = startTime
                                .add(quiz_duration, "minutes")
                                .format("DD/MM/YYYY HH:mm:ss");
                            let currentTime = new Date().toLocaleString("en-US", {timeZone: timezone});
                            currentTime = new Date(currentTime);
                            let result = moment
                                .utc(
                                    moment(endTime, "DD/MM/YYYY HH:mm:ss").diff(
                                        moment(currentTime, "DD/MM/YYYY HH:mm:ss")
                                    )
                                )
                                .format("HH:mm:ss")
                                .split(":");
                            this.hours = result[0];
                            this.minutes = result[1];
                            this.seconds = result[2];


                            if (
                                parseInt(this.hours) == 0 &&
                                parseInt(this.minutes) == 0 &&
                                parseInt(this.seconds) == 0
                            ) {
                                clearInterval(this.intervalTimeTick);
                                swal("Exam time ended. The exam was automatically submitted. Click OK to see your results.");
                                this.markQuizAttemptAsDone();
                            }
                        }, 1000);
                    });
            }
        },

        beforeDestroy() {
            clearInterval(this.intervalTimeTick);
        }
    };
</script>
