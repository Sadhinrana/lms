<template>
  <div class="mdk-drawer-layout__content page">

    <!-- /modal -->
    <div class="container-fluid page__container">
      <h1 class="h2">Edit Student</h1>

      <div class="card">
        <ul class="nav nav-tabs nav-tabs-card">
          <li class="nav-item">
            <a class="nav-link active" href="#third" data-toggle="tab" @click="getQuizzes(1)">Exam Results</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#essay-result" data-toggle="tab" @click="getStudentEssaysAnswers(1)">Essay Results</a>
          </li>
        </ul>
        <div class="tab-content card-body">
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
                    <!--<th>Review</th>-->
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(quiz,index) in quizzes" :key="quiz.id">
                    <td v-if="$store.getters.authUser.role == 'instructor'">
                      <router-link
                        :to="{ name: 'review_quiz',
                                              params:{
                                                user_id:$route.params.id,
                                                quiz_id:quiz.quiz.id,
                                                attempt_id:quiz.id
                                              }}"
                      >{{quiz.quiz.title}}</router-link>
                    </td>
                    <td v-else>{{ quiz.quiz.title }}</td>
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
                    <!--<td>
                      <a href="javascript:void(0)"  @click="getScore($route.params.id,quiz.quiz.id,quiz.id)">Review</a>
                    </td>-->
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- Pagination -->
            <ul class="pagination justify-content-center pagination-sm">
              <li v-if="pagination.current_page>1" class="page-item">
                <a @click.prevent="getQuizzes(pagination.current_page - 1)" class="page-link" href="#" aria-label="Previous">
                  <span aria-hidden="true" class="material-icons">chevron_left</span>
                  <span>Prev</span>
                </a>
              </li>

              <li :key="page" v-for="page in pagesNumber" :class="[page == pagination.current_page ? 'active' : null , 'page-item']">
                <a href="#" v-on:click.prevent="getQuizzes(page)" class="page-link" aria-label="Previous" >{{ page }}</a>
              </li>

              <li v-if="pagination.current_page < pagination.last_page">
                <a href="javascript:void(0)" aria-label="Next" v-on:click.prevent="getQuizzes(pagination.current_page + 1)">
                  <span aria-hidden="true"></span>
                  <span aria-hidden="true" class="material-icons">chevron_right</span>
                </a>
              </li>
            </ul>
          </div>

          <div class="tab-pane" id="essay-result">
            <div class="card card-body border-left-3 border-left-primary navbar-shadow mb-4">
              <form action="#" @submit.prevent="getStudentEssaysAnswers(1)">
                <div class="d-flex flex-wrap2 align-items-center mb-headings">
                  <div class="flex search-form ml-3 search-form--light">
                    <input type="text" class="form-control" placeholder="Search essays..." id="searchSample02" v-model="search_key_word" @change="searchEssays">
                    <button class="btn" type="button" role="button" @click="getStudentEssaysAnswers(1)"><i class="material-icons">search</i></button>
                  </div>
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center" style="white-space: nowrap;">
                  <small class="flex text-muted text-uppercase mr-3 mb-2 mb-sm-0">Displaying {{pagination.from}} to {{pagination.to}} out of {{pagination.total}} essay answers</small>
                </div>
              </form>
            </div>

            <div class="card" v-if="essayAnswers.length">
              <div class="table-responsive card-body">
                <table class="table table-middle">
                  <thead>
                  <tr>
                    <th>Student</th>
                    <th>Essay</th>
                    <th>Question</th>
                    <th>Date</th>
                    <th>Reviews</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(essayAnswer, index) in essayAnswers" :key="essayAnswer.id">
                    <td>
                      <div class="media align-items-center">
                        <div class="avatar avatar-sm mr-3">
                          <img :src="routes.server_api + essayAnswer.user.avatar_url" alt="Avatar" class="avatar-img rounded-circle" v-if="essayAnswer.user.avatar_url">
                          <img :src="routes.server_api+ '/img/default_avatar.jpg'" alt="" class="avatar-img rounded-circle" v-else>
                        </div>
                        <div class="media-body">
                                    <span class="js-lists-values-employee-name">
                                        <a href="javascript:void(0)">{{ essayAnswer.user.first_name + " " + essayAnswer.user.last_name }}</a>
                                    </span>
                        </div>
                      </div>
                    </td>
                    <td>{{ essayAnswer.essay.title }}</td>
                    <td>{{ essayAnswer.essay.question }}</td>
                    <td>{{ essayAnswer.created_at }}</td>
                    <td>
                      <a href="javascript:void(0)" @click="reviewAnswer(index)" v-if="essayAnswer.essay.type === 'unit' || user_info.role !== 'head_teacher'">
                        Reviews({{ essayAnswer.reviews.length }}) <span v-if="getUnseenReview(index)">({{ getUnseenReview(index) }} unseen)</span>
                      </a>
                      <a href="javascript:void(0)" v-else>N/A</a>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div v-else class="alert alert-light alert-dismissible border-1 border-left-3 border-left-warning" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>

              <div class="text-black-70">No results found.</div>
            </div>

            <!-- Pagination -->
            <ul class="pagination justify-content-center pagination-sm" v-if="essayAnswers.length">
              <li v-if="pagination.current_page>1" class="page-item">
                <a @click.prevent="getStudentEssaysAnswers(pagination.current_page - 1)" class="page-link" href="#" aria-label="Previous">
                  <span aria-hidden="true" class="material-icons">chevron_left</span>
                  <span>Prev</span>
                </a>
              </li>

              <li :key="page" v-for="page in pagesNumber" :class="[page === pagination.current_page ? 'active' : null , 'page-item']">
                <a href="#" v-on:click.prevent="getStudentEssaysAnswers(page)" class="page-link" aria-label="Previous" >{{ page }}</a>
              </li>

              <li v-if="pagination.current_page < pagination.last_page">
                <a href="javascript:void(0)" aria-label="Next" v-on:click.prevent="getStudentEssaysAnswers(pagination.current_page + 1)">
                  <span aria-hidden="true"></span>
                  <span aria-hidden="true" class="material-icons">chevron_right</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- modal-->
    <!--<div
            class="modal fade"
            id="blockForm1"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h4 class="modal-title text-white">Part score</h4>
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
            <table class="table table-middle">
              <tbody>
              <tr>
                <th>Listening: Part 1 score: </th><th>{{this.score_data?this.score_data.Listening_Part1_got:0}} out of {{this.score_data?this.score_data.Listening_Part1_out_of:0}}</th>
              </tr>
              <tr>
                <th>Voc & Gram. Part 2 score: </th><th>{{this.score_data?this.score_data.VocGramPart2score_got:0}} out of {{this.score_data?this.score_data.VocGramPart2score_out_of:0}}</th>
              </tr>
              <tr>
                <th>Voc & Reading: Part 3 score:  </th><th>{{this.score_data?this.score_data.VocReadingPart3score_got:0}} out of {{this.score_data?this.score_data.VocReadingPart3score_out_of:0}}</th>
              </tr>
              <tr>
                <th>Vocabulary: Part 4 score:  </th><th>{{this.score_data?this.score_data.VocabularyPart4score_got:0}} out of {{this.score_data?this.score_data.VocabularyPart4score_out_of:0}}</th>
              </tr>
              <tr>
                <th>Forming Sen: Part 5 score:  </th><th>{{this.score_data?this.score_data.FormingSenPart5score_got:0}} out of {{this.score_data?this.score_data.FormingSenPart5score_out_of:0}}</th>
              </tr>
              </tbody>
            </table>
              <div class="form-group row mb-0">
                <div class="col">
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>-->
    <!-- /modal -->
    <!-- modal-->
    <div class="modal fade" :id="'essayModal' + modal_index">
      <div class="modal-dialog modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h4 class="modal-title text-white">Review essay</h4>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="table-responsive card-body">
                <table class="table table-middle" v-if="reviews.length">
                  <thead>
                  <tr>
                    <th>User</th>
                    <th>Review</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(review, index) in reviews" :key="review.id">
                    <td>
                      <div class="media align-items-center">
                        <div class="avatar avatar-sm mr-3">
                          <img :src="routes.server_api + review.user.avatar_url" alt="Avatar" class="avatar-img rounded-circle" v-if="review.user.avatar_url">
                          <img :src="routes.server_api+ '/img/default_avatar.jpg'" alt="" class="avatar-img rounded-circle" v-else>
                        </div>
                        <div class="media-body">
                                                    <span class="js-lists-values-employee-name">
                                                        <a href="javascript:void(0)">{{ review.user.first_name + " " + review.user.last_name }}</a>
                                                    </span>
                        </div>
                      </div>
                    </td>
                    <td v-html="review.review"></td>
                  </tr>
                  </tbody>
                </table>
                <p v-else class="text-center">No review yet!</p>
              </div>
            </div>

            <form action="#">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label form-label">Answer:</label>
                <div class="col-sm-9">
                  <p v-html="answer"></p>
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
import { ModelSelect } from "vue-search-select";
import {QuillDeltaToHtmlConverter} from "quill-delta-to-html";

