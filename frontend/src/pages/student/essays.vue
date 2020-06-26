<template>
    <div class="container-fluid page__container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Essays</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 py-2" v-for="course in courses" :key="course.id" @click="getCourseEssays(course.id)">
                                <button class="btn btn-success" style="width:100% !important">
                                    <div class="rotate">
                                        <i style="font-size: 40px;"
                                           class="sidebar-menu-icon sidebar-menu-icon--left material-icons">school</i>
                                    </div>
                                    <h6 class="text-uppercase">{{course.title}}</h6>
                                </button>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <ul class="card list-group list-group-fit">
                                    <li class="list-group-item" v-for="(essay, index) in essays" :key="index">
                                        <div class="media">
                                            <div class="media-left">
                                                <div class="text-muted">{{index + 1}}.</div>
                                            </div>

                                            <div class="media-body">
                                                <a href="javascript:void(0)" @click="showForm(essay.id)" v-if="!essay.answers.length || !essay.answers[0].is_submitted || (!essay.answers[0].is_closed && essay.answers[0].reviews.length)">{{ essay.title }}</a>
                                                <a href="javascript:void(0)" v-else="">{{ essay.title }}</a>
                                            </div>

                                            <div class="media-right">
                                                <a href="javascript:void(0)" class="btn btn-success" @click="showForm(essay.id)" v-if="!essay.answers.length || !essay.answers[0].is_submitted || (!essay.answers[0].is_closed && essay.answers[0].reviews.length)">Write an Essay</a>
                                                <i class="material-icons text-muted-light" v-else>lock_open</i>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal-->
        <div class="modal fade" :id="'essayModal' + modal_index">
            <div class="modal-dialog modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">Write an Essay</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="#" @submit.prevent="store(false)">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Title:</label>
                                <div class="col-sm-9">
                                    <p>{{ essay.title }}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Question:</label>
                                <div class="col-sm-9">
                                    <p>{{ essay.question }}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Answer:</label>
                                <div class="col-sm-9">
                                    <div class="quill-outer">
                                        <div id="answer" v-model="quill" @keyup="onEditorChange" style="height: 150px;" data-toggle="quill" data-quill-placeholder="Answer">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="invalid-feedback d-block" v-if="errors.answer">
                                        {{ errors.answer[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-5">
                                    <h5>Word Count: {{ wordCount }}</h5>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-success float-right">Save</button>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-warning float-right" @click.prevent="store(true)">Submit</button>
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
    export default {
        data() {
            return {
                essay: {
                    id: '',
                    title: '',
                    question: '',
                    answer: '',
                    type: '',
                    course_id: '',
                },
                essayAnswer: {
                    answer: '',
                    is_submitted: 0,
                    essay_id: '',
                },
                quill: null,
                courses: [],
                essays: [],
                errors: [],
                modal_index: 1,
                wordCount: 0,
            };
        },
        computed: {
            user_info() {
                return this.$store.getters.authUser;
            }
        },
        mounted() {
            this.init();
            this.fetchCourse();
            this.initQuillEditor();
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
            fetchCourse() {
                this.$store.dispatch("enableLoading");
                var self = this;
                // Only run if authUser is fetched
                if (self.user_info.id) {
                    axios
                        .get(
                            self.routes.course.GET_COURSE_BY_STUDENT + "/" + self.user_info.id
                        ).then(response => {
                            self.courses = response.data.data;
                            self.$store.dispatch("disableLoading");
                    }).catch(e => {
                        self.$store.dispatch("disableLoading");
                    });
                } else {
                    //If this is the first time user visit page, wait until authUser is fetched
                    eventBus.$on("authUserFetched", function () {
                        axios
                            .get(
                                self.routes.course.GET_COURSE_BY_STUDENT + "/" + self.user_info.id
                            ).then(response => {
                                self.courses = response.data.data;
                                self.$store.dispatch("disableLoading");
                        }).catch(e => {
                            self.$store.dispatch("disableLoading");
                        });
                    });
                }
            },
            getCourseEssays(course_id) {
                this.$store.dispatch("enableLoading");
                axios
                    .get(
                        this.routes.course.ESSAYS + "/" + course_id
                    ).then(response => {
                    if (response.data.status === 200) {
                        this.essays = response.data.data;
                    } else {
                        swal(response.data.msg);
                    }
                        this.$store.dispatch("disableLoading");
                }).catch(e => {
                    this.$store.dispatch("disableLoading");
                });
            },
            showForm: function(id) {
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.admin.ESSAYS + '/' + id + '/edit')
                    .then(response => {
                        if (response.data.status === 200) {
                            this.essay = response.data.data;

                            if (this.essay.answers.length) {
                                this.essayAnswer = this.essay.answers[0];
                                this.quill.setContents(JSON.parse(this.essayAnswer.answer));
                            } else {
                                this.essayAnswer.essay_id = this.essay.id;
                            }
                        } else {
                            swal(response.data.msg);
                        }
                        this.$store.dispatch("disableLoading");
                    }).catch(err => {
                    console.log(err.response);
                    swal(err.response);
                    this.$store.dispatch("disableLoading");
                });

                $("#essayModal" + this.modal_index).modal("show");
            },
            store: function(submit) {
                let _this = this;

                if (this.quill.editor.delta.ops[0].insert.trim() === '') {
                    this.errors = {
                        answer: ['The answer field is required.']
                    };
                    return false;
                } else {
                    this.errors = [];
                }

                this.$store.dispatch("enableLoading");
                this.essayAnswer.answer = JSON.stringify(this.quill.getContents().ops);
                if (submit) {
                    this.essayAnswer.is_submitted = 1;
                }

                axios.post(this.routes.admin.ESSAYS + '/' + _this.essay.id + '/answer', _this.essayAnswer)
                    .then(response => {
                        if (response.data.status === 200) {
                            if (response.data.errors) {
                                _this.errors = response.data.errors;
                            } else {
                                _this.quill.setContents('');
                                $("#essayModal" + this.modal_index).modal("hide");
                                if (submit) {
                                    _this.getCourseEssays(_this.essay.course_id);
                                    swal({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: response.data.msg,
                                    });
                                }
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

                this.quill = new Quill("#answer", {
                    theme: "snow",
                    modules: {
                        toolbar: toolbarOptions
                    }
                });
            },
            onEditorChange() {
                this.wordCount = this.quill.editor.delta.ops[0].insert.trim().split(' ').length;
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
    .ql-container{
        min-height: 400px;
    }
</style>
