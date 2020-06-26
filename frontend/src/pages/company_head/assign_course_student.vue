<template>
  <div class="mdk-drawer-layout__content page">

    <div class="container-fluid page__container">
      <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item"><router-link :to="{ name: 'home'}">Home</router-link></li>
        <li class="breadcrumb-item active">Assign Course to student</li>
      </ol> -->
      <h1 class="h2">Assign Course to student</h1>

      <div class="card">
        <ul class="nav nav-tabs nav-tabs-card">
          <li class="nav-item">
            <a
              class="nav-link active"
              href="#first"
              data-toggle="tab"
            >New Course For Student</a>
          </li>
        </ul>
        <div class="tab-content card-body">
          <div
            class="tab-pane active"
            id="first"
          >
            <form
              action="#"
              class="form-horizontal"
            >

              <div class="form-group row">
                <label
                  for="company_head"
                  class="col-sm-3 col-form-label form-label"
                >Student</label>
                <div class="col-sm-6 col-md-6">
                  <div class="input-group">
                    <model-select :options="student_list"
                                  v-model="studentcourse_data.txt_student"
                                  placeholder="Select student">
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
                                  v-model="studentcourse_data.txt_course"
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
            <div class="alert alert-dismissible bg-success text-white border-0 fade" :class="added_success?'show':''" role="alert">
                Student enrolled <strong>successfully !</strong>
            </div>
            <div class="alert alert-dismissible bg-danger text-white border-0 fade" :class="added_error?'show':''" role="alert">
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
      studentcourse_data:{
        txt_student:'',
        txt_course:'',
      },
      student_list:[],
      course_list:[],
      added_success:false,
      added_error:false,
      added_error_messages:[],
	  error:'',
    };
  },
  mounted(){
    this.fetchListStudent(),
    this.fetchListCourse()
  },
  methods:{
    getSelection(data){
      console.log(data)
    },
    assignCourse(){
	this.$store.dispatch("enableLoading");
      axios.post(this.routes.course.ADD_COURSE_TO_STUDENT, this.studentcourse_data)
	  .then(res => {
	  console.log(res);
        this.$router.push({name:'assign_course'});
		this.$store.dispatch("disableLoading");
		this.added_error = false;
        this.added_success = true;
		var self = this;
		setTimeout(function() {
        self.added_error = false;
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
	  this.$store.dispatch("disableLoading");
        console.log(err.data)
      });
    },

    fetchListStudent(){
	this.$store.dispatch("enableLoading");
      axios.get(this.routes.user.GET_USER_LIST).then(res => {
        this.student_list = res.data.map(item => {
          var output = {}
          output['value'] = item.id
          output['text'] = item.first_name + ' ' + item.last_name
          return output;
        });
		this.$store.dispatch("disableLoading");
      }).catch(err => {
	  this.$store.dispatch("disableLoading");
        console.log(err.data)
      });
    }
  },
  components: {
    ModelSelect
  }
};
</script>

<style>
</style>
/* eslint-disable */