export default {
  data() {
    return {
      assigned_course: [],
      essayAnswers: [],
	  get_assigned_couser: [],
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
      score_data:[],
      score_parra:[],
      reviews: [],
      answer: '',
      search_key_word: '',
      pagination: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: 1
      },
      offset: 4,
      modal_index: 1,
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

  computed: {
    user_info() {
      return this.$store.getters.authUser;
    },
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
  },

  created() {
    console.log(this.user_info);
  },

  mounted() {
    this.init();
    this.getUserInfoById();
    this.fetchListCompany();
    this.fetchListCourse();
    this.getAssignedCourse();
    this.getQuizzes(this.pagination.current_page);
    // $("#blockForm1").appendTo("#modelDestination");
  },

  methods: {
    init() {
      let _this = this;
      //----This line bellow is one of the best thing I've ever created ! Ever ! :)) -----//
      //Append the modal element to another div outside of this file (located in App.vue) :
      $("#modelDestination").empty();
      $("#essayModal" + this.modal_index).appendTo("#modelDestination");

      if (this.$session.get("essayModal")) {
        if (this.$session.get("essayModal") != "essayModal" + this.modal_index) {
          var check_modal = this.$session.get("essayModal");
          $("#" + check_modal).remove();
        }
      }

      $("#essayModal" + this.modal_index).on("hidden.bs.modal", function (e) {
        $("#essayModal" + _this.modal_index).modal("hide");
        _this.modal_index++;
        var modal_id = "essayModal" + _this.modal_index;
        _this.$session.set("essayModal", modal_id);
        $("#essayModal" + _this.modal_index).remove();
      });
    },
    resetField(field_name) {
      for (var key in this.error_messages) {
        if (key == field_name) {
          this.error_messages[key].show = false;
        }
      }
    },
    getScore(user_id, quiz_id, attempt_id) {
      $('#blockForm1').modal('show');
      axios.get(this.routes.quiz.GET_PART_SCORE +"/" + user_id +"/" +quiz_id +"/" +attempt_id)
              .then(res => {
                this.score_data=res.data.score;
                this.score_parra=res.data.data;
                setTimeout(function(){
                  $('#blockForm1').modal('hide');
                }, 30000);
              })
              .catch(err => {
                //console.log(err.response);
              });
    },
    getQuizzes(page) {
      this.$store.dispatch("enableLoading");
      axios
        .get(this.routes.quiz.GET_QUIZ_ATTEMPTS_BY_USER + this.$route.params.id, {
          params: {
            page: page
          }
        })
        .then(response => {
          this.quizzes = response.data.data;
          this.pagination.from = response.data.from;
          this.pagination.to = response.data.to;
          this.pagination.total = response.data.total;
          this.pagination.per_page = response.data.per_page;
          this.pagination.last_page = response.data.last_page;
          this.pagination.current_page = response.data.current_page;

          if (Array.isArray(this.quizzes)) {
            this.quizzes.forEach((q, index) => {
              let start_time = moment(q.start_time);
              let end_time = moment(q.end_time);
              this.$set(this.quizzes[index], "spent_time", moment.duration(end_time.diff(start_time)).minutes());
              this.$set(this.quizzes[index], "grade_to_pass", q.quiz.grade_to_pass);
              this.$set(this.quizzes[index], "total_score", q.quiz_attempt.total_score);
              this.$set(this.quizzes[index],"submitted",moment(q.quiz_attempt.created_at).format('DD/MM'));
            });
          } else {
            for (let i = response.data.from - 1; i < response.data.to; i++) {
              var start_time = moment(response.data.data[i].start_time);
              var end_time = moment(response.data.data[i].end_time);
              response.data.data[i].spent_time = moment.duration(end_time.diff(start_time)).minutes();
              response.data.data[i].end_time = moment(response.data.data[i].end_time).format('DD/MM');
              this.$set(response.data.data[i], "spent_time", moment.duration(end_time.diff(start_time)).minutes());
              this.$set(response.data.data[i], "grade_to_pass", response.data.data[i].quiz.grade_to_pass);
              this.$set(response.data.data[i], "total_score", response.data.data[i].quiz_attempt.total_score);
              this.$set(response.data.data[i], "submitted", moment(response.data.data[i].quiz_attempt.created_at).format('DD/MM'));
            }

            this.quizzes = response.data.data;
          }

          this.$store.dispatch("disableLoading");
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
		  this.getAssiendCourse();
		  
        })
        .catch(err => {
          console.log(err.response);
        });
    },
	getAssiendCourse() {
	var tempData  = [];
      axios
        .post(this.routes.course.GET_ASSIEND_COURSE, {student_id : this.$route.params.id})
        .then(res => {
			$.each(this.course_list, function(keys, val) {
				$.each(res.data, function(key, value) {
					if(val.value == value.course_id){
						var datas = {
							text : val.text,
							value : val.value,
							}
							tempData.push(datas);
					}
				});		
			});
			this.get_assigned_couser =  tempData;
			console.log(this.get_assigned_couser);
			setTimeout(function () {
				this.added_success = false
				}.bind(this), 3000)
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
		  this.getAssiendCourse();
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
			  this.getAssiendCourse();
			})
			.catch(err => {
			  this.$store.dispatch("disableLoading");
			  this.added_error_messages = err.response.data;
			  this.added_error = true;
			  this.added_success = false;
			});
	},
    getStudentEssaysAnswers(page) {
      this.$store.dispatch("enableLoading");
      axios
              .get(
                      this.routes.admin.ESSAYS_ANSWER + '/' + this.$route.params.id + '/closed', {
                        params: {
                          page: page,
                          keyword: this.search_key_word,
                        }
                      }
              ).then(response => {
        if (response.data.status === 200) {
          this.essayAnswers = response.data.data.data;
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
      }).catch(e => {
        this.$store.dispatch("disableLoading");
      });
    },
    reviewAnswer: function (index) {
      let _this = this;

      if (this.essayAnswers[index].reviews.find(element => element.is_student && element.seen_at === null)) {
        this.$store.dispatch("enableLoading");
        axios.post(this.routes.admin.ESSAYS + '/review/seen', {ids: this.essayAnswers[index].reviews.map(element => element.id)})
                .then(response => {
                  if (response.data.status === 200) {
                    if (response.data.errors) {
                      _this.errors = response.data.errors;
                    } else {
                      this.essayAnswers[index].reviews = response.data.data;
                    }
                  } else {
                    swal(response.data.msg);
                  }
                  _this.$store.dispatch("disableLoading");
                }).catch(err => {
          console.log(err.response);
          swal(err.response);
          _this.$store.dispatch("disableLoading");
        });
      }

      let answer = this.essayAnswers[index].answer;
      answer = answer.replace('[{"insert":"\\n"}]', "");

      let converter = new QuillDeltaToHtmlConverter(
              JSON.parse(answer)
      );

      answer = converter.convert();
      this.answer = answer;
      let reviews = this.essayAnswers[index].reviews;

      reviews.forEach(function (value, index, array) {
        if (_this.IsJsonString(value.review)) {
          value.review = value.review.replace('[{"insert":"\\n"}]', "");
          let converter = new QuillDeltaToHtmlConverter(
                  JSON.parse(value.review)
          );
          value.review = converter.convert();
        }
      });

      this.reviews = reviews;

      $("#essayModal" + this.modal_index).modal('show');
    },
    IsJsonString(str) {
      try {
        JSON.parse(str);
      } catch (e) {
        return false;
      }
      return true;
    },
    getUnseenReview: function(index) {
      return this.essayAnswers[index].reviews.filter(element => element.is_student && element.seen_at === null).length;
    },
    searchEssays: function() {
      if (this.search_key_word === '') {
        this.getStudentEssaysAnswers(1);
      }
    },
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
.modal.fade {
  z-index: 10000000 !important;
}
.modal-backdrop.show {
  opacity: 0 !important;
}
</style>
