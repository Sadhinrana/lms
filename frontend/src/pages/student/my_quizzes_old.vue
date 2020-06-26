<template>
    <div class="container-fluid page__container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <router-link :to="{name:'home'}">Home</router-link>
            </li>
            <li class="breadcrumb-item active">My quizzes</li>
        </ol>
        <div class="media mb-headings align-items-center">
            <div class="media-body">
                <h1 class="h2">My quizzes</h1>
            </div>
            <div class="media-right">
                <div class="btn-group btn-group-sm">
                    <a
                            href="#"
                            class="btn btn-white active"
                    ><i class="material-icons">list</i></a>
                    <a
                            href="#"
                            class="btn btn-white"
                    ><i class="material-icons">dashboard</i></a>
                </div>
            </div>
        </div>
        <div class="card-columns">
            <div
                    class="card"
                    v-for="attempt in quiz_attempts"
                    :key="attempt.id"
            >
                <div class="card-header">
                    <div class="media">
                        <div class="media-left" v-if="attempt.quiz.quiz_image" style="max-width:100px;">
                            <router-link
                                    :to="{ name: 'quiz_result', params: {quiz_id:attempt.quiz.id, attempt_id:attempt.id} }">
                                <img :src="routes.server_api + attempt.quiz.quiz_image" alt=""
                                     class="avatar-img rounded">
                            </router-link>
                        </div>
                        <div class="media-body">
                            <h4 class="card-title m-0">
                                <router-link
                                        :to="{ name: 'quiz_result', params: {quiz_id:attempt.quiz.id, attempt_id:attempt.id} }">
                                    {{ attempt.quiz.title }}
                                </router-link>
                            </h4>
                            <small class="text-muted">answered: {{ attempt.questions_answered_count }} of {{
                                attempt.questions_count }}</small>
                        </div>
                    </div>
                </div>
                <div class="progress rounded-0">
                    <div
                            class="progress-bar progress-bar-striped bg-primary"
                            role="progressbar"
                            :style="{  width: attempt.question_anwered_percentage + '%'  }"
                            :aria-valuenow="attempt.question_anwered_percentage"
                            aria-valuemin="0"
                            aria-valuemax="100"
                    ></div>
                </div>
                <div class="card-footer bg-white">
                    <router-link
                            v-if="!attempt.is_completed"
                            class="btn btn-primary btn-sm"
                            :to="{ name: 'take_quiz', params:{quiz_id:attempt.quiz_id}}"
                    >
                        Continue <i class="material-icons btn__icon--right">play_circle_outline</i></router-link>
                    <router-link
                            v-else-if="attempt.is_completed"
                            class="btn btn-primary btn-sm"
                            to="/"
                    >
                        View result <i class="material-icons btn__icon--right">play_circle_outline</i></router-link>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <ul class="pagination justify-content-center pagination-sm">
            <li class="page-item disabled">
                <a
                        class="page-link"
                        href="#"
                        aria-label="Previous"
                >
          <span
                  aria-hidden="true"
                  class="material-icons"
          >chevron_left</span>
                    <span>Prev</span>
                </a>
            </li>
            <li class="page-item active">
                <a
                        class="page-link"
                        href="#"
                        aria-label="1"
                >
                    <span>1</span>
                </a>
            </li>
            <li class="page-item">
                <a
                        class="page-link"
                        href="#"
                        aria-label="1"
                >
                    <span>2</span>
                </a>
            </li>
            <li class="page-item">
                <a
                        class="page-link"
                        href="#"
                        aria-label="Next"
                >
                    <span>Next</span>
                    <span
                            aria-hidden="true"
                            class="material-icons"
                    >chevron_right</span>
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
                quiz_attempts: []
            };
        },
        mounted() {
            this.getQuizAttempts();
        },
        computed: {
            user_info() {
                return this.$store.getters.authUser;
            }
        },
        methods: {
            getQuizAttempts() {
                axios.get(this.routes.quiz.GET_QUIZ_ATTEMPTS).then(response => {
                    this.quiz_attempts = response.data;
                });
            }
        }
    };
</script>

<style>
</style>
