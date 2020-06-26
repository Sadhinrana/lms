<template>
    <div class="page">
        <div class="container page__container">
            <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
                <div class="flex mb-2 mb-sm-0">
                    <h1 class="h2">Live Lessons</h1>
                </div>
                <a href="javascript:void(0)" class="btn btn-success" @click="showForm(false)">Add session</a>
            </div>

            <div class="card card-body border-left-3 border-left-primary navbar-shadow mb-4">
                <form action="#" @submit.prevent="getSessions(1)">
                    <div class="d-flex flex-wrap2 align-items-center mb-headings">
                        <div class="flex search-form ml-3 search-form--light">
                            <input type="text" class="form-control" placeholder="Search sessions" id="searchSample02" v-model="search_key_word">
                            <button class="btn" type="button" role="button" @click="getSessions(1)"><i class="material-icons">search</i></button>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center" style="white-space: nowrap;">
                        <small class="flex text-muted text-uppercase mr-3 mb-2 mb-sm-0">Displaying {{pagination.from}} to {{pagination.to}} out of {{pagination.total}} sessions</small>
                    </div>
                </form>
            </div>

            <div class="card" v-if="sessions.length">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg">
                            <div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>Week days</th>
                                        <th>Time</th>
                                        <th>Group</th>
                                        <th>Study mode</th>
                                        <th>Online Class Link</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list">
                                    <tr class="selected" v-for="session in sessions" :key="session.id">
                                        <td>{{ session.week_day_string }}</td>
                                        <td>{{ session.time }}</td>
                                        <td>{{ session.student_group ? session.student_group.title : '' }}</td>
                                        <td>{{ session.study_mode }}</td>
                                        <td>{{ session.bbb_room_link }}</td>
                                        <td>
                                            <a href="javascript:void(0)" class="text-muted" data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" @click.prevent="showForm(true, session.id)" class="dropdown-item">Edit</a>
                                                <a href="javascript:void(0)" @click.prevent="deleteSession(session.id)" class="dropdown-item">Delete</a>
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
            <ul class="pagination justify-content-center pagination-sm" v-if="sessions.length">
                <li v-if="pagination.current_page>1" class="page-item">
                    <a @click.prevent="getSessions(pagination.current_page - 1)" class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true" class="material-icons">chevron_left</span>
                        <span>Prev</span>
                    </a>
                </li>

                <li :key="page" v-for="page in pagesNumber" :class="[page === pagination.current_page ? 'active' : null , 'page-item']">
                    <a href="#" v-on:click.prevent="getSessions(page)" class="page-link" aria-label="Previous" >{{ page }}</a>
                </li>

                <li v-if="pagination.current_page < pagination.last_page">
                    <a href="javascript:void(0)" aria-label="Next" v-on:click.prevent="getSessions(pagination.current_page + 1)">
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true" class="material-icons">chevron_right</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- modal-->
        <div class="modal fade" :id="'sessionModal' + modal_index">
            <div class="modal-dialog modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">{{ isEdit ? 'Edit' : 'Add' }} session</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="#" @submit.prevent="addOrUpdateSession">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Week days:</label>

                                <div class="col-sm-9">
                                    <v-select
                                            multiple
                                            :reduce="week_day => week_day.code"
                                            :class="{'is-invalid' : errors.week_day}"
                                            :options="options"
                                            v-model="session.week_day"
                                            placeholder="Choose week days"
                                    />

                                    <div class="invalid-feedback d-block" v-if="errors.week_day">
                                        {{ errors.week_day[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Students:</label>

                                <div class="col-sm-9">
                                    <v-select
                                            multiple
                                            label="full_name"
                                            :reduce="student => student.id"
                                            :class="{'is-invalid' : errors.students}"
                                            :options="students"
                                            v-model="session.students"
                                            placeholder="Choose students"
                                    />

                                    <div class="invalid-feedback d-block" v-if="errors.students">
                                        {{ errors.students[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Time:</label>

                                <div class="col-sm-9">
                                    <input type="time" :class="{'form-control' : true, 'is-invalid' : errors.time}" v-model="session.time" required>

                                    <div class="invalid-feedback" v-if="errors.time">
                                        {{ errors.time[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Study Mode:</label>

                                <div class="col-sm-9">
                                    <select class="form-control custom-select" v-model="session.study_mode" @change="checkStudyMode()" required>
                                        <option value="0" disabled="disabled">Select Study Mode</option>
                                        <option value="One to One">One to One</option>
                                        <option value="Group Two">Group Two</option>
                                        <option value="Group Three">Group Three</option>
                                    </select>

                                    <div class="invalid-feedback" v-if="errors.study_mode">
                                        {{ errors.study_mode[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row" v-if="(session.study_mode === 'Group Two' || session.study_mode === 'Group Three')">
                                <label class="col-sm-3 col-form-label form-label">Group:</label>

                                <div class="col-sm-9">
                                    <select class="form-control custom-select" v-model="session.student_group_id">
                                        <option value="99" disabled="disabled">Select Group</option>
                                        <option :value="group.id" v-for="group in groups" :key="group.id">{{ group.title }}</option>
                                    </select>

                                    <div class="invalid-feedback" v-if="errors.study_mode">
                                        {{ errors.study_mode[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label form-label">Online Class Link:</label>
                                <div class="col-sm-9">
                                    <input type="text" :class="{'form-control' : true, 'is-invalid' : errors.bbb_room_link}" v-model="session.bbb_room_link" placeholder="Online Class Link" required>

                                    <div class="invalid-feedback" v-if="errors.bbb_room_link">
                                        {{ errors.bbb_room_link[0] }}
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
    import Vue from 'vue'
    import vSelect from 'vue-select'
    import 'vue-select/dist/vue-select.css';

    Vue.component('v-select', vSelect)

    export default {
        data() {
            return {
                options: [
                    {
                        label: 'Sunday',
                        code: '0'
                    },
                    {
                        label: 'Monday',
                        code: '1'
                    },
                    {
                        label: 'Tuesday',
                        code: '2'
                    },
                    {
                        label: 'Wednesday',
                        code: '3'
                    },
                    {
                        label: 'Thursday',
                        code: '4'
                    },
                    {
                        label: 'Friday',
                        code: '5'
                    },
                    {
                        label: 'Saturday',
                        code: '6'
                    },
                ],
                students: [],
                session: {
                    week_day: [],
                    students: [],
                    time: '',
                    student_group_id: '99',
                    study_mode: '0',
                    bbb_room_link: '',
                },
                sessions: [],
                groups: [],
                isEdit: false,
                search_key_word: "",
                errors: [],
                modal_index: 1,
                offset: 4,
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
            this.getGroups();
            this.getStudentsList();
            this.getSessions(this.pagination.current_page);
        },
        methods: {
            init() {
                let _this = this;
                //----This line bellow is one of the best thing I've ever created ! Ever ! :)) -----//
                //Append the modal element to another div outside of this file (located in App.vue) :
                $("#modelDestination").empty();
                $("#sessionModal" + this.modal_index).appendTo("#modelDestination");

                if (this.$session.get("sessionModal")) {
                    if (this.$session.get("sessionModal") != "sessionModal" + this.modal_index) {
                        var check_modal = this.$session.get("sessionModal");
                        $("#" + check_modal).remove();
                    }
                }

                $("#sessionModal" + this.modal_index).on("hidden.bs.modal", function (e) {
                    $("#sessionModal" + _this.modal_index).modal("hide");
                    _this.modal_index++;
                    var modal_id = "sessionModal" + _this.modal_index;
                    _this.$session.set("sessionModal", modal_id);
                    $("#sessionModal" + _this.modal_index).remove();
                });
            },
            getSessions(page) {
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.lesson.SESSIONS, {
                        params: {
                            keyword: this.search_key_word,
                            page: page
                        }
                    })
                    .then(response => {
                        if (response.data.status === 200) {
                            this.sessions = response.data.data.data;
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
                    }).catch(err => {
                    console.log(err.response);
                    swal(err.response);
                    this.$store.dispatch("disableLoading");
                });
            },
            getGroups() {
                var self = this;

                axios
                    .get(
                        self.routes.company.GROUPS,
                    )
                    .then(res => {
                        self.groups = res.data.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            getStudentsList() {
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.admin.GET_STUDENT_LIST_COMPANY_BASED)
                    .then(response => {
                        if (response.data.status === 200) {
                            this.students = response.data.data;
                        } else {
                            swal(response.data.msg);
                        }

                        this.$store.dispatch("disableLoading");
                    }).catch(err => {
                    console.log(err.response);
                    this.$store.dispatch("disableLoading");
                });
            },
            showForm: function(isEdit, id) {
                this.errors = [];

                if (isEdit) {
                    this.isEdit = true;
                    this.$store.dispatch("enableLoading");
                    axios
                        .get(this.routes.lesson.SESSIONS + '/' + id + '/edit')
                        .then(response => {
                            if (response.data.status === 200) {
                                this.session = response.data.data;
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
                    this.session = {
                        week_day: [],
                        students: [],
                        time: '',
                        student_group_id: '99',
                        study_mode: '0',
                        bbb_room_link: '',
                    };
                }

                $("#sessionModal" + this.modal_index).modal("show");
            },
            checkStudyMode: function() {
                if(this.session.study_mode === 'One to One') {
                    this.session.student_group_id = 99;
                }
            },
            addOrUpdateSession: function() {
                let _this = this;
                this.$store.dispatch("enableLoading");

                if (this.isEdit) {
                    let params = this.session;
                    this.session.students = this.session.students.map(item => item.id);
                    params._method = 'put';

                    axios.post(_this.routes.lesson.SESSIONS + '/' + this.session.id, params)
                        .then(response => {
                            if (response.data.status === 200) {
                                if (response.data.errors) {
                                    _this.errors = response.data.errors;
                                } else {
                                    _this.errors = [];
                                    $("#sessionModal" + this.modal_index).modal("hide");

                                    swal({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: response.data.msg,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        _this.getSessions(this.pagination.current_page);
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
                    axios.post(_this.routes.lesson.SESSIONS, this.session)
                        .then(response => {
                            if (response.data.status === 200) {
                                if (response.data.errors) {
                                    _this.errors = response.data.errors;
                                } else {
                                    _this.errors = [];
                                    $("#sessionModal" + this.modal_index).modal("hide");

                                    swal({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: response.data.msg,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        _this.getSessions(this.pagination.current_page);
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
            deleteSession(id) {
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
                        axios.post(_this.routes.lesson.SESSIONS + '/' + id, {
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
                                        _this.getSessions(this.pagination.current_page);
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
