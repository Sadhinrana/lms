<template>
    <div class="container-fluid page__container">
        <div
                class="alert alert-dismissible bg-success text-white border-0 fade"
                :class="added_success?'show':''"
                role="alert"
        >
            Added new course <strong>successfully !</strong>
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
                <h1 class="h2">Add Course</h1>
            </div>
            <div class="media-right">
                <a
                        @click.prevent="addCourse(false)"
                        class="btn btn-success"
                        href="#"
                >SAVE</a>

            </div>
            <div class="media-right">
                <a
                        @click.prevent="addCourse(true)"
                        class="btn btn-primary"
                        href="#"
                >PUBLISH</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Basic Information</h4>
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
                                                    v-if="course.course_image.length>0"
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
                                    placeholder="How many minutes?"
                                    value="10"
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
        name: "instructor_add_course",

        mounted() {
            this.initNester();
            this.initQuillEditor();
            this.getCouseCategories();
        },

        data() {
            return {
                quill: null,

                course: {
                    title: "",
                    description: "",
                    video_link: "",
                    category_id: null,
                    duration: "",
                    is_published: false,
                    grade_to_pass: 5,
                    lesson: [],
                    course_image: "",
                    course_level: ""
                },

                categories: [],
                added_success: false,
                added_error: false,
                added_error_messages: []
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

            getCouseCategories() {
                axios.get(this.routes.category.GET_CATEGORIES).then(response => {
                    this.categories = response.data;
                });
            },

            addCourse(is_published) {
                this.$store.dispatch("enableLoading");
                if (is_published) this.course.is_published = true;
                this.course.description = JSON.stringify(this.quill.getContents());
                axios
                    .post(this.routes.course.CREATE, this.course)
                    .then(response => {
                        this.$store.dispatch("disableLoading");
                        this.added_success = true;
                        this.added_error = false;
                        this.course = {
                            title: "",
                            description: "",
                            video_link: "",
                            cbc_cate_id: null,
                            duration: "",
                            is_published: false,
                            grade_to_pass: 5,
                            lesson: [],
                            course_image: ""
                        };
                        this.quill.setContents("");
                        this.$router.push({name: "instructor_course_manager"});
                    })
                    .catch(err => {
                        this.added_error_messages = err.response.data;
                        this.added_error = true;
                        this.added_success = false;
                        this.$store.dispatch("disableLoading");
                    });
            },

            initQuillEditor() {
                this.quill = new Quill("#quill", {
                    theme: "snow"
                });
            },

            initNester() {
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
                $(".nestable").on("change", function () {
                    console.log($(".nestable").nestable("serialize"));
                });
                //--------Initialize the nested-----------//
                // !(function(e) {
                //   var t = {};
                //   function n(s) {
                //     if (t[s]) return t[s].exports;
                //     var l = (t[s] = { i: s, l: !1, exports: {} });
                //     return e[s].call(l.exports, l, l.exports, n), (l.l = !0), l.exports;
                //   }
                //   (n.m = e),
                //     (n.c = t),
                //     (n.d = function(e, t, s) {
                //       n.o(e, t) ||
                //         Object.defineProperty(e, t, {
                //           configurable: !1,
                //           enumerable: !0,
                //           get: s
                //         });
                //     }),
                //     (n.n = function(e) {
                //       var t =
                //         e && e.__esModule
                //           ? function() {
                //               return e.default;
                //             }
                //           : function() {
                //               return e;
                //             };
                //       return n.d(t, "a", t), t;
                //     }),
                //     (n.o = function(e, t) {
                //       return Object.prototype.hasOwnProperty.call(e, t);
                //     }),
                //     (n.p = "/"),
                //     n((n.s = 366));
                // })({
                //   366: function(e, t, n) {
                //     e.exports = n(367);
                //   },
                //   367: function(e, t, n) {
                //     "use strict";
                //     var s;
                //     ((s = jQuery).fn.APNestable = function() {
                //       this.length &&
                //         void 0 !== s.fn.nestable &&
                //         this.nestable({
                //           rootClass: "nestable",
                //           listNodeName: "ul",
                //           listClass: "nestable-list",
                //           itemClass: "nestable-item",
                //           dragClass: "nestable-drag",
                //           handleClass: "nestable-handle",
                //           collapsedClass: "nestable-collapsed",
                //           placeClass: "nestable-placeholder",
                //           emptyClass: "nestable-empty"
                //         });
                //     }),
                //       s(".nestable").APNestable();
                //   }
                // });
            }
        }
    };
</script>

<style>
</style>
