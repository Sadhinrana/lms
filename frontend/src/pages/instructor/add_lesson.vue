<template>
    <div class="container-fluid page__container">
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link to="/">Home</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link
              v-if="$route.params.course_id"
              :to="{name:'instructor_edit_course',params:{id:$route.params.course_id}}"
            >
              Course
            </router-link>

            <router-link :to="{name:'instructor_course_manager'}" v-else>Course</router-link
          </li>
          <li class="breadcrumb-item active">Add lesson</li>
        </ol> -->
        <h1 class="h2">Add Lesson</h1>
        <div class="card">
            <div class="card-body">
                <form action="#">
                    <div class="form-group row">
                        <label
                                for="avatar"
                                class="col-sm-3 col-form-label form-label"
                        >Preview</label>
                        <div class="col-sm-9">
                            <div class="media align-items-center">
                                <div class="media-left">
                                    <img
                                            v-if="lesson_data.image"
                                            :src="lesson_data.image"
                                            class="rounded"
                                            width="100"
                                    />
                                    <img
                                            v-else
                                            class="rounded"
                                            width="100"
                                            src="
                                  https://scontent.fsgn2-4.fna.fbcdn.net/v/t1.0-1/c47.0.160.160a/p160x160/10354686_10150004552801856_220367501106153455_n.jpg?_nc_cat=1&_nc_ht=scontent.fsgn2-4.fna&oh=d91da171bb693f7721ef7cd261bbcf77&oe=5CC35C1E"
                                    />
                                </div>
                                <div class="media-body">
                                    <div
                                            class="custom-file"
                                            style="width: auto;"
                                    >
                                        <input
                                                type="file"
                                                id="lessonImage"
                                                accept="image/*"
                                                @change="onImageInputChanged"
                                                class="custom-file-input"
                                        >
                                        <label
                                                for="avatar"
                                                class="custom-file-label"
                                        >Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label
                                for="title"
                                class="col-md-3 col-form-label form-label"
                        >Title</label>
                        <div class="col-md-6">
                            <input
                                    v-model="lesson_data.txt_title"
                                    id="title"
                                    type="text"
                                    class="form-control"
                                    placeholder="Write an awesome title"
                            >
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                      <label
                        for="course"
                        class="col-md-3 col-form-label form-label"
                      >Course</label>
                      <div class="col-md-4">
                        <select
                          id="company_head"
                          class="custom-control form-control custom-select"
                          v-model="lesson_data.txt_course"
                        >
                          <option
                            selected
                            disabled
                          >Select Course</option>
                          <option
                            v-for="course in courseList"
                            :value="course.id"
                          >{{ course.title }}</option>
                        </select>
                      </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label form-label">Video Link</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input
                                                v-model="lesson_data.txt_video_link"
                                                type="text"
                                                class="form-control"
                                                value="https://player.vimeo.com/video/97243285?title=0&amp;byline=0&amp;portrait=0"
                                        />
                                        <small class="form-text text-muted d-flex align-items-center">
                                            <i class="material-icons font-size-16pt mr-1">ondemand_video</i>
                                            <span class="icon-text">Paste Video</span>
                                        </small>
                                    </div>
                                </div>
                                <div
                                        class="col-md-6"
                                        v-if="lesson_data.txt_video_link"
                                >
                                    <div class="form-group">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe
                                                    class="embed-responsive-item"
                                                    :src="lesson_data.txt_video_link"
                                                    allowfullscreen=""
                                            ></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label
                                for="description"
                                class="col-sm-3 col-form-label form-label"
                        >Lesson Description</label>
                        <div class="col-sm-6 col-md-6">
                            <div class="input-group">
                <textarea
                        id="description"
                        class="form-control"
                        placeholder="Write something to describe this lesson"
                        v-model="lesson_data.txt_description"
                ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-8 offset-sm-3">
                            <div class="media align-items-center">
                                <div class="media-left">
                                    <a
                                            href="#"
                                            class="btn btn-info"
                                            @click.prevent="addLesson"
                                    >Add Lesson</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
                <div
                        class="alert alert-dismissible bg-success text-white border-0 fade"
                        :class="added_success?'show':''"
                        role="alert"
                >
                    Added lesson <strong>successfully !</strong>
                </div>
                <div
                        class="alert alert-dismissible bg-danger text-white border-0 fade"
                        :class="added_error?'show':''"
                        v-for="error in added_error_messages.errors"
                        role="alert"
                >
                    <strong>Error ! </strong> {{error[0]}}
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "instructor_add_lesson",
        data() {
            return {
                lesson_data: {
                    txt_title: "",
                    txt_description: "",
                    txt_video_link:
                        "https://player.vimeo.com/video/97243285?title=0&amp;byline=0&amp;portrait=0",
                    txt_course: this.$route.params.course_id,
                    image: ""
                },
                courseList: [],
                added_success: false,
                added_error: false,
                added_error_messages: []
            };
        },
        mounted() {
            this.fetchCourses();
        },
        methods: {
            onImageInputChanged(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.readAsBinaryString(file);

                reader.onload = () => {
                    this.lesson_data.image =
                        "data:image/jpeg;base64," + btoa(reader.result);
                    this.lesson_data.image =
                        "data:image/jpeg;base64," + btoa(reader.result);
                };
                reader.onerror = () => {
                    console.log("there are some problems");
                };
            },

            fetchCourses() {
                axios
                    .get(this.routes.course.GET_ALL)
                    .then(res => {
                        this.courseList = res.data;
                        if (this.$route.params.course_id)
                            this.lesson_data.txt_course = this.$route.params.course_id
                    })
                    .catch(err => {
                        console.log(err.data);
                    });
            },

            addLesson() {
                axios
                    .post(this.routes.lesson.CREATE, this.lesson_data)
                    .then(res => {
                        this.added_success = true;
                        this.added_error = false;
                        if (this.$route.params.course_id) {
                            this.$router.push({
                                name: 'instructor_edit_course',
                                params: {id: this.$route.params.course_id}
                            });
                        }
                    })
                    .catch(err => {
                        this.added_error_messages = err.response.data;
                        this.added_error = true;
                        this.added_success = false;
                    });
            }
        }
    };
</script>

<style>
</style>
