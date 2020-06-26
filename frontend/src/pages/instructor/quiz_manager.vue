<template>
    <div class="container-fluid page__container">

        <div class="media align-items-center mb-headings">
            <div class="media-body">
                <h1 class="h2">Exams</h1>
            </div>
            <div class="media-right d-flex align-items-center">
                <router-link
                        :to="{ name: 'instructor_add_quiz' }"
                        class="btn btn-success mr-2"
                >
                    Add an Exam
                </router-link>
                <div class="dropdown">
                    <a
                            href="#"
                            data-toggle="dropdown"
                            class="btn btn-white"
                    ><i class="material-icons">tune</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-item">
                            <form action="#">
                                <div class="form-group mb-0">
                                    <label
                                            class="form-label"
                                            for="custom-select"
                                    >Category</label><br>
                                    <select
                                            id="custom-select"
                                            class="form-control custom-select"
                                            style="width: 200px;"
                                    >
                                        <option selected>All categories</option>
                                        <option value="1">Vue.js</option>
                                        <option value="2">Node.js</option>
                                        <option value="3">GitHub</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-body border-left-3 border-left-primary navbar-shadow mb-4">
            <form action="#" @submit.prevent>
                <div class="d-flex flex-wrap2 align-items-center mb-headings">

                    <div class="flex search-form ml-3 search-form--light">
                        <input
                                type="text"
                                class="form-control"
                                placeholder="Search exams by name"
                                id="searchSample02"
                                v-model.lazy="search_key_word"
                                v-debounce="5000"
                        >
                        <button
                                class="btn"
                                type="button"
                                role="button"
                        ><i class="material-icons">search</i></button>
                    </div>
                </div>

                <div
                        class="d-flex flex-column flex-sm-row align-items-sm-center"
                        style="white-space: nowrap;"
                >
                    <small class="flex text-muted text-uppercase mr-3 mb-2 mb-sm-0"></small>

                </div>
            </form>
        </div>

        <div
                class="card-columns"
                v-if="search_key_word.length == 0"
        >

            <div
                    v-for="quizz in quizzes"
                    :key="quizz.id"
                    class="card card-sm"
            >
                <div class="card-body media">
                    <div class="media-left">
                        <router-link
                                :to="{ name: 'instructor_edit_quiz', params: {id:quizz.id} }"
                                class="avatar avatar-lg avatar-4by3"
                        >
                            <img
                                    :src="routes.server_api + quizz.quiz_image"
                                    alt="Card image cap"
                                    class="avatar-img rounded"
                                    v-if="quizz.quiz_image"
                            >
                            <img
                                    v-else
                                    :src="routes.server_api+ '/img/default.png'"
                                    alt="Card image cap"
                                    class="avatar-img rounded"
                            >
                        </router-link>
                    </div>
                    <div class="media-body">
                        <h4 class="card-title mb-0">
                            <router-link :to="{ name: 'instructor_edit_quiz', params: {id:quizz.id} }">
                                {{quizz.title}}
                            </router-link>
                        </h4>
                        <!-- <small class="text-muted">25 taken</small> -->
                    </div>
                </div>
                <div class="card-footer text-center">
                    <!-- <router-link -->
                    <!-- :to="{ name: 'instructor_edit_quiz', params: {id:quizz.id} }" -->
                    <!-- class="btn btn-white btn-sm float-left" -->
                    <!-- > -->
                    <!-- <i class="material-icons btn__icon--left">playlist_add_check</i> Review <span class="badge badge-dark ml-2">5</span> -->
                    <!-- </router-link> -->

                    <div class="dropdown float-right">
                        <button v-if="authUser.role !== 'content_manager'"
                                class="btn btn-default btn-sm dropdown-toggle"
                                type="button"
                                id="dropdownMenuButton"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                        >
                            More
                        </button>
                        <div
                                class="dropdown-menu"
                                aria-labelledby="dropdownMenuButton"
                        >
                            <a
                                    @click="deleteQuiz(quizz)"
                                    class="btn btn-default btn-sm dropdown-item"
                            ><i class="material-icons btn__icon--left">delete</i> Delete </a>
                        </div>

                    </div>
                    <router-link
                            :to="{name:'instructor_edit_quiz',params:{id:quizz.id}}"
                            class="btn btn-default btn-sm float-right"
                    ><i class="material-icons btn__icon--left">edit</i> Edit
                    </router-link>
                    <div class="clearfix"></div>
                </div>
            </div>

        </div>

        <div
                class="card-columns"
                v-if="search_key_word.length > 0 && quizzes_from_search.length > 0"
        >

            <div
                    v-for="quizz in quizzes_from_search"
                    :key="quizz.id" :id="'quizzes_from_search_'+quizz.id"
                    class="card card-sm"
            >
                <div class="card-body media">
                    <div class="media-left">
                        <router-link
                                :to="{ name: 'instructor_edit_quiz', params: {id:quizz.id} }"
                                class="avatar avatar-lg avatar-4by3"
                        >
                            <img
                                    :src="routes.server_api + quizz.quiz_image"
                                    alt="Card image cap"
                                    class="avatar-img rounded"
                                    v-if="quizz.quiz_image"
                            >
                            <img
                                    v-else
                                    src="@/assets/images/vuejs.png"
                                    alt="Card image cap"
                                    class="avatar-img rounded"
                            >
                        </router-link>
                    </div>
                    <div class="media-body">
                        <h4 class="card-title mb-0">
                            <router-link :to="{ name: 'instructor_edit_quiz', params: {id:quizz.id} }">
                                {{quizz.title}}
                            </router-link>
                        </h4>
                        <!-- <small class="text-muted">25 taken</small> -->
                    </div>
                </div>
                <div class="card-footer text-center">
                    <!-- <router-link -->
                    <!-- :to="{ name: 'instructor_review_quiz', params: {id:'1'} }" -->
                    <!-- class="btn btn-white btn-sm float-left" -->
                    <!-- > -->
                    <!-- <i class="material-icons btn__icon--left">playlist_add_check</i> Review <span class="badge badge-dark ml-2">5</span> -->
                    <!-- </router-link> -->

                    <div class="dropdown float-right">
                        <button v-if="authUser.role !== 'content_manager'"
                                class="btn btn-default btn-sm dropdown-toggle"
                                type="button"
                                id="dropdownMenuButton"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                        >
                            More
                        </button>
                        <div
                                class="dropdown-menu"
                                aria-labelledby="dropdownMenuButton"
                        >
                            <a
                                    @click="deleteQuiz(quizz)"
                                    class="btn btn-default btn-sm dropdown-item"
                            ><i class="material-icons btn__icon--left">delete</i> Delete </a>
                        </div>

                    </div>
                    <router-link
                            :to="{name:'instructor_edit_quiz',params:{id:quizz.id}}"
                            class="btn btn-default btn-sm float-right"
                    ><i class="material-icons btn__icon--left">edit</i> Edit
                    </router-link>
                    <div class="clearfix"></div>
                </div>
            </div>

        </div>

        <div
                v-else
                class="alert alert-light alert-dismissible border-1 border-left-3 border-left-warning"
                role="alert"
        >
            <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Close"
            >
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="text-black-70">No results found.</div>
        </div>

        <!-- Pagination -->

        <ul class="pagination justify-content-center pagination-sm" v-if="search_key_word.length == 0">
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
    import debounce from "v-debounce";

    export default {
        directives: {
            debounce
        },

        watch: {
            search_key_word(newVal, oldVal) {
                this.searchQuizzes(newVal);
            }
        },

        data() {
            return {
                quizzes: [],
                search_key_word: "",
                quizzes_from_search: [],
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
            authUser: function () {
                return this.$store.getters.authUser
            }
            /*company_id: function() {
              return this.$store.getters.authUser.company_id;
            }*/
        },

        mounted() {
            //this.getQuizzes();
            eventBus.$on("authUserFetched", e => {
                this.getQuizzes(this.pagination.current_page);
            });
            this.getQuizzes(this.pagination.current_page);
        },

        methods: {
            searchQuizzes(keyword) {
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.quiz.SEARCH_FOR_QUIZZES, {
                        params: {
                            keyword: keyword
                        }
                    })
                    .then(response => {
                        this.quizzes_from_search = response.data;
                        this.$store.dispatch("disableLoading");
                    })
                    .catch(e => {
                        this.$store.dispatch("disableLoading");
                    });
            },

            getQuizzes(page) {
                this.$store.dispatch("enableLoading");
                axios.get(this.routes.quiz.GET_ALL, {
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
                        this.$store.dispatch("disableLoading");
                    }).catch(e => {
                    this.$store.dispatch("disableLoading");
                });
            },

            deleteQuiz(quiz) {
                var confirmed = confirm("Are you sure you want to delete this quiz?");
                if (confirmed) {
                    this.$store.dispatch("enableLoading");
                    axios
                        .delete(this.routes.quiz.DELETE_QUIZ, {
                            data: {
                                quiz: quiz
                            }
                        })
                        .then(response => {
                            this.$store.dispatch("disableLoading");
                            $('#quizzes_from_search_' + quiz.id).hide();
                            this.getQuizzes();
                        });
                }
            }
        }
    };
</script>

<style>
    [dir=ltr] .dropdown-menu.show, [dir=ltr] .show > .dropdown-menu {
        margin-top: .0rem !important;
    }
</style>
