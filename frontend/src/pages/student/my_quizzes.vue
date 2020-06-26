<template>
    <div class="container-fluid page__container">
        <div class="media mb-headings align-items-center">
            <div class="media-body">
                <h1 class="h2">My Exams</h1>
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
        <div class="card">
            <div class="table-responsive card-body">
                <table class="table table-middle">
                    <thead>
                    <tr>
                        <th>Exam</th>
                        <th>Result</th>
                        <th>Points</th>
                        <th>Date</th>
                        <th>Time</th>
                        <!--<th>Review</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(quiz, index) in quizzes" :key="quiz.id">
                        <td>
                            {{ quiz.quiz.title }}
                        </td>
                        <td>
                <span
                        class="badge badge-success"
                        v-if="quiz.quiz.grade_to_pass <= quiz.total_score"
                >PASSED</span
                >
                            <span class="badge badge-danger" v-else>FAILED</span>
                        </td>
                        <td>{{ quiz.total_score }}</td>
                        <td>
                            <span class="badge badge-light ">{{ quiz.end_time }}</span>
                        </td>
                        <td>{{ quiz.spent_time }} min</td>
                        <!--<td>
                            <a href="javascript:void(0)"  @click="getScore(user_info.id, quiz.quiz_id, quiz.id)">Review</a>
                        </td>-->
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <ul class="pagination justify-content-center pagination-sm">
            <li v-if="pagination.current_page > 1" class="page-item">
                <a
                        @click.prevent="getQuizzes(pagination.current_page - 1)"
                        class="page-link"
                        href="#"
                        aria-label="Previous"
                >
                    <span aria-hidden="true" class="material-icons">chevron_left</span>
                    <span>Prev</span>
                </a>
            </li>

            <li
                    :key="page"
                    v-for="page in pagesNumber"
                    :class="[
          page == pagination.current_page ? 'active' : null,
          'page-item'
        ]"
            >
                <a
                        href="#"
                        v-on:click.prevent="getQuizzes(page)"
                        class="page-link"
                        aria-label="Previous"
                >{{ page }}</a
                >
            </li>

            <li v-if="pagination.current_page < pagination.last_page">
                <a
                        href="javascript:void(0)"
                        aria-label="Next"
                        v-on:click.prevent="getQuizzes(pagination.current_page + 1)"
                >
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true" class="material-icons">chevron_right</span>
                </a>
            </li>
        </ul>

        <!-- modal-->
        <div class="modal fade" :id="'studentBlockForm' + modal_index">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">Part score</h4>
                        <button
                                type="button"
                                class="close text-white"
                                data-dismiss="modal"
                                aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-middle">
                            <tbody>
                            <tr>
                                <th>Listening: Part 1 score: </th><th>{{this.score_data?this.score_data.Listening_Part1_got:0}} out of {{this.score_data?this.score_data.Listening_Part1_out_of:0}}</th>
                            </tr>
                            <tr>
                                <th>Voc & Gram. Part 2 score: </th><th>{{this.score_data?this.score_data.VocGramPart2score_got:0}} out of {{this.score_data?this.score_data.VocGramPart2score_out_of:0}}</th>
                            </tr>
                            <tr>
                                <th>Voc & Reading: Part 3 score:  </th><th>{{this.score_data?this.score_data.VocReadingPart3score_got:0}} out of {{this.score_data?this.score_data.VocReadingPart3score_out_of:0}}</th>
                            </tr>
                            <tr>
                                <th>Vocabulary: Part 4 score:  </th><th>{{this.score_data?this.score_data.VocabularyPart4score_got:0}} out of {{this.score_data?this.score_data.VocabularyPart4score_out_of:0}}</th>
                            </tr>
                            <tr>
                                <th>Forming Sen: Part 5 score:  </th><th>{{this.score_data?this.score_data.FormingSenPart5score_got:0}} out of {{this.score_data?this.score_data.FormingSenPart5score_out_of:0}}</th>
                            </tr>
                            </tbody>
                        </table>
                        <div class="form-group row mb-0">
                            <div class="col">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal -->
    </div>
