<template>
    <div class="container-fluid page__container">
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link to="/">Home</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link :to="{name:'instructor_course_manager'}">Course</router-link>
          </li>
          <li class="breadcrumb-item active">Edit lesson</li>
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
                                            :src="routes.server_api + lesson_data.image"
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
                    <div class="form-group row">
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
                                >Select Course
                                </option>
                                <option
                                        v-for="course in courseList"
                                        :value="course.id"
                                >{{ course.title }}
                                </option>
                            </select>
                        </div>
                    </div>
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
                                            <!-- https://player.vimeo.com/video/97243285?title=0&amp;byline=0&amp;portrait=0 -->
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
                                            @click.prevent="updateLesson"
                                    >Update Lesson</a>
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
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Files</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form
                                id="my-awesome-dropzone"
                                action="/target"
                                class="dropzone"
                        ></form>
                    </div>
                    <div class="col-md-6">
                        <div data-toggle="tree">
                            <ul style="display: none;">
                                <li class="folder expanded">
                                    lesson files
                                    <ul>
                                        <li>lesson-1-install.zip</li>
                                        <li>lesson-1-steps.zip</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                lesson_data: {
                    txt_title: "",
                    txt_description: "",
                    txt_video_link:
                        "https://player.vimeo.com/video/97243285?title=0&amp;byline=0&amp;portrait=0",
                    txt_course: "Select Course",
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
            this.fetchLesson();
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
                    })
                    .catch(err => {
                        console.log(err.data);
                    });
            },

            fetchLesson() {
                axios
                    .get(this.routes.lesson.GET_BY_ID + "/" + this.$route.params.id)
                    .then(res => {
                        this.lesson_data.txt_title = res.data.title;
                        this.lesson_data.txt_description = res.data.description;
                        this.lesson_data.image = res.data.lesson_image;
                        this.lesson_data.txt_video_link = res.data.video_link;
                        this.lesson_data.txt_course = res.data.course_id;
                        console.log(res.data);
                    })
                    .catch(err => {
                        console.log(err.response.data);
                    });
            },

            updateLesson() {
                axios
                    .put(
                        this.routes.lesson.UPDATE + this.$route.params.id,
                        this.lesson_data
                    )
                    .then(res => {
                        this.added_success = true;
                        this.added_error = false;
                        this.$router.push({name: 'instructor_lesson_manager'})
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
