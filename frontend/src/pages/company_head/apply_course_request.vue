<template>
  <div class="page">

    <div class="container page__container">
      <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link :to="{ name: 'home'}">Home</router-link>
        </li>
        <li class="breadcrumb-item">Company Head</li>
        <li class="breadcrumb-item active">Manage Users Request</li>
      </ol> -->

      <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
        <div class="flex mb-2 mb-sm-0">
          <h1 class="h2">User Apply course requests</h1>
        </div>
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

                <table class="table mb-0">
                  <thead>
                    <tr>

                      <!-- <th style="width: 18px;">
                        <div class="custom-control custom-checkbox">
                          <input
                            type="checkbox"
                            class="custom-control-input js-toggle-check-all"
                            data-target="#staff"
                            id="customCheckAll"
                          >
                          <label
                            class="custom-control-label"
                            for="customCheckAll"
                          ><span class="text-hide">Toggle all</span></label>
                        </div>
                      </th> -->
                      <th>Course name</th>
                      <th>User name</th>
                      <th>Email</th>
                      <th>Company name</th>
                      <th>Status</th>
                      <th style="width: 24px;"></th>
                    </tr>
                  </thead>
                  <tbody
                    class="list"
                    id="staff"
                  >

                    <tr
                      :key="request.id"
                      :class="['selected',request.is_approved ? 'approved' :'' , request.is_rejected ? 'rejected' : '']"
                      v-for="request in requests"
                    >

                      <!-- <td>
                        <div class="custom-control custom-checkbox">
                          <input
                            type="checkbox"
                            class="custom-control-input js-check-selected-row"
                            checked=""
                            id="customCheck1_1"
                          >
                          <label
                            class="custom-control-label"
                            for="customCheck1_1"
                          ><span class="text-hide">Check</span></label>
                        </div>
                      </td> -->

                      <td><span class="js-lists-values-employee-name">{{request.course.title}}</span></td>
                      <td><span class="js-lists-values-employee-name">{{request.student.first_name + " " + request.student.last_name}}</span></td>
                      <td><span class="">{{request.student.email}}</span></td>
                      <td><span
                          v-if="request.student.company_learning"
                          class=""
                        >{{request.student.company_learning.name}}</span></td>
                      <td v-if="request.is_approved">
                        Approved
                      </td>
                      <td v-else-if="request.is_rejected">
                        Rejected
                      </td>
                      <td v-else>Not decided</td>
                      <td>
                        <a
                          href="#"
                          class="text-muted"
                          data-toggle="dropdown"
                        >
                          <i class="material-icons">more_vert</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                          <a
                            v-show="!request.is_approved"
                            href="#"
                            @click.prevent="acceptRequest(request)"
                            class="dropdown-item"
                          >
                            Accept request
                          </a>
                          <a
                            v-show="!request.is_rejected"
                            href="#"
                            class="dropdown-item"
                            @click.prevent="rejectRequest(request)"
                          >Reject request</a>

                          <a
                            href="#"
                            @click.prevent="forceStudentToReregister(request)"
                            class="dropdown-item"
                          >
                            Re-register
                          </a>
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
      <!-- <ul class="pagination justify-content-center pagination-sm">
        <li
          v-if="pagination.current_page>1"
          class="page-item"
        >
          <a
            @click="getUserList(pagination.current_page - 1)"
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
            href="
          javascript:void(0)"
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

      </ul> -->

    </div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      requests: [],
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
    this.getRequests();
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
      let to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }
      let pagesArray = [];
      for (let page = from; page <= to; page++) {
        pagesArray.push(page);
      }
      return pagesArray;
    }
  },

  methods: {
    forceStudentToReregister(request) {
	this.$store.dispatch("enableLoading");
      axios
        .post(this.routes.company.FORCE_STUDENT_TO_REREGISTER, request)
        .then(response => {
          setTimeout(() => {
            this.requests = this.requests.filter(r => {
              return r.id != request.id;
            });
          }, 1500);
		  this.$store.dispatch("disableLoading");
        }).catch(err => {
			this.$store.dispatch("disableLoading");
		});
    },

    getRequests() {
	this.$store.dispatch("enableLoading");
      axios.get(this.routes.company.GET_USER_REQUESTS)
	  .then(response => {
        this.requests = response.data.data;
		this.$store.dispatch("disableLoading");
      }).catch(err => {
			this.$store.dispatch("disableLoading");
		});
    },

    acceptRequest(request) {
	this.$store.dispatch("enableLoading");
      axios
        .patch(this.routes.company.ACCEPT_REQUEST, {
          id: request.id
        })
        .then(response => {
          request.is_approved = true;
          setTimeout(() => {
            this.requests = this.requests.filter(r => {
              return r.id != request.id;
            });
          }, 1500);
		  this.$store.dispatch("disableLoading");
        }).catch(err => {
			this.$store.dispatch("disableLoading");
		});
    },

    rejectRequest(request) {
	this.$store.dispatch("enableLoading");
      axios
        .patch(this.routes.company.REJECT_REQUEST, {
          id: request.id
        })
        .then(response => {
          request.is_rejected = true;
          setTimeout(() => {
            this.requests = this.requests.filter(r => {
              return r.id != request.id;
            });
          }, 1500);
		  this.$store.dispatch("disableLoading");
        }).catch(err => {
			this.$store.dispatch("disableLoading");
		});
    }
  }
};
</script>

<style>
tr.approved {
  background: #d9edf7;
}
tr.rejected {
  background: #fcf8e3;
}
</style>
