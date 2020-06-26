<template>
  <div class="mdk-drawer-layout__content page">

    <div class="container-fluid page__container">
      <h1 class="h2">Assign Course to company</h1>

      <div class="card">
        <ul class="nav nav-tabs nav-tabs-card">
          <li class="nav-item">
            <a
              class="nav-link active"
              href="#first"
              data-toggle="tab"
            >New Course For Company</a>
          </li>
        </ul>
        <div class="tab-content card-body">
          <div class="tab-pane active" id="first">
            <form
              action="#"
              class="form-horizontal"
            >

              <div class="form-group row">
                <label
                  for="company_head"
                  class="col-sm-3 col-form-label form-label"
                >Company</label>
                <div class="col-sm-6 col-md-6">
                  <div class="input-group">
                    <model-select :options="company_list"
                                  v-model="companycourse_data.txt_company"
                                  placeholder="Select course">
                     </model-select>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label
                  for="company_head"
                  class="col-sm-3 col-form-label form-label"
                >Course</label>
                <div class="col-sm-6 col-md-6">
                  <div class="input-group">
                    <model-select :options="course_list"
                                  v-model="companycourse_data.txt_course"
                                  placeholder="Select course">
                     </model-select>
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
                        @click.prevent="assignCourse"
                      >Assign Course</a>
                    </div>

                  </div>
                </div>
              </div>
            </form>

            <table class="table mb-0 table-striped table-hover">
              <thead>
              <tr>
                <th>Course</th>
                <th>Company</th>
                <th>Created at</th>
                <th></th>
              </tr>
              </thead>
              <tbody class="list" id="search">
              <tr v-for="assigned_course in assigned_courses" :key="assigned_course.id">
                <td>
                  <span class="js-lists-values-employee-name">{{assigned_course.course.title}}</span>
                </td>
                <td>
                  <span class="js-lists-values-employee-name">{{assigned_course.company.name}}</span>
                </td>
                <td>{{assigned_course.created_at}}</td>
                <td class="text-right">
                  <a href="#" class="text-muted" data-toggle="dropdown">
                    <i class="material-icons">more_vert</i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" @click.prevent="deleteAssignedCourse(assigned_course.id)" class="dropdown-item">Remove</a>
                  </div>
                </td>
              </tr>
              </tbody>
            </table>

            <!-- Pagination -->
            <ul class="pagination justify-content-center pagination-sm mt-5" v-if="assigned_courses.length">
              <li v-if="pagination.current_page>1" class="page-item">
                <a @click.prevent="fetchListAssignedCourse(pagination.current_page - 1)" class="page-link" href="#" aria-label="Previous">
                  <span aria-hidden="true" class="material-icons">chevron_left</span>
                  <span>Prev</span>
                </a>
              </li>

              <li :key="page" v-for="page in pagesNumber" :class="[page === pagination.current_page ? 'active' : null , 'page-item']">
                <a href="#" v-on:click.prevent="fetchListAssignedCourse(page)" class="page-link" aria-label="Previous" >{{ page }}</a>
              </li>

              <li v-if="pagination.current_page < pagination.last_page">
                <a href="javascript:void(0)" aria-label="Next" v-on:click.prevent="fetchListAssignedCourse(pagination.current_page + 1)">
                  <span aria-hidden="true"></span>
                  <span aria-hidden="true" class="material-icons">chevron_right</span>
                </a>
              </li>
            </ul>

            <div class="alert alert-dismissible bg-success text-white border-0 fade" :class="added_success ? 'show' : 'd-none'" role="alert">
                Assign new course to company <strong>successfully !</strong>
            </div>
			
			<div class="alert alert-dismissible bg-danger text-white border-0 fade" :class="added_error ? 'show' : 'd-none'" role="alert">
                <strong>Oops ! </strong> {{error}}
            </div>
          </div>


        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { ModelSelect } from 'vue-search-select'
export default {
  data(){
    return{
      companycourse_data:{
        txt_student:'',
        txt_course:'',
      },
      company_list:[],
      course_list:[],
      instructor_list:[],
      assigned_courses:[],
      added_success:false,
      added_error:false,
      added_error_messages:[],
	  error: "",
      pagination: {
        total: 0,
        per_page: 10,
        from: 0,
        to: 0,
        current_page: 1
      },
      offset: 4,
    };
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
  mounted(){
    this.fetchListCompany();
    this.fetchListCourse();
    this.fetchListAssignedCourse(1);
  },
  methods:{
    assignCourse(){
	this.$store.dispatch("enableLoading");
      axios.post(this.routes.course.ADD_COURSE_TO_COMPANY, this.companycourse_data)
	  .then(res => {
        this.fetchListAssignedCourse(1);
        this.$router.push({name:'assign_course_company'});
		this.$store.dispatch("disableLoading");
        this.added_error = false;
		this.added_success = true;
		var self = this;
		setTimeout(function() {
        self.added_success = false;
          }, 4000);
      }).catch(err => {
		this.$store.dispatch("disableLoading");
        this.added_error_messages = err.response.data;
		if(err.response.data == 'Course is Duplicate'){
			this.error = "Oops! The student has been already enrolled";
		}else{
			this.error = "Something wrong";
		}
        this.added_success = false;
        this.added_error = true;
		var self = this;
		setTimeout(function() {
        self.added_error = false;
          }, 4000);
      });
    },

    fetchListCourse(){
		this.$store.dispatch("enableLoading");
      axios.get(this.routes.course.GET_ALL).then(res => {
        this.course_list = res.data.map(item => {
          var output = {}
          output['value'] = item.id
          output['text'] = item.title
          return output;
        });
		this.$store.dispatch("disableLoading");
      }).catch(err => {
        console.log(err.data);
		this.$store.dispatch("disableLoading");
      });
    },

    fetchListCompany(){
		this.$store.dispatch("enableLoading");
      axios.get(this.routes.company.SHOW_COMPANY).then(res => {
        this.company_list = res.data.map(item => {
          var output = {}
          output['value'] = item.id
          output['text'] = item.name
          return output;
        });
		this.$store.dispatch("disableLoading");
      }).catch(err => {
        console.log(err.data);
		this.$store.dispatch("disableLoading");
      });
    },

    fetchListAssignedCourse(page) {
      this.$store.dispatch("enableLoading");
      axios.get(this.routes.admin.ASSIGNED_COMPANY_COURSES, { params: { page: page } }).then(response => {
        if (response.data.status === 200) {
          this.assigned_courses = response.data.data.data;
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
        console.log(err.data);
		this.$store.dispatch("disableLoading");
      });
    },

    deleteAssignedCourse(id) {
      let _this = this;
      swal({
        title: "Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((ok) => {
        if (ok) {
          _this.$store.dispatch("enableLoading");
          axios
                  .post(_this.routes.admin.DELETE_COMPANY_COURSE, {id: id})
                  .then(response => {
                    _this.$store.dispatch("disableLoading");
                    swal({
                      position: 'top-end',
                      icon: 'success',
                      title: response.data.msg,
                      showConfirmButton: false,
                      timer: 1500
                    });
                    _this.fetchListAssignedCourse();
                  })
                  .catch(err => {
                    _this.$store.dispatch("disableLoading");
                    _this.added_error_messages = err.response.data;
                    _this.added_error = true;
                    _this.added_success = false;
                  });
        }
      });
    },
  },
  components: {
    ModelSelect
  }
};
</script>

<style>
</style>
