<template>
    <div class="page">

        <div class="container page__container">
            <!-- <ol class="breadcrumb">
              <li class="breadcrumb-item"><router-link :to="{ name: 'home'}">Home</router-link></li>
              <li class="breadcrumb-item active">Lesson manager</li>
            </ol> -->

            <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
                <div class="flex mb-2 mb-sm-0">
                    <h1 class="h2">Lesson manager</h1>
                </div>
                <router-link :to="{ name: 'instructor_add_lesson' }" class="btn btn-success">
                    Add lesson
                </router-link>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg">
                            <div
                                    class="table-responsive border-bottom"
                                    data-toggle="lists"
                                    data-lists-values='["js-lists-values-employee-name"]'
                            >

                                <table class="table mb-0 course-item" v-for="course in courses">
                                    <thead>
                                    <tr>

                                        <th class="course-name">
                                            <router-link
                                                    :to="{ name: 'instructor_edit_course', params: {id:course.id} }">
                                                {{ course.title }}
                                            </router-link>
                                        </th>

                                        <th style="width: 24px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody
                                            class="list"
                                            id="staff"
                                    >
                                    <tr
                                            class="selected"
                                            v-for="lesson in course.lesson"
                                    >
                                        <td class="lesson-name">
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <span class="js-lists-values-employee-name">{{lesson.title}}</span>
                                                </div>
                                            </div>

                                        </td>

                                        <td>
                                            <a href="#" class="text-muted" data-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <router-link
                                                        :to="{ name: 'instructor_edit_lesson', params: {id:lesson.id} }"
                                                        class="dropdown-item">
                                                    Edit
                                                </router-link>
                                                <a href="#" @click.prevent="deleteLesson(lesson.id)"
                                                   class="dropdown-item">Delete</a>
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

            <!-- Pagination -->
            <ul class="pagination justify-content-center pagination-sm">
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

        <div class="container page__container">
            <div class="footer">
                Copyright &copy; 2016 - <a
                    href="http://themeforest.net/item/learnplus-learning-management-application/15287372?ref=mosaicpro">Purchase
                LearnPlus</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                courses: [],
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4
            };
        },

        mounted() {
            this.getCourseList(this.pagination.current_page);
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
            }
        },

        methods: {
            getUserList(page) {
                axios
                    .get(this.routes.admin.GET_USER_LIST, {
                        params: {
                            page: page
                        }
                    })
                    .then(response => {
                        this.users = response.data.data;
                        this.pagination.from = response.data.from;
                        this.pagination.to = response.data.to;
                        this.pagination.total = response.data.total;
                        this.pagination.per_page = response.data.per_page;
                        this.pagination.last_page = response.data.last_page;
                        this.pagination.current_page = response.data.current_page;
                    });
            },


            getCourseList(page) {
                axios
                    .get(this.routes.course.GET_ALL + /all/ + 10, {
                        params: {
                            page: page
                        }
                    })
                    .then(response => {
                        //this.courses = response.data.data.filter(course => course.lesson.length > 0);
                        this.courses = response.data.data;
                        this.pagination.from = response.data.from;
                        this.pagination.to = response.data.to;
                        this.pagination.total = response.data.total;
                        this.pagination.per_page = response.data.per_page;
                        this.pagination.last_page = response.data.last_page;
                        this.pagination.current_page = response.data.current_page;
                    });
            },


            deleteLesson(id) {
                var confirmed = confirm("Are you sure you want to delete this lesson?");
                if (confirmed) {
                    axios.get(this.routes.lesson.DELETE + id).then(res => {
                        this.getCourseList(this.pagination.current_page);
                    }).catch(err => {
                        console.log(err.response)
                    })
                }
            }
        }
    };
</script>

<style lang="scss" scoped>
    .course-item {
        margin-top: 30px;

        .course-name {
            font-size: 16px;
            color: #2196f3;
            text-transform: capitalize;
        }

        .lesson-name {
            padding-left: 30px;
        }
    }
</style>