</template>
<script>
    import {eventBus} from "@/main";

    export default {
        data() {
            return {
                quizzes: [],
                score_data:[],
                modal_index: 1,
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                }
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
            }
        },
        mounted() {
            this.init();
            var self = this;
            // Only run if authUser is fetched
            if (self.user_info.id) {
                self.getQuizzes(self.pagination.current_page);
            }
            //If this is the first time user visit page, wait until authUser is fetched
            eventBus.$on("authUserFetched", function () {
                self.getQuizzes(self.pagination.current_page);
            });
        },
        methods: {
            init() {
                let _this = this;
                //----This line bellow is one of the best thing I've ever created ! Ever ! :)) -----//
                //Append the modal element to another div outside of this file (located in App.vue) :
                $("#modelDestination").empty();
                $("#studentBlockForm" + this.modal_index).appendTo("#modelDestination");

                if (this.$session.get("studentBlockForm")) {
                    if (this.$session.get("studentBlockForm") != "studentBlockForm" + this.modal_index) {
                        var check_modal = this.$session.get("studentBlockForm");
                        $("#" + check_modal).remove();
                    }
                }

                $("#studentBlockForm" + this.modal_index).on("hidden.bs.modal", function (e) {
                    $("#studentBlockForm" + _this.modal_index).modal("hide");
                    _this.modal_index++;
                    var modal_id = "studentBlockForm" + _this.modal_index;
                    _this.$session.set("studentBlockForm", modal_id);
                    $("#studentBlockForm" + _this.modal_index).remove();
                });
            },
            getQuizzes(page) {
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.quiz.GET_QUIZ_ATTEMPTS, {
                        params: {
                            page: page
                        }
                    })
                    .then(response => {
                        this.quizzes = response.data.data;
                        this.pagination.from = response.data.from;
                        this.pagination.to = response.data.to;
                        this.pagination.total = response.data.total;
                        this.pagination.per_page = response.data.per_page;
                        this.pagination.last_page = response.data.last_page;
                        this.pagination.current_page = response.data.current_page;

                        if (!this.quizzes.length) {
                            for (let i = response.data.from - 1; i < response.data.to; i++) {
                                var start_time = moment(response.data.data[i].start_time);
                                var end_time = moment(response.data.data[i].end_time);
                                response.data.data[i].spent_time = moment.duration(end_time.diff(start_time)).minutes();
                                response.data.data[i].end_time = moment(response.data.data[i].end_time).format('DD/MM');
                            }
                            this.quizzes = response.data.data;

                        } else {
                            this.quizzes.forEach(q => {
                                var start_time = moment(q.start_time);
                                var end_time = moment(q.end_time);
                                q.spent_time = moment.duration(end_time.diff(start_time)).minutes();
                                q.end_time = moment(q.end_time).format('DD/MM');
                            });
                        }

                        this.$store.dispatch("disableLoading");
                    }).catch(err => {
                    this.$store.dispatch("disableLoading");
                    console.log(err);
                });
            },
            fetchQuestion(quiz_id, attempt_id, index) {
                this.$store.dispatch("enableLoading");
                axios
                    .get(
                        this.routes.quiz.GET_RESULT_BY_STUDENT +
                        "/" +
                        quiz_id +
                        "/" +
                        attempt_id
                    )
                    .then(res => {
                        let total_score = 0;
                        res.data.forEach(i => {
                            if (
                                (i[0].question.child_questions == "" ||
                                    i[0].question.type == "matching") &&
                                i.result
                            ) {
                                total_score += i[0].question.score * i.result;
                            }
                        });
                        let start_time = moment(res.data[0][0].attempt.start_time);
                        let end_time = moment(res.data[0][0].attempt.end_time);

                        //Vue cannot reactive aditional data so we have to set like this :
                        this.$set(
                            this.quizzes[index],
                            "spent_time",
                            moment.duration(end_time.diff(start_time)).minutes()
                        );
                        this.$set(this.quizzes[index], "total_score", total_score);
                        this.$set(
                            this.quizzes[index],
                            "submitted",
                            // res.data[res.data.length - 1][0].updated_at
                            moment(res.data[res.data.length - 1][0].updated_at).format('DD-MM-YYYY h:mm a')
                        );
                        this.$store.dispatch("disableLoading");
                    })
                    .catch(err => {
                        this.$store.dispatch("disableLoading");
                        console.log(err.response);
                    });
            },
            getScore(user_id, quiz_id, attempt_id) {
                $("#studentBlockForm" + this.modal_index).modal('show');
                axios.get(this.routes.quiz.GET_PART_SCORE +"/" + user_id +"/" +quiz_id +"/" +attempt_id)
                    .then(res => {
                        this.score_data=res.data.score;
                        this.score_parra=res.data.data;
                        setTimeout(function(){
                            $("#studentBlockForm" + this.modal_index).modal('hide');
                        }, 30000);
                    })
                    .catch(err => {
                        console.log(err.response);
                    });
            },
        }
    };
</script>

<style></style>
