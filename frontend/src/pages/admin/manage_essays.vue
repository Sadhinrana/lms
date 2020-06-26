<template>
    <div class="page">
        <div class="container page__container">
            <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
                <div class="flex mb-2 mb-sm-0">
                    <h1 class="h2">Essay Manager</h1>
                </div>
                <a href="javascript:void(0)" class="btn btn-success" @click="showForm(false)">Add essay</a>
            </div>

            <div class="card card-body border-left-3 border-left-primary navbar-shadow mb-4">
                <form action="#" @submit.prevent="getEssays(1)">
                    <div class="d-flex flex-wrap2 align-items-center mb-headings">
                        <div class="flex search-form ml-3 search-form--light">
                            <input type="text" class="form-control" placeholder="Search essays" id="searchSample02" v-model="search_key_word">
                            <button class="btn" type="button" role="button" @click="getEssays(1)"><i class="material-icons">search</i></button>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center" style="white-space: nowrap;">
                        <small class="flex text-muted text-uppercase mr-3 mb-2 mb-sm-0">Displaying {{pagination.from}} to {{pagination.to}} out of {{pagination.total}} essays</small>
                    </div>
                </form>
            </div>

            <div class="card" v-if="essays.length">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg">
                            <div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Question</th>
                                        <th>Type</th>
                                        <th>Course</th>
                                        <th v-if="authUser.role === 'headmaster'">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list">
                                    <tr class="selected" v-for="essay in essays" :key="essay.id">
                                        <td>{{ essay.title }}</td>
                                        <td>{{ essay.question }}</td>
                                        <td>{{ essay.type === 'unit' ? 'Unit' : 'Midterm and End of course' }}</td>
                                        <td>{{ essay.course.title }}</td>
                                        <td v-if="authUser.role === 'headmaster'">
                                            <a href="javascript:void(0)" class="text-muted" data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" @click.prevent="showForm(true, essay.id)" class="dropdown-item">Edit</a>
                                                <a href="javascript:void(0)" @click.prevent="deleteEssay(essay.id)" class="dropdown-item">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="alert alert-light alert-dismissible border-1 border-left-3 border-left-warning" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-black-70">No results found.</div>
            </div>

            <!-- Pagination -->
            <ul class="pagination justify-content-center pagination-sm" v-if="essays.length">
                <li v-if="pagination.current_page>1" class="page-item">
                    <a @click.prevent="getEssays(pagination.current_page - 1)" class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true" class="material-icons">chevron_left</span>
                        <span>Prev</span>
                    </a>
                </li>

                <li :key="page" v-for="page in pagesNumber" :class="[page === pagination.current_page ? 'active' : null , 'page-item']">
                    <a href="#" v-on:click.prevent="getEssays(page)" class="page-link" aria-label="Previous" >{{ page }}</a>
                </li>

                <li v-if="pagination.current_page < pagination.last_page">
                    <a href="javascript:void(0)" aria-label="Next" v-on:click.prevent="getEssays(pagination.current_page + 1)">
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true" class="material-icons">chevron_right</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- modal-->
        <div class="modal fade" :id="'essayModal' + modal_index">
            <div class="modal-dialog modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">{{ isEdit ? 'Edit' : 'Add' }} essay</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="#" @submit.prevent="addOrUpdateEssay">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Title:</label>
                                <div class="col-sm-9">
                                    <input type="text" :class="{'form-control' : true, 'is-invalid' : errors.title}" v-model="essay.title" placeholder="Title" required>

                                    <div class="invalid-feedback" v-if="errors.title">
                                        {{ errors.title[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Question:</label>
                                <div class="col-sm-9">
                                    <textarea rows="10" :class="{'form-control' : true, 'is-invalid' : errors.question}" v-model="essay.question" placeholder="Question" required></textarea>

                                    <div class="invalid-feedback" v-if="errors.question">
                                        {{ errors.question[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Type:</label>
                                <div class="col-sm-9">
                                    <select :class="{'form-control' : true, 'is-invalid' : errors.type}" v-model="essay.type" required>
                                        <option value="0" disabled>Choose type</option>
                                        <option value="unit">Unit essay</option>
                                        <option value="midterm_end_course">Midterm and End of course essay</option>
                                    </select>

                                    <div class="invalid-feedback" v-if="errors.type">
                                        {{ errors.type[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Course:</label>
                                <div class="col-sm-9">
                                    <select :class="{'form-control' : true, 'is-invalid' : errors.course_id}" v-model="essay.course_id" required>
                                        <option value="0" disabled>Choose course</option>
                                        <option :value="course.id" v-for="course in courses" :key="course.id">{{ course.title }}</option>
                                    </select>

                                    <div class="invalid-feedback" v-if="errors.course_id">
                                        {{ errors.course_id[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
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
    export default {
        data() {
            return {
                essay: {
                    title: '',
                    question: '',
                    type: '0',
                    course_id: '0',
                },
                courses: [],
                essays: [],
                offset: 4,
                isEdit: false,
                search_key_word: "",
                errors: [],
                modal_index: 1,
                pagination: {
                    total: 0,
                    per_page: 10,
                    from: 0,
                    to: 0,
                    current_page: 1
                },
            };
        },
        computed: {
            authUser: function () {
                return this.$store.getters.authUser
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
        created() {
            console.log(this.authUser);
        },
        mounted() {
            this.init();
            this.getEssays(this.pagination.current_page);
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
            getEssays(page) {
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.admin.ESSAYS, {
                        params: {
                            keyword: this.search_key_word,
                            page: page
                        }
                    })
                    .then(response => {
                        if (response.data.status === 200) {
                            this.courses = response.data.data.courses;
                            this.essays = response.data.data.essays.data;
                            this.pagination.from = response.data.data.essays.from;
                            this.pagination.to = response.data.data.essays.to;
                            this.pagination.total = response.data.data.essays.total;
                            this.pagination.per_page = response.data.data.essays.per_page;
                            this.pagination.last_page = response.data.data.essays.last_page;
                            this.pagination.current_page = response.data.data.essays.current_page;
                        } else {
                            swal(response.data.msg);
                        }
                        this.$store.dispatch("disableLoading");
                    }).catch(err => {
                    console.log(err.response);
                    swal(err.response);
                    this.$store.dispatch("disableLoading");
                });
            },
            showForm: function(isEdit, id) {
                if (isEdit) {
                    this.isEdit = true;
                    this.$store.dispatch("enableLoading");
                    axios
                        .get(this.routes.admin.ESSAYS + '/' + id + '/edit')
                        .then(response => {
                            if (response.data.status === 200) {
                                this.essay = response.data.data;
                            } else {
                                swal(response.data.msg);
                            }
                            this.$store.dispatch("disableLoading");
                        }).catch(err => {
                        console.log(err.response);
                        swal(err.response);
                        this.$store.dispatch("disableLoading");
                    });
                } else {
                    this.isEdit = false;
                    this.essay = {
                        title: '',
                        question: '',
                        type: '0',
                        course_id: '0',
                    };
                }

                $("#essayModal" + this.modal_index).modal("show");
            },
            addOrUpdateEssay: function() {
                let _this = this;
                this.$store.dispatch("enableLoading");

                if (this.isEdit) {
                    let params = this.essay;
                    params._method = 'put';
                    axios.post(_this.routes.admin.ESSAYS + '/' + this.essay.id, params)
                        .then(response => {
                            if (response.data.status === 200) {
                                if (response.data.errors) {
                                    _this.errors = response.data.errors;
                                } else {
                                    $("#essayModal" + this.modal_index).modal("hide");

                                    swal({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: response.data.msg,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        _this.getEssays(this.pagination.current_page);
                                    });
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
                } else {
                    axios.post(_this.routes.admin.ESSAYS, this.essay)
                        .then(response => {
                            if (response.data.status === 200) {
                                if (response.data.errors) {
                                    _this.errors = response.data.errors;
                                } else {
                                    $("#essayModal" + this.modal_index).modal("hide");

                                    swal({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: response.data.msg,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        _this.getEssays(this.pagination.current_page);
                                    });
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
            },
            deleteEssay(id) {
                let _this = this;
                swal({
                    title: "Are you sure?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((ok) => {
                    if (ok) {
                        _this.$store.dispatch("enableLoading");
                        axios.post(_this.routes.admin.ESSAYS + '/' + id, {
                            _method: 'delete',
                        })
                            .then(response => {
                                if (response.data.status === 200) {
                                    swal({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: response.data.msg,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        _this.getEssays(this.pagination.current_page);
                                    });
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
            }
        }
    };
</script>
