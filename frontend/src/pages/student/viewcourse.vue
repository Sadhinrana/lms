<template>
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__container">
            <!-- <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'home'}">Home</router-link>
              </li>
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'student_browse_courses'}">
                  Courses
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ course_content.title }}</li>
            </ol> -->
            <h1 class="h2">{{ course_content.title }}</h1>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe
                                    class="embed-responsive-item"
                                    :src="course_content.video_link"
                                    allowfullscreen=""
                            ></iframe>
                        </div>
                        <div
                                v-html="description"
                                class="card-body"
                        >
                        </div>
                    </div>

                    <!-- Lessons -->
                    <ul class="card list-group list-group-fit">
                        <li
                                class="list-group-item"
                                v-for="(lesson,itemObjKey) in lesson_list"
                        >
                            <div class="media">
                                <div class="media-left">
                                    <div class="text-muted">{{itemObjKey + 1}}.</div>
                                </div>
                                <div class="media-body">
                                    <router-link
                                            :to="{ name: 'take_course', params:{idcourse:lesson.course_id, idlesson:lesson.id}}">
                                        {{lesson.title}}
                                    </router-link>
                                </div>
                                <div class="media-right">
                                    <small class="text-muted-light">2:03</small>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
                <div class="col-md-4">
                    <div
                            class="card"
                            v-if="course_content.student_course && course_content.student_course.is_rejected"
                    >
                        <div class="card-body text-center">
                            <p>
                                <a class="btn btn-disabled btn-block flex-column ">
                                    {{request_text}}
                                </a>
                            </p>
                        </div>

                    </div>

                    <div
                            class="card"
                            v-else-if="course_content.student_course && !course_content.student_course.is_approved && !course_content.student_course.is_rejected"
                    >
                        <div class="card-body text-center">
                            <p>
                                <a
                                        href="#"
                                        @click.prevent=""
                                        class="btn btn-info btn-block flex-column "
                                >
                                    {{request_text}}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div
                            class="card"
                            v-else-if="!course_content.student_course"
                    >
                        <div class="card-body text-center">
                            <p>
                                <a
                                        href="#"
                                        @click.prevent="applyCourse"
                                        class="btn btn-success btn-block flex-column "
                                >
                                    {{request_text}}
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- <div class="card">
                      <div class="card-header">
                        <div class="media align-items-center">
                          <div class="media-left">
                            <img
                              src="@/assets/images/people/110/guy-6.jpg"
                              alt="About Adrian"
                              width="50"
                              class="rounded-circle"
                            >
                          </div>
                          <div class="media-body">
                            <h4 class="card-title"><a href="student-profile.html">Adrian Demian</a></h4>
                            <p class="card-subtitle">Instructor</p>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <p>Having over 12 years exp. Adrian is one of the lead UI designers in the industry Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere, aut.</p>
                        <a
                          href=""
                          class="btn btn-default"
                        >
                          <i class="fa fa-facebook"></i>
                        </a>
                        <a
                          href=""
                          class="btn btn-default"
                        >
                          <i class="fa fa-twitter"></i>
                        </a>
                        <a
                          href=""
                          class="btn btn-default"
                        ><i class="fa fa-github"></i></a>
                      </div>
                    </div> -->
                    <div class="card">
                        <ul class="list-group list-group-fit">
                            <li class="list-group-item">
                                <div class="media align-items-center">
                                    <div class="media-left">
                                        <i class="material-icons text-muted-light">assessment</i>
                                    </div>
                                    <div class="media-body">
                                        Beginner
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="media align-items-center">
                                    <div class="media-left">
                                        <i class="material-icons text-muted-light">schedule</i>
                                    </div>
                                    <div class="media-body">
                                        {{ course_content.duration }} <small class="text-muted">minutes</small>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="media align-items-center">
                                    <div class="media-left">
                                        <i class="material-icons text-muted-light">G</i>
                                    </div>
                                    <div class="media-body">
                                        {{ course_content.grade_to_pass }} <small class="text-muted"></small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- <div class="card">
                      <div class="card-header">
                        <h4 class="card-title">Ratings</h4>
                      </div>
                      <div class="card-body">
                        <div class="rating">
                          <i class="material-icons">star</i>
                          <i class="material-icons">star</i>
                          <i class="material-icons">star</i>
                          <i class="material-icons">star</i>
                          <i class="material-icons">star_border</i>
                        </div>
                        <small class="text-muted">20 ratings</small>
                      </div>
                    </div> -->
                </div>

            </div>
        </div>

    </div>
</template>

<script>
    import {QuillDeltaToHtmlConverter} from "quill-delta-to-html";

    export default {
        data() {
            return {
                course_content: [],
                lesson_list: [],
                description: "",
                request_text: "Apply to this course"
            };
        },
        mounted() {
            this.fetchCourse();
            this.fetchLesson();
        },
        methods: {
            applyCourse() {
                axios
                    .post(this.routes.course.APPLY_COURSE, {
                        id: this.$route.params.id
                    })
                    .then(response => {
                        this.request_text = "Request sent !";
                        this.course_content.student_course = {};
                        this.course_content.student_course.is_rejected = false;
                        this.course_content.student_course.is_approved = false;
                    });
            },

            // fetchCourse() {
            //   axios
            //     .get(this.routes.course.GET_BY_ID + "/" + this.$route.params.id)
            //     .then(response => {
            //       this.course_content = response.data;
            //     });
            // },

            fetchLesson() {
                axios
                    .get(
                        this.routes.lesson.LIST_LESSON_BY_COURSEID +
                        "/" +
                        this.$route.params.id
                    )
                    .then(response => {
                        this.lesson_list = response.data;
                    });
            },

            fetchCourse() {
                axios
                    .get(this.routes.course.GET_BY_ID + "/" + this.$route.params.id)
                    .then(response => {
                        this.course_content = response.data;
                        this.description = this.parseDescription();
                        if (
                            this.course_content.student_course &&
                            this.course_content.student_course.is_rejected
                        ) {
                            this.request_text = "Your request has been rejected.";
                        } else if (
                            this.course_content.student_course &&
                            !this.course_content.student_course.is_rejected &&
                            !this.course_content.student_course.is_approved
                        ) {
                            this.request_text = "Your request is being reviewed.";
                        }
                    });
            },

            parseDescription() {
                if (
                    this.IsJsonString(this.course_content.description) &&
                    _.has(JSON.parse(this.course_content.description.toString()), "ops")
                ) {
                    var converter = new QuillDeltaToHtmlConverter(
                        JSON.parse(this.course_content.description.toString()).ops,
                        {}
                    );
                    return converter.convert();
                }
                return this.course_content.description;
            },

            IsJsonString(str) {
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return true;
            }
        }
    };
</script>

<style>
    .btn-disabled {
        background: rgb(200, 200, 200) !important;
        border: none !important;
        color: #fff !important;
    }
</style>
