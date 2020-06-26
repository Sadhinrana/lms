<template>
  <div class="mdk-drawer-layout__content page">
    <div class="container-fluid page__container">
      <h1 class="h2">Edit Student</h1>

      <div class="card">
        <ul class="nav nav-tabs nav-tabs-card">
		
          <li class="nav-item">
            <a class="nav-link active" href="#third" data-toggle="tab">Exam Results</a>
          </li>
		  
          <!--<li class="nav-item">
            <a class="nav-link" href="#second" data-toggle="tab">Courses</a>
          </li>
          
		  
		  <li class="nav-item">
            <a class="nav-link" href="#forth" data-toggle="tab">Unlock Exams</a>
          </li>
		  
		  <li class="nav-item">
            <a class="nav-link" href="#first" data-toggle="tab">Account</a>
          </li>-->
		  
        </ul>
        <div class="tab-content card-body">
          <div class="tab-pane" id="first">
            <form
              enctype="multipart/form-data"
              @submit.prevent="updateUserInfo()"
              class="form-horizontal"
            >
              <div class="form-group row">
                <label for="avatar" class="col-sm-3 col-form-label form-label">Avatar</label>
                <div class="col-sm-9">
                  <div class="media align-items-center">
                    <div class="media-left">
                      <div class="icon-block rounded">
                        <!-- <i class="material-icons text-muted-light md-36">photo</i> -->
                        <img v-if="userData.avatar_url" :src="userData.avatar_url">
                        <img
                          v-else
                          :src="routes.server_api+ '/img/default_avatar.jpg'"
                        >
                      </div>
                    </div>
                    <div class="media-body">
                      <div class="custom-file" style="width: auto;">
                        <input
                          type="file"
                          id="avatar"
                          accept="image/*"
                          @change="onImageInputChanged"
                          @click="resetField('image')"
                          class="custom-file-input"
                        >
                        <label for="avatar" class="custom-file-label">Choose file</label>
                        <p v-show="error_messages.image.show">{{error_messages.image.message}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label form-label">Full Name</label>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-md-6">
                      <input
                        id="name"
                        type="text"
                        class="form-control"
                        placeholder="First Name"
                        v-model="userData.first_name"
                        @click="resetField('first_name')"
                      >
                      <p
                        v-show="error_messages.first_name.show"
                      >{{error_messages.first_name.message}}</p>
                      <p v-show="error_messages.last_name.show">{{error_messages.last_name.message}}</p>
                    </div>
                    <div class="col-md-6">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Last Name"
                        v-model="userData.last_name"
                        @click="resetField('last_name')"
                      >
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label form-label">Email</label>
                <div class="col-sm-6 col-md-6">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="material-icons md-18 text-muted">mail</i>
                      </div>
                    </div>
                    <input
                      type="text"
                      id="email"
                      class="form-control"
                      placeholder="Email Address"
                      v-model="userData.email"
                      @click="resetField('email')"
                    >
                    <p v-show="error_messages.email.show">{{error_messages.email.message}}</p>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="user-role" class="col-sm-3 col-form-label form-label">User Role</label>
                <div class="col-sm-6 col-md-6">
                  <select id="user-role" class="form-control custom-select" v-model="userData.role">
                    <option selected disabled>Select Role</option>
                    <!-- <option value="1">Headmaster</option> -->
                    <!-- <option value="2">Content Manager</option> -->
                    <!-- <option value="3">Company Head</option> -->
                    <option value="4">Instructor</option>
                    <option value="5">Student</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="user-role" class="col-sm-3 col-form-label form-label">Company</label>
                <div class="col-sm-6 col-md-6">
                  <select
                    id="company_head"
                    class="form-control custom-select"
                    v-model="userData.company"
                  >
                    <option selected disabled>Select Company</option>
                    <option v-for="company in company_list.data" :value="company.id">{{ company.name }}</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="password" class="col-sm-3 col-form-label form-label">Change Password</label>
                <div class="col-sm-6 col-md-4">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="material-icons md-18 text-muted">lock</i>
                      </div>
                    </div>
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      placeholder="Enter new password"
                      v-model="userData.password"
                      @click="resetField('password')"
                    >
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label
                  for="password"
                  class="col-sm-3 col-form-label form-label"
                >Password confirmation</label>
                <div class="col-sm-6 col-md-4">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="material-icons md-18 text-muted">lock</i>
                      </div>
                    </div>
                    <input
                      type="password"
                      id="password_confirmation"
                      class="form-control"
                      placeholder="Retype new password"
                      v-model="userData.password_confirmation"
                      @click="resetField('password_confirmation')"
                    >
                    <p
                      v-show="error_messages.password_confirmation.show"
                    >{{error_messages.password_confirmation.message}}</p>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-8 offset-sm-3">
                  <div class="media align-items-center">
                    <div class="media-left">
                      <button href="#" class="btn btn-success">Save Changes</button>
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
              Added new company
              <strong>successfully !</strong>
            </div>
            <div
              class="alert alert-dismissible bg-danger text-white border-0 fade"
              :class="added_error?'show':''"
              v-for="error in added_error_messages.errors"
              role="alert"
            >
              <strong>Oops !</strong>
              {{error[0]}}
            </div>
          </div>

          <div class="tab-pane" id="second">
            <div class="form-group row">
              <label
                for="name"
                class="col-sm-3 col-form-label form-label"
              >Enroll a Student</label>
              <div class="col-sm-8">
                <div class="row">
                  <div class="col-md-6">
                    <model-select
                      :options="course_list"
                      v-model="txt_course"
                      placeholder="Select course"
                    ></model-select>
                  </div>
                  <div class="col-md-6">
                    <button href="#" class="btn btn-success" @click.prevent="assignCourse">Enroll</button>
                  </div>
                </div>
              </div>
            </div>

            <table class="table mb-0">
              <thead>
                <tr>
                  <th>Course name</th>
                  <th>Created at</th>
                  <th style="width: 24px;"></th>
                </tr>
              </thead>
              <tbody class="list" id="search">
                <tr v-for="course in assigned_course">
                  <td>
                    <span class="js-lists-values-employee-name">{{course.course_content.title}}</span>
                  </td>
                  <td>{{course.created_at}}</td>
                  <td class="text-right">
                    <a href="#" class="text-muted" data-toggle="dropdown">
                      <i class="material-icons">more_vert</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a href="#" @click.prevent="deleteCourse(course.id)" class="dropdown-item">Remove</a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <div
              class="alert alert-dismissible bg-success text-white border-0 fade"
              :class="added_success?'show':''"
              role="alert"
            >
              {{success_status}}
              <strong>successfully !</strong>
            </div>
            <!-- <div -->
              <!-- class="alert alert-dismissible bg-danger text-white border-0 fade" -->
              <!-- :class="added_error?'show':''" -->
              <!-- v-for="error in added_error_messages.errors" -->
              <!-- role="alert" -->
            <!-- > -->
              <!-- <strong>Error !</strong> -->
              <!-- {{error[0]}} -->
            <!-- </div> -->
			<div
              class="alert alert-dismissible bg-danger text-white border-0 fade"
              :class="added_error?'show':''"
              role="alert"
            >
              <strong>Oops !</strong>
              {{fail_status}}
            </div>
          </div>

          <div class="tab-pane active" id="third">
            <div class="table-responsive">
              <table class="table table-middle">
                <thead>
                  <tr>
                    <th>Exam</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Result</th>
                    <th>Points</th>
                   <!--  <th>Review</th> -->
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(quiz,index) in quizzes" :key="quiz.id">
                    <td>
                      <router-link
                        :to="{ name: 'review_quized',
                                              params:{
                                                user_id:$route.params.id,
                                                quiz_id:quiz.quiz.id,
                                                attempt_id:quiz.id
                                              }}"
                      >{{quiz.quiz.title}}</router-link>
                    </td>
                    <td>
                      <span class="badge badge-light">{{quiz.submitted}}</span>
                    </td>
                    <td>{{quiz.spent_time}} min</td>
                    <td>
                      <span
                        class="badge badge-success"
                        v-if="quiz.quiz.grade_to_pass <= quiz.total_score"
                      >PASSED</span>
                      <span class="badge badge-danger" v-else>FAILED</span>
                    </td>
                    <td>{{quiz.total_score}}</td>
                    <!-- <td>
                      <router-link
                        :to="{ name: 'review_quiz',
                                              params:{
                                                user_id:$route.params.id,
                                                quiz_id:quiz.quiz.id,
                                                attempt_id:quiz.id
                                              }}"
                      >Review</router-link>
                    </td> -->
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
		  
		  <div class="tab-pane" id="forth">
            <div class="form-group row">
              <label
                for="name"
                class="col-sm-3 col-form-label form-label"
              >Unlock to Take Exam</label>
              <div class="col-sm-8">
                <div class="row">
                  <div class="col-md-6">
                    <model-select
                      :options="course_list"
                      v-model="txt_course"
                      placeholder="Select course"
                    ></model-select>
                  </div>
                  <div class="col-md-6">
                    <button href="#" id="myfetchbtn" class="btn btn-success" @click.prevent="getAssignedQuiz">View</button>
                  </div>
                </div>
              </div>
            </div>

            <table class="table mb-0">
              <thead>
                <tr>
                  <th>Exams</th>
                  <th>Status</th>
                  <th style="width: 24px;"></th>
                </tr>
              </thead>
              <tbody class="list" id="search">
                <tr v-for="quiz in quiz_data">
                  <td>
                    <span class="js-lists-values-employee-name">{{quiz.quiz_name}}</span>
                  </td>
				  <td>
					  <span v-if="quiz.is_block == 1" :class="['badge badge-pill', 'badge-danger']">Locked</span>
					  <span v-if="quiz.is_block == 2" :class="['badge badge-pill', 'badge-success']">Unlocked</span>
                  </td>
                  <td class="text-right">
                    <a href="#" class="text-muted" data-toggle="dropdown">
                      <i class="material-icons">more_vert</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a href="#" v-if="quiz.is_block == 2" @click.prevent="blockQuiz(quiz.id,1)" class="dropdown-item">Lock</a>
                      <a href="#" v-if="quiz.is_block == 1" @click.prevent="blockQuiz(quiz.id,2)" class="dropdown-item">Unlock</a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <div
              class="alert alert-dismissible bg-success text-white border-0 fade"
              :class="added_success?'show':''"
              role="alert"
            >
              {{success_status}}
              <strong>successfully !</strong>
            </div>
            
			<div
              class="alert alert-dismissible bg-danger text-white border-0 fade"
              :class="added_error?'show':''"
              role="alert"
            >
              <strong>Oops !</strong>
              {{fail_status}}
            </div>
          </div>
		  
		  
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ModelSelect } from "vue-search-select";
export default {
  data() {
    return {
      assigned_course: [],
	  assigned_quiz:[],
      txt_course: "",
      course_list: [],
      userData: {},
      company_test: "",
	  success_status: "",
	  fail_status: "",
      added_success: false,
      added_error: false,
      added_error_messages: [],
      company_list: [],
      quizzes: [],
	  quiz_data:[],
      error_messages: {
        image: {
          message: "",
          show: false
        },

        email: {
          message: "",
          show: false
        },

        password: {
          message: "",
          show: false
        },

        password_confirmation: {
          message: "",
          show: false
        },

        last_name: {
          message: "",
          show: false
        },

        first_name: {
          message: "",
          show: false
        },

        company: {
          message: "",
          show: false
        }
      }
    };
  },

  mounted() {
    this.getUserInfoById();
    this.fetchListCompany();
    this.fetchListCourse();
    this.getAssignedCourse();
    this.getQuizzes();
  },

  methods: {
    resetField(field_name) {
      for (var key in this.error_messages) {
        if (key == field_name) {
          this.error_messages[key].show = false;
        }
      }
    },

    getQuizzes() {
      axios
        .get(this.routes.quiz.GET_QUIZ_ATTEMPTS_BY_USER + this.$route.params.id)
        .then(response => {
          this.quizzes = response.data.data;
          this.quizzes.forEach((q, index) => {
		  console.log(q);
		    let start_time = moment(q.start_time);
            let end_time = moment(q.end_time);
		    this.$set(this.quizzes[index], "spent_time", moment.duration(end_time.diff(start_time)).minutes());
			
			this.$set(this.quizzes[index], "grade_to_pass", q.quiz.grade_to_pass);
			this.$set(this.quizzes[index], "total_score", q.quiz_attempt.total_score);
			this.$set(this.quizzes[index],"submitted",moment(q.quiz_attempt.updated_at).format('DD/MM'));
            //this.fetchQuestion(this.$route.params.id, q.quiz.id, q.id, index);
          });
        });
    },

    fetchQuestion(user_id, quiz_id, attempt_id, index) {
      axios
        .get(
          this.routes.quiz.GET_RESULT +
            "/" +
            user_id +
            "/" +
            quiz_id +
            "/" +
            attempt_id
        )
        .then(res => {
          //let total_score = 0;
          /*res.data.forEach(i => {
            if (i[0].question.child_questions == "" && i.result)
              total_score += i[0].question.score * i.result;
          });*/

          //Vue cannot reactive aditional data so we have to set like this :
         // this.$set(this.quizzes[index], "total_score", total_score);
          //this.$set(this.quizzes[index],"submitted",moment(res.data[res.data.length - 1][0].updated_at).format('DD/MM'));
        })
        .catch(err => {
          //console.log(err.response);
        });
    },

    getUserInfoById() {
      axios
        .get(this.routes.admin.GET_USER_INFO_BY_ID, {
          params: { id: this.$route.params.id }
        })
        .then(response => {
          this.userData = response.data;
          this.userData.avatar_url = this.userData.avatar_url
            ? this.routes.server_api + this.userData.avatar_url
            : "";
          // this.userData.role =
          //   this.userData.role == "student" ? "5" : this.userData.role;
          switch (this.userData.role) {
            case "headmaster":
              this.userData.role = "1";
              break;
            case "content_manager":
              this.userData.role = "2";
              break;
            case "company_head":
              this.userData.role = "3";
              break;
            case "instructor":
              this.userData.role = "4";
              break;
            case "student":
              this.userData.role = "5";
              break;
          }
        });
    },

    onImageInputChanged(event) {
      var file = event.target.files[0];
      var reader = new FileReader();
      reader.readAsBinaryString(file);

      reader.onload = () => {
        this.userData.avatar_url =
          "data:image/jpeg;base64," + btoa(reader.result);
        this.userData.image = "data:image/jpeg;base64," + btoa(reader.result);
      };
      reader.onerror = () => {
        console.log("there are some problems");
      };
    },

    updateUserInfo() {
      this.$store.dispatch("enableLoading");
      axios
        .patch(this.routes.admin.UPDATE_USER, this.userData)
        .then(response => {
          this.userData.avatar_url =
            this.routes.server_api + response.data.avatar_url;
          this.added_success = true;
          this.added_error = false;
          this.$router.push({ name: "admin_manange_user" });
          this.$store.dispatch("disableLoading");
        })
        .catch(err => {
          this.added_error_messages = err.response.data;
          this.added_error = true;
          this.added_success = false;
          this.$store.dispatch("disableLoading");
        });
    },
    fetchListCompany() {
      axios
        .get(this.routes.company.SHOW_COMPANY)
        .then(res => {
          this.company_list = res.data;
          this.userData.company = this.userData.company_id;
        })
        .catch(err => {
          console.log(err.response);
        });
    },
    fetchListCourse() {
      axios
        .get(this.routes.course.GET_ALL)
        .then(res => {
          this.course_list = res.data.map(item => {
            var output = {};
            output["value"] = item.id;
            output["text"] = item.title;
            return output;
          });
        })
        .catch(err => {
          console.log(err.response);
        });
    },
    assignCourse() {
      var studentcourse_data = {
        txt_course: this.txt_course,
        txt_student: this.userData.id
      };
      axios
        .post(this.routes.course.ADD_COURSE_TO_STUDENT, studentcourse_data)
        .then(res => {
		  this.success_status = "The student enrolled ";
          this.added_success = true;
          this.added_error = false;
          this.getAssignedCourse();
        })
        .catch(err => {
          this.added_error_messages = err.response.data;
		  this.fail_status = "The student has been already enrolled.";
          this.added_error = true;
          this.added_success = false;
        });
    },
    getAssignedCourse() {
      axios
        .get(
          this.routes.course.GET_COURSE_BY_STUDENT + "/" + this.$route.params.id
        )
        .then(res => {
          this.assigned_course = res.data.data;
        })
        .catch(err => {
          console.log(err.data);
        });
    },
	getAssignedQuiz() {
	this.quiz_data = [];
	var studentcourse_data = {
        course_id: this.txt_course,
        student_id: this.userData.id
      };
	this.$store.dispatch("enableLoading");
      axios
        .post(this.routes.quiz.GET_QUIZ_BLOCK, {
          data: studentcourse_data
        }).then(response => {
		this.$store.dispatch("disableLoading");
		this.quiz_data = response.data;
		});
    },
	
	blockQuiz(id,status){
		axios
			.post(this.routes.quiz.QUIZ_BLOCK_UPDATE, {
			   id: id,
			   is_block:status
			}).then(response => {
			
			 $('#myfetchbtn').trigger('click');
			  
				//this.getAssignedQuiz();
			});
	},
	deleteCourse(id){
	this.$store.dispatch("enableLoading");
		axios
			.post(this.routes.course.DELETE_STUDENT_COURSE, {id:id})
			.then(res => {
			  this.$store.dispatch("disableLoading");
			  this.success_status = "The Course deleted ";
			  this.added_success = true;
			  this.added_error = false;
			  this.getAssignedCourse();
			})
			.catch(err => {
			  this.$store.dispatch("disableLoading");
			  this.added_error_messages = err.response.data;
			  this.added_error = true;
			  this.added_success = false;
			});
	}
	
  },
  components: {
    ModelSelect
  }
};
</script>

<style scoped lang="scss">
.media-left {
  img {
    max-width: 100%;
  }
}
</style>
