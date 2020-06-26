<template>
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__container">
            <h1 class="h2">Dashboard</h1>

            <div class="row mb-3">
                <div class="col-md-4 col-sm-6 py-2">
                    <router-link :to="{ name: 'student_my_courses'}">

                        <button class="btn btn-success" style="width:100% !important">
                            <div class="rotate">
                                <i style="font-size: 40px;"
                                   class="sidebar-menu-icon sidebar-menu-icon--left material-icons">school</i>
                            </div>
                            <h6 class="text-uppercase">My Courses</h6>
                        </button>

                    </router-link>
                </div>

                <div class="col-md-4 col-sm-6 py-2">
                    <router-link :to="{ name: 'student_attendance'}">

                        <button class="btn btn-primary" style="width:100% !important">
                            <div class="rotate">
                                <i style="font-size: 40px;"
                                   class="sidebar-menu-icon sidebar-menu-icon--left material-icons">school</i>
                            </div>
                            <h6 class="text-uppercase">Attendance</h6>
                        </button>

                    </router-link>
                </div>

                <div class="col-md-4 col-sm-6 py-2">
                    <router-link :to="{ name: 'my_quizzes'}">
                        <button class="btn btn-warning" style="width:100% !important">
                            <div class="rotate">
                                <i style="font-size: 40px;"
                                   class="sidebar-menu-icon sidebar-menu-icon--left material-icons">poll</i>
                            </div>
                            <h6 class="text-uppercase">Exam Results</h6>
                        </button>
                    </router-link>
                </div>


                <div class="col-md-4 col-sm-6 py-2">
                    <router-link :to="{ name: 'take_listening_student'}">
                        <button class="btn btn-primary" style="width:100% !important">
                            <div class="rotate">
                                <i style="font-size: 40px;"
                                   class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_box</i>
                            </div>
                            <h6 class="text-uppercase">Listening</h6>
                        </button>
                    </router-link>
                </div>

                <div class="col-md-4 col-sm-6 py-2">
                    <router-link :to="{ name: 'student_edit_profile'}">
                        <button class="btn btn-info" style="width:100% !important">
                            <div class="rotate">
                                <i style="font-size: 40px;"
                                   class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_box</i>
                            </div>
                            <h6 class="text-uppercase">Edit Account</h6>
                        </button>
                    </router-link>
                </div>

                <div class="col-md-4 col-sm-6 py-2">
                    <router-link :to="{ name: 'student_essays'}">
                        <button class="btn btn-success" style="width:100% !important">
                            <div class="rotate">
                                <i style="font-size: 40px;"
                                   class="sidebar-menu-icon sidebar-menu-icon--left material-icons">school</i>
                            </div>
                            <h6 class="text-uppercase">Essays</h6>
                        </button>
                    </router-link>
                </div>

                <div class="col-md-4 col-sm-6 py-2">
                    <router-link :to="{ name: 'logout'}">
                        <button class="btn btn-danger" style="width:100% !important">
                            <div class="rotate">
                                <i style="font-size: 40px;"
                                   class="sidebar-menu-icon sidebar-menu-icon--left material-icons">lock_open</i>
                            </div>
                            <h6 class="text-uppercase">Logout</h6>
                        </button>
                    </router-link>
                </div>

                <div class="col-md-4 col-sm-6 py-2"></div>
            </div>

            <div class="row" style="width:25%;"></div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getStudentEssaysAnswers();
        },
        methods: {
            getStudentEssaysAnswers() {
                let _this = this;
                this.$store.dispatch("enableLoading");

                axios
                    .get(
                        this.routes.admin.ESSAYS_ANSWER
                    ).then(response => {
                    if (response.data.status === 200) {
                        let count = 0;

                        response.data.data.forEach(function (value) {
                            count += value.reviews.filter(element => !element.is_student && element.seen_at === null).length;
                        });

                        if (count) {
                            swal({
                                title: "Review",
                                text: 'You have ' + count + ' new essay review(s).',
                                icon: "info",
                            }).then((ok) => {
                                if (ok) {
                                    _this.$router.push({ name: 'student_essays_reviews' });
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

<style lang="scss">

    @media (min-width: 576px) {
        .mycard {
            flex: 5 0 25% !important;
            max-width: 100% !important;
        }
    }
</style>
