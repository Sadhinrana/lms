<template>
  <div class="page">

    <div class="container page__container">
      <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
        <div class="flex mb-2 mb-sm-0">
          <h1 class="h2">User Manager</h1>
        </div>
      </div>

      <div class="card card-body border-left-3 border-left-primary navbar-shadow mb-4">
        <form action="#" @submit.prevent>
          <div class="d-flex flex-wrap2 align-items-center mb-headings">

            <div class="flex search-form ml-3 search-form--light">
              <input
                      type="text"
                      class="form-control"
                      placeholder="Search users by name, email..."
                      id="searchSample02"
                      v-model.lazy="search_key_word"
                      v-debounce="500"
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
            <small class="flex text-muted text-uppercase mr-3 mb-2 mb-sm-0">Displaying {{pagination.current_page}} out of {{pagination.total}} users</small>

          </div>
        </form>
      </div>

      <div
              class="card"
              v-if="search_key_word.length == 0"
      >
        <div class="card-body">
          <div class="row">
            <div class="col-lg">
              <div
                      class="table-responsive border-bottom"
                      data-toggle="lists"
                      data-lists-values='["js-lists-values-employee-name"]'
              >

                <table class="table mb-0">
                  <thead>
                  <tr>
                    <th>User</th>
                    <th style="width: 24px;"></th>
                  </tr>
                  </thead>
                  <tbody
                          class="list"
                          id="staff"
                  >
                  <tr
                          class="selected"
                          v-for="user in users"
                          :key="user.id"
                  >
                    <td>

                      <div class="media align-items-center">
                        <div class="avatar avatar-sm mr-3">
                          <img
                                  :src="routes.server_api + user.avatar_url"
                                  alt="Avatar"
                                  class="avatar-img rounded-circle"
                                  v-if="user.avatar_url"
                          >
                          <img
                                  :src="routes.server_api+ '/img/default_avatar.jpg'"
                                  alt=""
                                  class="avatar-img rounded-circle"
                                  v-else
                          >
                        </div>
                        <div class="media-body">

                            <span class="js-lists-values-employee-name">
                              <router-link
                                      :to="{ name: 'instructor_edit_users', params: {id:user.id} }"
                              >
                                {{user.first_name + " " + user.last_name}}
                              </router-link>
                            </span>

                        </div>
                      </div>

                    </td>

                    <td>
                      <a
                              href="#"
                              class="text-muted"
                              data-toggle="dropdown"
                      >
                        <i class="material-icons">more_vert</i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right">
                        <router-link
                                :to="{ name: 'instructor_edit_users', params: {id:user.id} }"
                                class="dropdown-item"
                        >
                          Edit
                        </router-link>
                        <a
                                href="#"
                                @click.prevent="deleteUser(user.id)"
                                class="dropdown-item"
                        >Delete</a>
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

      <div
              class="card"
              v-if="search_key_word.length > 0 && users_from_search.length > 0"
      >
        <div class="card-body">
          <div class="row">
            <div class="col-lg">
              <div
                      class="table-responsive border-bottom"
                      data-toggle="lists"
                      data-lists-values='["js-lists-values-employee-name"]'
              >

                <table class="table mb-0">
                  <thead>
                  <tr>

                    <th>User</th>

                    <th style="width: 24px;"></th>
                  </tr>
                  </thead>
                  <tbody
                          v-if="users_from_search.length > 0"
                          class="list"
                          id="staff"
                  >

                  <tr
                          class="selected"
                          v-for="user in users_from_search"
                          :key="user.id"
                  >

                    <td>

                      <div class="media align-items-center">
                        <div class="avatar avatar-sm mr-3">
                          <img
                                  :src="routes.server_api + user.avatar_url"
                                  alt="Avatar"
                                  class="avatar-img rounded-circle"
                                  v-if="user.avatar_url"
                          >
                          <img
                                  :src="routes.server_api+ '/img/default_avatar.jpg'"
                                  alt=""
                                  class="avatar-img rounded-circle"
                                  v-else
                          >
                        </div>
                        <div class="media-body">

                            <span class="js-lists-values-employee-name">
                              <router-link
                                      :to="{ name: 'instructor_edit_users', params: {id:user.id} }"
                              >
                                {{user.first_name + " " + user.last_name}}
                              </router-link>
                            </span>

                        </div>
                      </div>

                    </td>

                    <td>
                      <a
                              href="#"
                              class="text-muted"
                              data-toggle="dropdown"
                      >
                        <i class="material-icons">more_vert</i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right">
                        <router-link
                                :to="{ name: 'instructor_edit_users', params: {id:user.id} }"
                                class="dropdown-item"
                        >
                          Edit
                        </router-link>
                        <a
                                href="#"
                                @click.prevent="deleteUser(user.id)"
                                class="dropdown-item"
                        >Delete</a>
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

      <div
              v-else
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

      <!-- Pagination -->
      <ul
              class="pagination justify-content-center pagination-sm"
              v-if="search_key_word.length == 0"
      >
        <li
                v-if="pagination.current_page>1"
                class="page-item"
        >
          <a
                  @click.prevent="getUserList(pagination.current_page - 1)"
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
                  v-on:click.prevent="getUserList(page)"
                  class="page-link"
                  aria-label="Previous"
          >{{ page }}</a>
        </li>

        <li v-if="pagination.current_page < pagination.last_page">
          <a
                  href="javascript:void(0)"
                  aria-label="Next"
                  v-on:click.prevent="getUserList(pagination.current_page + 1)"
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

    <!-- modal-->
    <div
            class="modal fade"
            id="blockForm"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h4 class="modal-title text-white">Why do you block this student to take quiz?</h4>
            <button
                    type="button"
                    class="close text-white"
                    data-dismiss="modal"
                    aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="#" @submit.prevent>
              <div class="form-group row">
                <label
                        for="quiz_title"
                        class="col-sm-3 col-form-label form-label"
                >Write in detail:</label>
                <div class="col-sm-9">
                  <textarea
                          id="quiz_title"
                          type="text"
                          class="form-control"
                          v-model="block_description"
                          placeholder="The reason to block this student"
                  >
                  </textarea>
                </div>
              </div>
              <div class="form-group row mb-0">
                <div class="col">
                  <button
                          type="submit"
                          class="btn btn-success float-right"
                          @click="blockUserToTakeQuiz"
                  >Save</button>
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
  import debounce from "v-debounce";
  export default {
    directives: {
      debounce
    },

    data() {
      return {
        user:[],
        users: [],
        pagination: {
          total: 0,
          per_page: 2,
          from: 1,
          to: 0,
          current_page: 1
        },
        offset: 4,
        search_key_word: "",
        users_from_search: [],
        current_selected_student: null,
        block_description: "",
        c_id: null,
      };
    },

    watch: {
      search_key_word(newVal, oldVal) {
        this.searchUsers(newVal);
      }
    },

    mounted() {
      this.$session.start();
      this.getUserList(this.pagination.current_page);
      $("#blockForm").appendTo("#modelDestination");
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
      unblockUserToTakeQuiz(user) {
        axios.post(this.routes.admin.BLOCK_USER_TO_QUIZZES, {
          user_id: user.id,
          reason: ""
        }).then(response => {
          user.student_quiz_block.is_blocked = false
          this.$forceUpdate();
        });
      },

      blockUserToTakeQuiz() {
        axios.post(this.routes.admin.BLOCK_USER_TO_QUIZZES, {
          user_id:this.current_selected_student.id,
          reason: this.block_description
        }).then(response => {
          $('#blockForm').modal('toggle');
          this.current_selected_student.student_quiz_block = {}
          this.current_selected_student.student_quiz_block.is_blocked = true
          this.block_description = ""
        });
      },

      assignUserIdToBlock(user) {
        this.current_selected_student = user;
      },

      searchUsers(keyword) {
        this.user = this.$store.getters.authUser;
        this.$store.dispatch("enableLoading");
        axios
                .get(this.routes.admin.SEARCH_FOR_STUDENTS_INSTRUCTOR, {
                  params: {
                    keyword: keyword,
                    company_id: this.user.company_id
                  }
                })
                .then(response => {
                  this.users_from_search = response.data;
                  this.$store.dispatch("disableLoading");
                })
                .catch(e => {
                  this.$store.dispatch("disableLoading");
                });
      },

      getUserList(page) {
        this.user = this.$store.getters.authUser;

        if(this.user.company_id != null){
          this.$session.set("c_id",this.user.company_id);
        }

        console.log(this.$session.get("c_id"));
        this.$store.dispatch("enableLoading");
        axios
                .get(this.routes.admin.GET_STUDENT_LIST_COMPANY_BASED, {
                  params: {
                    page: page,
                    company_id: this.$session.get("c_id")
                  }
                })
                .then(response => {
                  if (response.data.status === 200) {
                    this.users = response.data.data.data;
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
          this.$store.dispatch("disableLoading");
        });
      },

      deleteUser(id) {
        var confirmed = confirm("Are you sure you want to delete this user?");
        if (confirmed) {
          this.$store.dispatch("enableLoading");
          axios
                  .get(this.routes.user.DELETE + id)
                  .then(res => {
                    this.getUserList(this.pagination.current_page);
                    this.$store.dispatch("disableLoading");
                  })
                  .catch(err => {
                    console.log(err.response);
                    this.$store.dispatch("disableLoading");
                  });
        }
      },
    }
  };
</script>

<style>
</style>
