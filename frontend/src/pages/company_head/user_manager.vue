<template>
  <div class="page">

    <div class="container page__container">
      <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
        <div class="flex mb-2 mb-sm-0">
          <h1 class="h2">Student Manager</h1>
        </div>
        <router-link
          :to="{ name: 'company_add_user' }"
          class="btn btn-success"
        >
          Add a Student
        </router-link>
      </div>

      <div class="card card-body border-left-3 border-left-primary navbar-shadow mb-4">
        <form action="#" @submit.prevent="getUserList(1)">
          <div class="d-flex flex-wrap2 align-items-center mb-headings">

            <div class="flex search-form ml-3 search-form--light">
              <input
                type="text"
                class="form-control"
                placeholder="Search users by name, email..."
                id="searchSample02"
                v-model="search_key_word"
                @change="searchUser"
              >
              <button
                class="btn"
                type="button"
                role="button"
                @click="getUserList(1)"
              ><i class="material-icons">search</i></button>
            </div>
          </div>

          <div
            class="d-flex flex-column flex-sm-row align-items-sm-center"
            style="white-space: nowrap;"
          >
            <small class="flex text-muted text-uppercase mr-3 mb-2 mb-sm-0">Displaying {{pagination.from}} to {{pagination.to}} out of {{pagination.total}} students</small>

          </div>
        </form>
      </div>

      <div
        class="card"
        v-if="users.length"
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
                      <th>Students</th>
                      <th></th>
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
                                    v-if="user.role === 'student'"
                                :to="{ name: 'company_edit_user', params: {id:user.id} }"
                              >
                                {{user.first_name + " " + user.last_name}}
                              </router-link>
                              <router-link
                                      v-else
                                      :to="{ name: 'company_edit_user', params: {id:user.id}, query: { edit: true } }"
                              >
                                {{user.first_name + " " + user.last_name}}
                              </router-link>
							</span>

                          </div>
                        </div>

                      </td>

                      <td>{{ user.role == 'student' ? user.role : 'teacher' }}</td>

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
                            :to="{ name: 'company_edit_user', params: {id:user.id}, query: { edit: true } }"
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
      <ul class="pagination justify-content-center pagination-sm" v-if="users.length">
        <li v-if="pagination.current_page>1" class="page-item">
          <a @click.prevent="getUserList(pagination.current_page - 1)" class="page-link" href="#" aria-label="Previous">
            <span aria-hidden="true" class="material-icons">chevron_left</span>
            <span>Prev</span>
          </a>
        </li>

        <li :key="page" v-for="page in pagesNumber" :class="[page == pagination.current_page ? 'active' : null , 'page-item']">
          <a href="#" v-on:click.prevent="getUserList(page)" class="page-link" aria-label="Previous" >{{ page }}</a>
        </li>

        <li v-if="pagination.current_page < pagination.last_page">
          <a href="javascript:void(0)" aria-label="Next" v-on:click.prevent="getUserList(pagination.current_page + 1)">
            <span aria-hidden="true"></span>
            <span aria-hidden="true" class="material-icons">chevron_right</span>
          </a>
        </li>
      </ul>

    </div>

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
    };
  },

  mounted() {
    this.$store.dispatch("enableLoading");
    eventBus.$on("authUserFetched", e => {
      this.getUserList(this.pagination.current_page);
    });
    this.getUserList(this.pagination.current_page);
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
      // let to = from + this.offset * 2;
      // if (to >= this.pagination.last_page) {
      //   to = this.pagination.last_page;
      // }
      // let pagesArray = [];
      // for (let page = from; page <= to; page++) {
      //   pagesArray.push(page);
      // }
      let pagesArray = [];
      for (let page = 1; page <= this.pagination.last_page; page++) {
        pagesArray.push(page);
      }
      return pagesArray;
    },
    company_id: function() {
      return this.$store.getters.authUser.company_id;
    }
  },

  methods: {
    getUserList(page) {
      this.$store.dispatch("enableLoading");
      axios
        .get(this.routes.company.GET_USER_OF_COMPANY + "/" + this.company_id, {
          params: {
            keyword: this.search_key_word,
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
          this.$store.dispatch("disableLoading");
        });
    },

    searchUser: function() {
      if (this.search_key_word === '') {
        this.getUserList(1);
      }
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
    }
  }
};
</script>

<style>
</style>
