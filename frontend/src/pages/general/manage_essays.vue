<template>
    <div class="container-fluid page__container">
        <div class="media mb-headings align-items-center">
            <div class="media-body">
                <h1 class="h2">Manage essays</h1>
            </div>
        </div>

        <div class="card card-body border-left-3 border-left-primary navbar-shadow mb-4">
            <form action="#" @submit.prevent="getStudentEssaysAnswers(1)">
                <div class="d-flex flex-wrap2 align-items-center mb-headings">
                    <div class="flex search-form ml-3 search-form--light">
                        <input type="text" class="form-control" placeholder="Search essays..." id="searchSample02" v-model="search_key_word" @change="searchEssays">
                        <button class="btn" type="button" role="button" @click="getStudentEssaysAnswers(1)"><i class="material-icons">search</i></button>
                    </div>
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center" style="white-space: nowrap;">
                    <small class="flex text-muted text-uppercase mr-3 mb-2 mb-sm-0">Displaying {{pagination.from}} to {{pagination.to}} out of {{pagination.total}} essay answers</small>
                </div>
            </form>
        </div>

        <div class="card" v-if="essayAnswers.length">
            <div class="table-responsive card-body">
                <table class="table table-middle">
                    <thead>
                    <tr>
                        <th>Student</th>
                        <th>Essay</th>
                        <th>Question</th>
                        <th>Date</th>
                        <th>Reviews</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(essayAnswer, index) in essayAnswers" :key="essayAnswer.id">
                        <td>
                            <div class="media align-items-center">
                                <div class="avatar avatar-sm mr-3">
                                    <img :src="routes.server_api + essayAnswer.user.avatar_url" alt="Avatar" class="avatar-img rounded-circle" v-if="essayAnswer.user.avatar_url">
                                    <img :src="routes.server_api+ '/img/default_avatar.jpg'" alt="" class="avatar-img rounded-circle" v-else>
                                </div>
                                <div class="media-body">
                                    <span class="js-lists-values-employee-name">
                                        <a href="javascript:void(0)">{{ essayAnswer.user.first_name + " " + essayAnswer.user.last_name }}</a>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>{{ essayAnswer.essay.title }}</td>
                        <td>{{ essayAnswer.essay.question }}</td>
                        <td>{{ essayAnswer.created_at }}</td>
                        <td>
                            <a href="javascript:void(0)" @click="reviewAnswer(index)" v-if="essayAnswer.essay.type === 'unit' || user_info.role !== 'head_teacher'">
                                Reviews({{ essayAnswer.reviews.length }}) <span v-if="getUnseenReview(index)">({{ getUnseenReview(index) }} unseen)</span>
                            </a>
                            <a href="javascript:void(0)" v-else>N/A</a>
                        </td>
                        <td>
                            <div v-if="essayAnswer.essay.type === 'unit' || user_info.role !== 'head_teacher'">
                                <button class="btn btn-warning btn-sm" @click="closeAnswer(essayAnswer.id, index)" v-if="!essayAnswer.is_closed">Close</button>
                                <button class="btn btn-success btn-sm" @click="recoverAnswer(essayAnswer.id, index)" v-else-if="user_info.role === 'company_head'">Recover</button>
                                <button class="btn btn-danger btn-sm" v-else>Closed</button>
                            </div>
                            <div class="text-danger" v-else>
                                N/A
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-else class="alert alert-light alert-dismissible border-1 border-left-3 border-left-warning" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <div class="text-black-70">No results found.</div>
        </div>

        <!-- Pagination -->
        <ul class="pagination justify-content-center pagination-sm" v-if="essayAnswers.length">
            <li v-if="pagination.current_page>1" class="page-item">
                <a @click.prevent="getStudentEssaysAnswers(pagination.current_page - 1)" class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true" class="material-icons">chevron_left</span>
                    <span>Prev</span>
                </a>
            </li>

            <li :key="page" v-for="page in pagesNumber" :class="[page === pagination.current_page ? 'active' : null , 'page-item']">
                <a href="#" v-on:click.prevent="getStudentEssaysAnswers(page)" class="page-link" aria-label="Previous" >{{ page }}</a>
            </li>

            <li v-if="pagination.current_page < pagination.last_page">
                <a href="javascript:void(0)" aria-label="Next" v-on:click.prevent="getStudentEssaysAnswers(pagination.current_page + 1)">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true" class="material-icons">chevron_right</span>
                </a>
            </li>
        </ul>

        <!-- modal-->
        <div class="modal fade" :id="'essayModal' + modal_index">
            <div class="modal-dialog modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">Review essay</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="table-responsive card-body">
                                <table class="table table-middle" v-if="reviews.length">
                                    <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Review</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(review, index) in reviews" :key="review.id">
                                        <td>
                                            <div class="media align-items-center">
                                                <div class="avatar avatar-sm mr-3">
                                                    <img :src="routes.server_api + review.user.avatar_url" alt="Avatar" class="avatar-img rounded-circle" v-if="review.user.avatar_url">
                                                    <img :src="routes.server_api+ '/img/default_avatar.jpg'" alt="" class="avatar-img rounded-circle" v-else>
                                                </div>
                                                <div class="media-body">
                                                    <span class="js-lists-values-employee-name">
                                                        <a href="javascript:void(0)">{{ review.user.first_name + " " + review.user.last_name }}</a>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td v-html="review.review"></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p v-else class="text-center">No review yet!</p>
                            </div>
                        </div>

                        <form action="#" @submit.prevent="store">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Answer:</label>
                                <div class="col-sm-9">
                                    <p v-html="answer"></p>
                                </div>
                            </div>

                            <div class="form-group row" v-show="!is_closed">
                                <label class="col-sm-3 col-form-label form-label">Review:</label>
                                <div class="col-sm-9">
                                    <div class="quill-outer">
                                        <div id="review" v-model="quill" style="height: 150px;" data-toggle="review" data-quill-placeholder="Answer">
                                            <p></p>
                                        </div>

                                    </div>

                                    <div class="invalid-feedback d-block" v-if="errors.review">
                                        {{ errors.review[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0" v-show="!is_closed">
                                <div class="col">
                                    <button type="submit" class="btn btn-success float-right">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal -->
    </div>
</template>

<script>
    import {QuillDeltaToHtmlConverter} from "quill-delta-to-html";

    export default {
        data() {
            return {
                essayAnswers: [],
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4,
                quill: null,
                essay_review: {
                    review: '',
                    is_student: 0,
                    essay_answer_id: '',
                },
                answer: '',
                reviews: [],
                errors: [],
                modal_index: 1,
                is_closed: 0,
                search_key_word: ''
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
            },
        },
        created() {
            console.log(this.user_info);
        },
        mounted() {
            this.init();
            this.initQuillEditor();
            this.getStudentEssaysAnswers(1);
        },
        methods: {
            init() {
                let _this = this;
                //----This line bellow is one of the best thing I've ever created ! Ever ! :)) -----//
                //Append the modal element to another div outside of this file (located in App.vue) :
                $("#modelDestination").empty();
                $("#essayModal" + this.modal_index).appendTo("#modelDestination");

                if (this.$session.get("essayModal")) {
                    if (this.$session.get("essayModal") != "essayModal" + this.modal_index) {
                        var check_modal = this.$session.get("essayModal");
                        $("#" + check_modal).remove();
                    }
                }

                $("#essayModal" + this.modal_index).on("hidden.bs.modal", function (e) {
                    $("#essayModal" + _this.modal_index).modal("hide");
                    _this.modal_index++;
                    var modal_id = "essayModal" + _this.modal_index;
                    _this.$session.set("essayModal", modal_id);
                    $("#essayModal" + _this.modal_index).remove();
                });
            },
            getStudentEssaysAnswers(page) {
                this.$store.dispatch("enableLoading");
                axios
                    .get(
                        this.routes.admin.ESSAYS_ANSWER, {
                            params: {
                                page: page,
                                keyword: this.search_key_word,
                            }
                        }
                    ).then(response => {
                    if (response.data.status === 200) {
                        this.essayAnswers = response.data.data.data;
                        this.pagination.from = response.data.data.from;
                        this.pagination.to = response.data.data.to;
                        this.pagination.total = response.data.data.total;
                        this.pagination.per_page = response.data.data.per_page;
                        this.pagination.last_page = response.data.data.last_page;
                        this.pagination.current_page = response.data.data.current_page;
                    } else {
                        swal(response.data.msg);
                    }
                        this.$store.dispatch("disableLoading");
                }).catch(e => {
                    this.$store.dispatch("disableLoading");
                });
            },
            reviewAnswer: function (index) {
                let _this = this;

                if (this.essayAnswers[index].reviews.find(element => element.is_student && element.seen_at === null)) {
                    this.$store.dispatch("enableLoading");
                    axios.post(this.routes.admin.ESSAYS + '/review/seen', {ids: this.essayAnswers[index].reviews.map(element => element.id)})
                        .then(response => {
                            if (response.data.status === 200) {
                                if (response.data.errors) {
                                    _this.errors = response.data.errors;
                                } else {
                                    this.essayAnswers[index].reviews = response.data.data;
                                }
                            } else {
                                swal(response.data.msg);
                            }
                            _this.$store.dispatch("disableLoading");
                        }).catch(err => {
                        console.log(err.response);
                        swal(err.response);
                        _this.$store.dispatch("disableLoading");
                    });
                }

                let answer = this.essayAnswers[index].answer;
                answer = answer.replace('[{"insert":"\\n"}]', "");

                let converter = new QuillDeltaToHtmlConverter(
                    JSON.parse(answer)
                );

                answer = converter.convert();
                this.answer = answer;
                let reviews = this.essayAnswers[index].reviews;

                reviews.forEach(function (value, index, array) {
                    if (_this.IsJsonString(value.review)) {
                        value.review = value.review.replace('[{"insert":"\\n"}]', "");
                        let converter = new QuillDeltaToHtmlConverter(
                            JSON.parse(value.review)
                        );
                        value.review = converter.convert();
                    }
                });

                this.reviews = reviews;
                this.essay_review.essay_answer_id = this.essayAnswers[index].id;

                if (this.essayAnswers[index].is_closed) {
                    this.is_closed = 1;
                } else {
                    this.is_closed = 0;
                }

                $("#essayModal" + this.modal_index).modal('show');
            },
            store: function() {
                let _this = this;

                if (this.quill.editor.delta.ops[0].insert.trim() === '') {
                    this.errors = {
                        review: ['The review field is required.']
                    };
                    return false;
                } else {
                    this.errors = [];
                }

                this.$store.dispatch("enableLoading");
                this.essay_review.review = JSON.stringify(this.quill.getContents().ops);

                axios.post(this.routes.admin.ESSAYS + '/review', _this.essay_review)
                    .then(response => {
                        if (response.data.status === 200) {
                            if (response.data.errors) {
                                _this.errors = response.data.errors;
                            } else {
                                _this.quill.setContents('');
                                response.data.data.review = response.data.data.review.replace('[{"insert":"\\n"}]', "");
                                let converter = new QuillDeltaToHtmlConverter(
                                    JSON.parse(response.data.data.review)
                                );
                                response.data.data.review = converter.convert();
                                _this.reviews.push(response.data.data);
                                swal({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.data.msg,
                                });
                                $("#essayModal" + this.modal_index).modal('hide');
                            }
                        } else {
                            swal(response.data.msg);
                        }
                        _this.$store.dispatch("disableLoading");
                    }).catch(err => {
                    console.log(err.response);
                    swal(err.response);
                    _this.$store.dispatch("disableLoading");
                });
            },
            initQuillEditor() {
                let toolbarOptions = [
                    ["bold", "italic", "underline", "strike"],

                    [{
                        list: "ordered"
                    }, {
                        list: "bullet"
                    }],
                    [{
                        size: ["small", false, "large", "huge"]
                    }], // custom dropdown
                    [{
                        color: []
                    }], // dropdown with defaults from theme
                    [{
                        align: []
                    }],
                    ["clean"] // remove formatting button
                ];

                this.quill = new Quill("#review", {
                    theme: "snow",
                    modules: {
                        toolbar: toolbarOptions
                    }
                });
            },
            IsJsonString(str) {
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return true;
            },
            getUnseenReview: function(index) {
                return this.essayAnswers[index].reviews.filter(element => element.is_student && element.seen_at === null).length;
            },
            searchEssays: function() {
                if (this.search_key_word === '') {
                    this.getStudentEssaysAnswers(1);
                }
            },
            closeAnswer: function(id, index) {
                let _this = this;
                swal({
                    title: "Are you sure?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, close it!'
                }).then((ok) => {
                    if (ok) {
                        _this.$store.dispatch("enableLoading");
                        axios.post(_this.routes.admin.ESSAYS + '/answers/' + id)
                            .then(response => {
                                if (response.data.status === 200) {
                                    swal({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: response.data.msg,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                    _this.essayAnswers[index].is_closed = 1;
                                } else {
                                    swal(response.data.msg);
                                }
                                _this.$store.dispatch("disableLoading");
                            }).catch(err => {
                            console.log(err.response);
                            swal(err.response);
                            _this.$store.dispatch("disableLoading");
                        });
                    }
                });
            },
            recoverAnswer: function(id, index) {
                let _this = this;
                swal({
                    title: "Are you sure?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, recover it!'
                }).then((ok) => {
                    if (ok) {
                        _this.$store.dispatch("enableLoading");
                        axios.post(_this.routes.admin.ESSAYS + '/answers/' + id)
                            .then(response => {
                                if (response.data.status === 200) {
                                    swal({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: response.data.msg,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                    _this.essayAnswers[index].is_closed = 0;
                                } else {
                                    swal(response.data.msg);
                                }
                                _this.$store.dispatch("disableLoading");
                            }).catch(err => {
                            console.log(err.response);
                            swal(err.response);
                            _this.$store.dispatch("disableLoading");
                        });
                    }
                });
            },
        }
    };
</script>

<style lang="scss">
    .ql-container{
        min-height: 400px;
    }
</style>
