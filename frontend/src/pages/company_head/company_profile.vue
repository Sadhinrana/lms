<template>
    <div class="container-fluid page__container">
        <div class="text-center">
            <h1 class="h2 mb-0 mt-1">{{company_data.name}}</h1>
            <p class="lead text-muted mb-0">
        <span
                class="m-1"
                v-if="company_data.city"
        >{{ company_data.city }}</span>
                <span
                        class="m-1"
                        v-if="company_data.country"
                >{{ company_data.country }}</span>
            </p>
            <div class="badge badge-primary ">Company</div>
            <hr>

            <div class="card-group">

                <div class="card">
                    <div class="card-body text-center">
                        <div class="media-body">
                            <a class="text-body"><strong>Total Exams Taken</strong></a>
                        </div>
                        <span class="badge badge-info m-1" style="font-size: 16px;">{{ company_data.daily_quizzes_taken_count }} | Daily</span>
                        <span class="badge badge-primary m-1" style="font-size: 16px;">{{ company_data.monthly_quizzes_taken_count }} | Monthly</span>
                        <span class="badge badge-success m-1" style="font-size: 16px;">{{ company_data.yearly_quizzes_taken_count }} | Yearly</span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="text-muted mb-1">Company Rating</h5>
                        <div class="rating" v-for="from_average in get_average">
		    <span v-if="from_average.average >=85">
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            </span>
                            <span v-if="from_average.average >=75 && from_average.average <85">
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
			<i class="material-icons text-muted-light">star_border</i>
            </span>
                            <span v-if="from_average.average >=65 && from_average.average <75">
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
            </span>
                            <span v-if="from_average.average >=55 && from_average.average <65">
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
            </span>
                            <span v-if="from_average.average <=55">
            <i class="material-icons text-success">star</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
            </span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <div class="media-body">
                            <a class="text-body"><strong>Total Students Enrolled</strong></a>
                        </div>
                        <span class="badge badge-info m-1" style="font-size: 16px;">{{ company_data.daily_students_registered_count }} | Daily</span>
                        <span class="badge badge-primary m-1" style="font-size: 16px;">{{ company_data.monthly_students_registered_count }} | Monthly</span>
                        <span class="badge badge-success m-1" style="font-size: 16px;">{{ company_data.yearly_students_registered_count }} | Yearly</span>
                    </div>
                </div>

            </div>
        </div>
        <hr>
        <h4>Teachers by {{company_data.name}}</h4>
        <div class="card">
            <div class="card-body">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Total Students</th>
                        <th>GPA</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="teacher in company_teacher">
                        <td>{{teacher.first_name}} {{teacher.last_name}}</td>
                        <td>{{teacher.student_count}}</td>
                        <td>{{teacher.gpa}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <h4>Courses by {{company_data.name}}</h4>
        <div class="card-columns">
            <div
                    class="card"
                    :key="course_company.id"
                    v-for="course_company in company_courses"
            >
                <div class="card-header">
                    <div class="media align-items-center">
                        <div class="media-left">
                            <!--<router-link :to="{ name: 'instructor_edit_course' }">-->
                            <img
                                    :src="routes.server_api + course_company.course.course_image"
                                    alt="Card image cap"
                                    width="100"
                                    class="rounded"
                                    v-if="course_company.course.course_image"
                            >
                            <img
                                    :src="routes.server_api+ '/img/default.png'"
                                    alt="Card image cap"
                                    width="100"
                                    class="rounded"
                                    v-else
                            >
                            <!--</router-link>-->
                        </div>
                        <div class="media-body">
                            <h4 class="card-title mb-0">{{course_company.course.title}}
                                <!--<router-link :to="{ name: 'instructor_edit_course' }">{{course_company.course.title}}</router-link>-->
                            </h4>
                        </div>
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
                get_average: "",
                company_data: {
                    name: "",
                    address: "",
                    description: ""
                },
                company_courses: [],
                company_teacher: [],
                idcompany: ""
            };
        },
        computed: {
            user_info() {
                return this.$store.getters.authUser;
            }
        },
        mounted() {
            this.fetchCompanies();
            this.getStudentData();
            this.getCompanyTeacher();
            this.getStudentEssaysAnswers();
        },
        methods: {
            fetchCompanies() {
                var self = this;
                // Only run if authUser is fetched
                if (this.user_info.id) {
                    axios
                        .get(
                            this.routes.company.SHOW_COMPANY + "/" + self.user_info.company_id
                        )
                        .then(res => {
                            this.company_data = res.data;
                            this.company_courses = res.data.company_courses;
                            this.getStudentData();
                        })
                        .catch(err => {
                            console.log(err);
                        });


                    axios
                        .get(
                            self.routes.company.GET_STUDENT_RECORD + "/" + self.user_info.company_id
                        )
                        .then(res => {
                            self.get_average = res.data;
                            console.log(self.get_average);
                        })
                        .catch(err => {
                            console.log(err);
                        });
                } else {
                    //If this is the first time user visit page, wait until authUser is fetched
                    eventBus.$on("authUserFetched", function () {
                        axios
                            .get(
                                self.routes.company.SHOW_COMPANY + "/" + self.user_info.company_id
                            )
                            .then(res => {
                                self.company_data = res.data;
                                self.company_courses = res.data.company_courses;
                            })
                            .catch(err => {
                                console.log(err);
                            });
                    });
                }
            },
            getStudentData() {
                var self = this;
                eventBus.$on("authUserFetched", function () {

                    axios
                        .get(
                            self.routes.company.GET_STUDENT_RECORD + "/" + self.user_info.company_id
                        )
                        .then(res => {
                            self.get_average = res.data;
                            console.log(self.get_average);
                        })
                        .catch(err => {
                            console.log(err);
                        });


                });
            },
            getCompanyTeacher() {
                var self = this;
                if (this.user_info.id) {
                    axios
                        .post(
                            self.routes.company.TEACHER_LIST, {company_id: self.user_info.company_id}
                        )
                        .then(res => {
                            self.company_teacher = res.data;
                        })
                        .catch(err => {
                            console.log(err);
                        });
                } else {
                    eventBus.$on("authUserFetched", function () {
                        axios
                            .post(
                                self.routes.company.TEACHER_LIST, {company_id: self.user_info.company_id}
                            )
                            .then(res => {
                                self.company_teacher = res.data;
                            })
                            .catch(err => {
                                console.log(err);
                            });
                    });
                }
            },
            getStudentEssaysAnswers() {
                let _this =this;
                this.$store.dispatch("enableLoading");

                axios
                    .get(
                        this.routes.admin.ESSAYS_ANSWER_BY_TEACHER
                    ).then(response => {
                    if (response.data.status === 200) {
                        if (response.data.data) {
                            swal({
                                title: "Review",
                                text: 'You have ' + response.data.data + ' new essay answer(s) to review.',
                                icon: "info",
                            }).then((ok) => {
                                if (ok) {
                                    _this.$router.push({ name: 'essay_manage' });
                                }
                            });
                        }
                    } else {
                        swal(response.data.msg);
                    }
                    this.$store.dispatch("disableLoading");
                }).catch(e => {
                    this.$store.dispatch("disableLoading");
                });
            },
        }
    };
</script>

<style>
</style>
