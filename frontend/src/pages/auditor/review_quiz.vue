<template>
    <div class="container-fluid page__container">
        <!-- <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <router-link :to="{ name: 'home'}">Home</router-link>
            </li>
            <li class="breadcrumb-item">
              <router-link :to="{ name: 'instructor_quiz_manager'}">Quiz manager</router-link>
            </li>
            <li class="breadcrumb-item active">Quiz Review</li>
        </ol> -->

        <div class="media flex-wrap align-items-center mb-headings">
            <div class="media-left avatar avatar-lg avatar-4by3" v-if="quiz.quiz_image">
                <img :src="routes.server_api + quiz.quiz_image" alt="" class="avatar-img rounded">
            </div>
            <div class="media-body mb-3 mb-sm-0">
                <h1 class="h2 mb-0">{{quiz.title}}</h1>
                <!-- <span class="text-muted">submited by</span> <a href="instructor-profile.html">Adrian Demian</a> -->
            </div>
            <div class="media-body mb-3 mb-sm-0">
                <router-link :to="{ name: 'auditor_edit_user',params: {id:user_id}}" class="btn btn-info"
                             style="float: right !important;">Back
                </router-link>
            </div>
        </div>

        <div class="card">
            <ul class="nav nav-tabs nav-tabs-card">
                <li class="nav-item">
                    <a class="nav-link active" href="#second" data-toggle="tab">All Questions</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="second">
                    <div class="table-responsive">
                        <table class="table table-middle">
                            <thead>
                            <tr>
                                <th style="width: 60%;">Question</th>
                                <th>Answers</th>
                                <th>Points</th>
                                <th>Result</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(question, index) in questions">
                                <td>
                                    <!-- <span v-if="['matching_as_image'].includes(question.type)"> -->
                                    <!-- <img :src="question.question" alt="" width="80" class="rounded" v-if="question.question"> -->
                                    <!-- </span> -->
                                    <span>
										{{question.question}}
									</span>
                                </td>

                                <td>
									<span v-if="['single_choice_as_image'].includes(question.type)">
											<img :src="question.answer.answer_title" alt="" width="80" class="rounded"
                                                 v-if="question.answer.answer_title">
									</span>
                                    <span v-else>
											{{question.answer.answer_title}}
									</span>
                                </td>

                                <!--  score result -->

                                <td>
                                    <strong>
                                        {{question.score}}
                                    </strong>
                                </td>

                                <!--  staus result -->

                                <td>
                                    <span class="badge badge-danger" v-if="question.score == '0'">Wrong!</span>
                                    <span class="badge badge-success" v-else>Correct!</span>
                                </td>


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

    export default {
        data() {
            return {
                user_id: this.$route.params.user_id,
                quiz: {
                    title: "",
                    description: "",
                    course_id: "",
                    lesson_id: "",
                    duration: "",
                    grade_to_pass: ""
                },
                questions: [],
                total_score: 0,
                submitted: '',
            };
        },
        mounted() {
            this.fetchQuiz();

            this.fetchQuestion();
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
                    })
                    .catch(err => {
                        console.log(err.response);
                    });
            },
            fetchQuestion() {
                axios
                    .get(this.routes.quiz.GET_RESULT_REVIEW + "/" + this.$route.params.user_id + "/" + this.$route.params.quiz_id + "/" + this.$route.params.attempt_id)
                    .then(res => {
                        this.questions = res.data
                        console.log(this.questions);
                    })
                    .catch(err => {
                        console.log(err.response);
                    });
            }
        }
    };
</script>

<style>
</style>
