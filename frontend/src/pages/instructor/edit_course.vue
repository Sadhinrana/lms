<template>
    <div class="container-fluid page__container">
        <div
                class="alert alert-dismissible bg-success text-white border-0 fade"
                :class="added_success?'show':''"
                role="alert"
        >
            <strong>{{ success_text }}</strong>
        </div>
        <div
                v-if="added_error"
                class="alert alert-dismissible bg-danger text-white border-0 fade"
                :class="added_error?'show':''"
                v-for="error in added_error_messages.errors"
                role="alert"
        >
            <strong>Error ! </strong> {{error[0]}}
        </div>
        <div class="media align-items-center mb-headings">
            <div class="media-body">
                <h1 class="h2">Edit Course</h1>
            </div>
            <div class="media-right">
                <a
                        @click.prevent="updateCourse(false)"
                        href="#"
                        class="btn btn-success"
                >SAVE</a>
            </div>
            <div
                    class="media-right"
                    v-if="!course.is_published"
            >
                <a
                        @click.prevent="updateCourse(true)"
                        href="#"
                        class="btn btn-primary"
                >PUBLISH</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Course Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label
                                    for="avatar"
                                    class="col-sm-3 col-form-label form-label"
                            >Course image</label>
                            <div class="col-sm-9">
                                <div class="media align-items-center">
                                    <div class="media-left">
                                        <div class="icon-block rounded">
                                            <!-- <i class="material-icons text-muted-light md-36">photo</i> -->
                                            <img
                                                    v-if="course.course_image && course.course_image.length>0"
                                                    :src="course.course_image"
                                            />
                                            <img
                                                    v-else
                                                    :src="routes.server_api+ '/img/default.png'"
                                            />
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div
                                                class="custom-file"
                                                style="width: auto;"
                                        >
                                            <input
                                                    type="file"
                                                    id="avatar"
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
                        <div class="form-group">
                            <label
                                    class="form-label"
                                    for="title"
                            >Title</label>
                            <input
                                    v-model="course.title"
                                    type="text"
                                    id="title"
                                    class="form-control"
                                    placeholder="Write a title"
                                    value="Basics of Vue.js"
                            >
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label">Description</label>
                            <div
                                    id="quill"
                                    style="height: 150px;"
                                    data-toggle="quill"
                                    data-quill-placeholder="Quill WYSIWYG editor"
                                    data-quill-modules-toolbar='[["bold", "italic"], ["link", "blockquote", "code", "image"], [{"list": "ordered"}, {"list": "bullet"}]]'
                            >
                                <p></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="title" v-if="audio_files.length > 0">Audio Files</label>
                            <div v-for="audio in audio_files">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <audio controls style="width:100%;">
                                            <source
                                                    :src="routes.server_api + audio.file_url"
                                                    type="audio/mpeg"
                                            >
                                            Your browser does not support the audio tag.
                                        </audio>
                                    </div>
                                    <div class="col-sm-2">
                                        <a @click="deleteAudio(audio.id)" class="btn btn-warning btn-sm"
                                           style="margin-left: 10px; margin-top: 10px;"><i class="material-icons">delete_forever</i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Meta</h4>
                        <p class="card-subtitle">Extra Options </p>
                    </div>

                    <form
                            class="card-body"
                            action="#"
                    >
                        <div class="form-group">
                            <label
                                    class="form-label"
                                    for="category"
                            >Category</label>
                            <select
                                    v-model="course.category_id"
                                    id="category"
                                    class="custom-select form-control"
                            >
                                <option :value="null">Select category</option>
                                <option
                                        :value="category.id"
                                        :key="category.id"
                                        v-for="category in categories"
                                >{{category.name}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label
                                    class="form-label"
                                    for="duration"
                            >Duration</label>
                            <input
                                    v-model="course.duration"
                                    type="text"
                                    id="duration"
                                    class="form-control"
                                    placeholder="Duration of this course in minutes"
                            >
                        </div>
                        <div class="form-group">
                            <label
                                    class="form-label"
                                    for="duration"
                            >Course level</label>
                            <input
                                    v-model="course.course_level"
                                    type="text"
                                    id="duration"
                                    class="form-control"
                                    placeholder="Level of this course: beginner, advanced..."
                            >
                        </div>
                        <div class="form-group">
                            <label
                                    class="form-label"
                                    for="duration"
                            >Grade to pass</label>
                            <input
                                    v-model="course.grade_to_pass"
                                    type="text"
                                    id="duration"
                                    class="form-control"
                                    placeholder="Grade to pass this course"
                                    value="5"
                            >
                        </div>

                        <div class="form-group">
                            <label
                                    class="form-label"
                                    for="duration"
                            >Add Audio</label>
                            <button type="button" class="btn btn-primary"
                                    style="padding: 2px !important; float: right !important;" @click="uploadAudio">Add
                            </button>
                            <input
                                    type="file"
                                    id="file" @change="onAudioInputChanged($event)"
                                    class="form-control"
                            >
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.initQuillEditor();
            this.getCouseCategories();
            this.getCourseByRouteParam();
            this.getLesson();
            this.initNester();
            this.getCourseAudio();
        },

        data() {
            return {
                quill: null,
                added_success: false,
                added_error: false,
                added_error_messages: [],
                course: {
                    title: "",
                    description: "",
                    video_link: "",
                    cbc_cate_id: null,
                    duration: "",
                    is_published: false,
                    grade_to_pass: 5,
                    course_image: "",
                    lesson: [],
                    course_level: ""
                },
                course_audio: null,
                lesson: [],
                categories: [],
                success_text: "",
                audio_files: []
            };
        },

        methods: {
            onImageInputChanged(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.readAsBinaryString(file);

                reader.onload = () => {
                    this.course.course_image = "";
                    this.course.image = "data:image/jpeg;base64," + btoa(reader.result);
                    this.course.course_image =
                        "data:image/jpeg;base64," + btoa(reader.result);
                };
                reader.onerror = () => {
                    console.log("there are some problems");
                };
            },

            getCourseByRouteParam() {
                axios
                    .get(`${this.routes.course.GET_BY_ID}/${this.$route.params.id}`)
                    .then(response => {
                        this.course = response.data;
                        if (this.course.course_image && this.course.course_image.length > 0) {
                            this.course.course_image =
                                this.routes.server_api + this.course.course_image;
                        }

                        if (this.IsJsonString(this.course.description)) {
                            this.quill.setContents(JSON.parse(this.course.description));
                        } else {
                            this.quill.setContents({
                                ops: [{insert: this.course.description}]
                            });
                        }
                    });
            },
            getLesson() {
                axios
                    .get(
                        `${this.routes.lesson.LIST_LESSON_BY_COURSEID}/${
                            this.$route.params.id
                        }`
                    )
                    .then(response => {
                        this.lesson = response.data.sort(function (a, b) {
                            return a.orders - b.orders;
                        });
                    });
            },
            getCouseCategories() {
                axios.get(this.routes.category.GET_CATEGORIES).then(response => {
                    this.categories = response.data;
                });
            },

            updateCourse(is_published, _callback) {
                this.$store.dispatch("enableLoading");
                if (is_published) this.course.is_published = true;
                this.success_text = "Saving...";
                this.course.description = JSON.stringify(this.quill.getContents());
                axios
                    .put(
                        `${this.routes.course.UPDATE}/${this.$route.params.id}`,
                        this.course
                    )
                    .then(response => {
                        this.$store.dispatch("disableLoading");
                        this.added_success = true;
                        this.success_text = "Saved!";
                        this.this.added_error = false;
                        _callback();
                        return true;
                    })
                    .catch(err => {
                        this.$store.dispatch("disableLoading");
                        this.added_error_messages = err.response.data;
                        this.added_error = true;
                        this.added_success = false;
                        return false;
                    });
            },

            addLesson() {
                var self = this;
                this.updateCourse(false, function () {
                    console.log("do something crazy");
                    self.$router.push({
                        name: "instructor_add_lesson_id",
                        params: {course_id: self.$route.params.id}
                    });
                });
            },

            initQuillEditor() {
                this.quill = new Quill("#quill", {
                    theme: "snow"
                });
            },

            initNester() {
                var self = this;
                //--------Initialize the nested-----------//
                $(".nestable").nestable({
                    rootClass: "nestable",
                    listNodeName: "ul",
                    listClass: "nestable-list",
                    itemClass: "nestable-item",
                    dragClass: "nestable-drag",
                    handleClass: "nestable-handle",
                    collapsedClass: "nestable-collapsed",
                    placeClass: "nestable-placeholder",
                    emptyClass: "nestable-empty"
                });

                //Update order when change
                $(".nestable").on("change", function () {
                    var reorderArr = $(".nestable").nestable("serialize");
                    var sendArr = [];
                    _.forEach(reorderArr, function (item, index) {
                        var temp = [];
                        temp[0] = item.id;
                        temp[1] = index + 1;
                        temp[2] = self.$route.params.id;
                        sendArr.push(temp);
                    });
                    var test = [1, 2, 3, 4];
                    test["id"] = 1;
                    var data = {ordersid: sendArr[0]};
                    console.log(sendArr);
                    axios
                        .patch(self.routes.lesson.ORDER, {ordersid: sendArr})
                        .then(res => {
                            console.log(res.data);
                        })
                        .catch(err => {
                            console.log(err.response);
                        });
                });
            },


            onAudioInputChanged(event) {
                this.course_audio = event.target.files[0];
                console.log(this.course_audio);
                document.getElementById('file').value = null;
            },

            uploadAudio() {
                /*var txt;
                var name = prompt("Please enter file name:", "");
                if (name == null || name == "") {
                    txt = "No File Name";
                } else {
                    txt = name;
                }*/

                this.$store.dispatch("enableLoading");
                let form_data = new FormData();
                form_data.append("course_id", this.course.id);
                form_data.append("audio", this.course_audio);
                form_data.append("audio_name", this.course_audio.name);
                axios
                    .post(this.routes.course.UPLOAD_COURSE_AUDIO, form_data, {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        }
                    }).then(res => {
                    this.course_audio = null;
                    this.$store.dispatch("disableLoading");
                    this.getCourseAudio();
                }).catch(err => {
                    this.$store.dispatch("disableLoading");
                });
            },

            getCourseAudio() {
                axios
                    .post(this.routes.course.GET_COURSE_AUDIO, {course_id: this.$route.params.id})
                    .then(res => {
                        this.audio_files = res.data;
                    });
            },

            deleteAudio(id) {
                this.$store.dispatch("enableLoading");
                axios
                    .post(this.routes.course.DELTE_AUDIO, {id: id})
                    .then(res => {
                        this.$store.dispatch("disableLoading");
                        this.getCourseAudio();
                    });
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

<style lang="scss">
    .media img {
        max-width: 100%;
        height: auto;
        display: block;
    }
</style>
