<template>
    <div class="container-fluid page__container">
        <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
            <div class="flex mb-2 mb-sm-0">
                <h1 class="h2">Manage Courses</h1>
            </div>
            <router-link
                    :to="{ name: 'instructor_add_course'}"
                    class="btn btn-success"
            >
                Add course
            </router-link>
        </div>

        <div class="card card-body border-left-3 border-left-primary navbar-shadow mb-4">
            <form action="#">
                <div class="d-flex flex-wrap2 align-items-center mb-headings">
                    <div class="dropdown">
                        <a
                                href="#"
                                data-toggle="dropdown"
                                class="btn btn-white"
                        ><i class="material-icons mr-sm-2">tune</i> <span class="d-none d-sm-block">Filters</span></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-item d-flex flex-column">
                                <div class="form-group">
                                    <label
                                            for="custom-select"
                                            class="form-label"
                                    >Category</label><br>
                                    <select
                                            id="custom-select"
                                            class="form-control custom-select"
                                            style="width: 200px;"
                                            v-model="selected_category_id"
                                    >
                                        <option
                                                value="0"
                                                selected
                                        >All categories
                                        </option>
                                        <option
                                                v-for="category in categories"
                                                :key="category.id"
                                                :value="category.id"
                                        >{{category.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label
                                            for="published01"
                                            class="form-label"
                                    >Published</label><br>
                                    <select
                                            id="published01"
                                            class="form-control custom-select"
                                            style="width: 200px;"
                                            v-model="course_type"
                                    >
                                        <option
                                                selected
                                                value="0"
                                        >All courses
                                        </option>
                                        <option value="1">Published courses</option>
                                        <option value="2">Private courses</option>
                                        <option value="3">Draft courses</option>

                                    </select>
                                </div>
                                <a href="#">Clear filters</a>
                            </div>
                        </div>
                    </div>
                    <div class="flex search-form ml-3 search-form--light">
                        <input
                                type="text"
                                class="form-control"
                                placeholder="Search courses"
                                id="searchSample02"
                                v-model.lazy="search_key_word"
                                v-debounce="300"
                        >
                        <button
                                class="btn"
                                type="button"
                                role="button"
                        ><i class="material-icons">search</i></button>
                    </div>
                </div>

                <div
                        class="d-flex flex-column flex-sm-row align-items-sm-center"
                        style="white-space: nowrap;"
                >
                    <small class="flex text-muted text-uppercase mr-3 mb-2 mb-sm-0">Displaying
                        {{pagination.current_page}} out of {{pagination.total}} courses</small>

                </div>
            </form>
        </div>
        <template v-if="search_key_word.length==0">
            <div
                    v-if="courses.length==0"
                    class="alert alert-light alert-dismissible border-1 border-left-3 border-left-warning"
                    role="alert"
            >
                <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="text-black-70">Ohh no! No courses to display. Add some courses.</div>
            </div>

            <div class="row">

                <div
                        class="col-md-6"
                        v-for="course in courses"
                        :key="course.id"
                >
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex flex-column flex-sm-row">
                                <router-link
                                        :to="{name:'instructor_edit_course',params:{id:course.id}}"
                                        class="avatar avatar-lg avatar-4by3 mb-3 w-xs-plus-down-100 mr-sm-3"
                                >
                                    <img
                                            :src="routes.server_api + course.course_image"
                                            alt="Card image cap"
                                            v-if="course.course_image && course.course_image.length>0"
                                            class="avatar-img rounded"
                                    >
                                    <img
                                            :src="routes.server_api+ '/img/default.png'"
                                            alt="Card image cap"
                                            class="avatar-img rounded"
                                            v-else
                                    >
                                </router-link>
                                <div
                                        class="flex"
                                        style="min-width: 200px;"
                                >
                                    <!-- <h5 class="card-title text-base m-0"><a href="instructor-course-edit.html"><strong>Learn Vue.js</strong></a></h5> -->
                                    <h4 class="card-title mb-1">
                                        <router-link :to="{name:'instructor_edit_course',params:{id:course.id}}">
                                            {{course.title}}
                                        </router-link>
                                    </h4>
                                    <p
                                            class="text-black-70 fade-out-container"
                                            v-html="parseDescription(course)"
                                    ></p>
                                    <div class="d-flex align-items-end">
                                        <div class="d-flex flex flex-column mr-3">
                                            <div class="d-flex align-items-center py-1 border-bottom">
                                                <!-- <small class="text-black-70 mr-2">&dollar;1,230/mo</small> -->
                                                <!-- <small class="text-black-50">34 learners</small> -->
                                            </div>
                                            <div class="d-flex align-items-center py-1">
                                                <!-- <small class="text-muted mr-2">GULP</small> -->
                                                <small class="text-muted">{{ course.category.name }}</small>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <router-link
                                                    :to="{name:'instructor_edit_course',params:{id:course.id}}"
                                                    class="btn btn-sm btn-white"
                                            >Edit
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card__options dropdown right-0 pr-2">
                            <a
                                    href="#"
                                    class="dropdown-toggle text-muted"
                                    data-caret="false"
                                    data-toggle="dropdown"
                            >
                                <i class="material-icons">more_vert</i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <router-link
                                        :to="{name:'instructor_edit_course',params:{id:course.id}}"
                                        class="dropdown-item"
                                >Edit course
                                </router-link>
                                <a
                                        class="dropdown-item"
                                        href="#"
                                        @click.prevent="duplicateCourse(course.id)"
                                >Duplicate Course</a>
                                <div class="dropdown-divider" v-if="authUser.role !== 'content_manager'"></div>
                                <a v-if="authUser.role !== 'content_manager'"
                                   class="dropdown-item text-danger"
                                   href="#"
                                   @click.prevent="deleteCourse(course)"
                                >Delete course</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template v-else>
            <div
                    v-if="courses_from_search.length==0"
                    class="alert alert-light alert-dismissible border-1 border-left-3 border-left-warning"
                    role="alert"
            >
                <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="text-black-70">No results found.</div>
            </div>

            <div class="row">

                <div
                        class="col-md-6"
                        v-for="course in courses_from_search"
                        :key="course.id"
                >
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex flex-column flex-sm-row">
                                <router-link
                                        :to="{name:'instructor_edit_course',params:{id:course.id}}"
                                        class="avatar avatar-lg avatar-4by3 mb-3 w-xs-plus-down-100 mr-sm-3"
                                >
                                    <img
                                            :src="routes.server_api + course.course_image"
                                            alt="Card image cap"
                                            v-if="course.course_image && course.course_image.length>0"
                                            class="avatar-img rounded"
                                    >
                                    <img
                                            src="@/assets/images/vuejs.png"
                                            alt="Card image cap"
                                            class="avatar-img rounded"
                                            v-else
                                    >
                                </router-link>
                                <div
                                        class="flex"
                                        style="min-width: 200px;"
                                >
                                    <!-- <h5 class="card-title text-base m-0"><a href="instructor-course-edit.html"><strong>Learn Vue.js</strong></a></h5> -->
                                    <h4 class="card-title mb-1">
                                        <router-link :to="{name:'instructor_edit_course',params:{id:course.id}}">
                                            {{course.title}}
                                        </router-link>
                                    </h4>
                                    <p
                                            class="text-black-70 fade-out-container"
                                            v-html="parseDescription(course)"
                                    ></p>
                                    <div class="d-flex align-items-end">
                                        <div class="d-flex flex flex-column mr-3">
                                            <div class="d-flex align-items-center py-1 border-bottom">
                                                <!-- <small class="text-black-70 mr-2">&dollar;1,230/mo</small> -->
                                                <small class="text-black-50">34 learners</small>
                                            </div>
                                            <div class="d-flex align-items-center py-1">
                                                <!-- <small class="text-muted mr-2">GULP</small> -->
                                                <small class="text-muted">INTERMEDIATE</small>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <router-link
                                                    :to="{name:'instructor_edit_course',params:{id:course.id}}"
                                                    href="instructor-course-edit.html"
                                                    class="btn btn-sm btn-white"
                                            >Edit
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card__options dropdown right-0 pr-2">
                            <a
                                    href="#"
                                    class="dropdown-toggle text-muted"
                                    data-caret="false"
                                    data-toggle="dropdown"
                            >
                                <i class="material-icons">more_vert</i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <router-link
                                        :to="{name:'instructor_edit_course',params:{id:course.id}}"
                                        class="dropdown-item"
                                >Edit course
                                </router-link>
                                <a
                                        class="dropdown-item"
                                        href="#"
                                >Course Insights</a>
                                <div class="dropdown-divider"></div>
                                <a
                                        class="dropdown-item text-danger"
                                        href="#"
                                        @click.prevent="deleteCourse(course)"
                                >Delete course</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Pagination -->
        <ul
                v-if="search_key_word.length==0"
                class="pagination justify-content-center pagination-sm"
        >
            <li
                    v-if="pagination.current_page>1"
                    class="page-item"
            >
                <a
                        @click.prevent="getCourseList(pagination.current_page - 1)"
                        class="page-link"
                        href="#"
                        aria-label="Previous"
                >
          <span
                  aria-hidden="true"
                  class="material-icons"
          >chevron_left</span>
                    <span>Prev</span>
                </a>
            </li>

            <li
                    :key="page"
                    v-for="page in pagesNumber"
                    :class="[page == pagination.current_page ? 'active' : null , 'page-item']"
            >
                <a
                        href="#"
                        v-on:click.prevent="getCourseList(page)"
                        class="page-link"
                        aria-label="Previous"
                >{{ page }}</a>
            </li>

            <li v-if="pagination.current_page < pagination.last_page">
                <a
                        href="javascript:void(0)"
                        aria-label="Next"
                        v-on:click.prevent="getCourseList(pagination.current_page + 1)"
                >
                    <span aria-hidden="true"></span>
                    <span
                            aria-hidden="true"
                            class="material-icons"
                    >chevron_right</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    import debounce from "v-debounce";
    import {QuillDeltaToHtmlConverter} from "quill-delta-to-html";

    export default {
        directives: {
            debounce
        },
        data() {
            return {
                course_dupli: {
                    idcourse: ""
                },
                courses: [],
                courses_from_search: [],
                search_key_word: "",
                categories: [],
                selected_category_id: 0,
                course_type: 0,
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                }
            };
        },
        watch: {
            search_key_word(newVal, oldVal) {
                this.searchCourses(newVal);
            }
        },
        computed: {
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
            },
            authUser: function () {
                return this.$store.getters.authUser
            }
        },
        mounted() {
            //  this.getCourse();
            this.getCouseCategories();
            this.getCourseList(this.pagination.current_page);
        },
        methods: {
            searchCourses(keyword) {
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.course.SEARCH, {
                        params: {
                            keyword: keyword,
                            category_id: this.selected_category_id,
                            course_type: this.course_type
                        }
                    })
                    .then(response => {
                        this.courses_from_search = response.data;
                        this.$store.dispatch("disableLoading");
                    })
                    .catch(e => {
                        this.$store.dispatch("disableLoading");
                    });
            },

            getCouseCategories() {
                this.$store.dispatch("enableLoading");
                axios.get(this.routes.category.GET_CATEGORIES)
                    .then(response => {
                        this.categories = response.data;
                        this.$store.dispatch("disableLoading");
                    }).catch(e => {
                    this.$store.dispatch("disableLoading");
                });
            },

            getCourseList(page) {
                this.$store.dispatch("enableLoading");
                axios
                    .get(this.routes.course.GET_ALL + /all/ + 10, {
                        params: {
                            page: page
                        }
                    })
                    .then(response => {
                        this.courses = response.data.data;
                        this.pagination.from = response.data.from;
                        this.pagination.to = response.data.to;
                        this.pagination.total = response.data.total;
                        this.pagination.per_page = response.data.per_page;
                        this.pagination.last_page = response.data.last_page;
                        this.pagination.current_page = response.data.current_page;
                        this.$store.dispatch("disableLoading");
                    }).catch(e => {
                    this.$store.dispatch("disableLoading");
                });
            },

            duplicateCourse(id) {
                this.$store.dispatch("enableLoading");
                (this.course_dupli.idcourse = id),
                    console.log(this.course_dupli.idcourse);
                axios
                    .post(this.routes.course.DUPLICATE_COURSE, this.course_dupli)
                    .then(res => {
                        this.added_success = true;
                        this.added_error = false;
                        this.getCourseList(1);
                        this.$store.dispatch("disableLoading");
                    })
                    .catch(err => {
                        this.$store.dispatch("disableLoading");
                        this.added_error_messages = err.response.data;
                        this.added_error = true;
                        this.added_success = false;
                    });
            },

            deleteCourse(course) {
                var confirmed = confirm("Are you sure you want to delete this course?");
                if (confirmed) {
                    this.$store.dispatch("enableLoading");
                    axios
                        .delete(this.routes.course.DELETE, {
                            data: {
                                id: course.id
                            }
                        })
                        .then(response => {
                            this.$store.dispatch("disableLoading");
                            this.courses = this.courses.filter(courseIterator => {
                                this.$store.dispatch("disableLoading");
                                return courseIterator.id != course.id;
                            });
                        });
                }
            },

            parseDescription(course) {
                if (
                    this.IsJsonString(course.description) &&
                    _.has(JSON.parse(course.description.toString()), "ops")
                ) {
                    var converter = new QuillDeltaToHtmlConverter(
                        JSON.parse(course.description.toString()).ops,
                        {}
                    );
                    return converter.convert();
                }
                return course.description;
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
    .fade-out-container {
        height: 41px;
        overflow: hidden;
    }
</style>
