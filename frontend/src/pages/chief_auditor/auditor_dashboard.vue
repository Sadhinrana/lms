<template>
  <div class="container-fluid page__container">
    <!-- <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <router-link :to="{ name: 'home'}">Home</router-link>
      </li>
      <li class="breadcrumb-item active">Company profile</li>
    </ol> -->

    <div class="text-center" v-for="company_data in company_data">
      <!--<a href="#">
	    <img
          v-if="company_data.company_avatar"
          :src="routes.server_api + company_data.company_avatar"
          alt=""
          class="rounded-circle"
		  style="width:25% !important; border-radius: 0% !important;"
        >
        <img
          v-else
          class="rounded-circle"
          :src="routes.server_api+ '/img/default.png'"
		  style="width:25% !important; border-radius: 0% !important;"
        /></a>-->
      <h1 class="h2 mb-0 mt-1">{{company_data.name}}</h1>
      <p class="lead text-muted mb-0">
        <span
          class="m-1"
          v-if="company_data.city"
        >{{ company_data.city }}</span>
        <span
          class="m-1"
          v-if="company_data.country"
        >{{ company_data.country }}</span>
      </p>
      <div class="badge badge-primary ">Company</div>
      <hr>
	  
	  <div class="card-group">
	  
	  <div class="card">
          <div class="card-body text-center">
            <div class="media-body">
              <a class="text-body"><strong>Total Exams Taken</strong></a>
            </div>
            <span class="badge badge-info m-1" style="font-size: 16px;">{{ company_data.daily_quizzes_taken_count }} | Daily</span>
            <span class="badge badge-primary m-1" style="font-size: 16px;">{{ company_data.monthly_quizzes_taken_count }} | Monthly</span>
            <span class="badge badge-success m-1" style="font-size: 16px;">{{ company_data.yearly_quizzes_taken_count }} | Yearly</span>
          </div>
        </div>
        <!--<div class="card">
          <div class="card-body text-center">
            <h5 class="text-muted mb-1">Company Rating</h5>
          <div class="rating">
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-muted-light">star_border</i>
            <i class="material-icons text-muted-light">star_border</i>
          </div>
          </div>
        </div>-->
		<div class="card">
          <div class="card-body text-center">
		  
            <h5 class="text-muted mb-1">Company Rating</h5>
          <div class="rating"  v-for="from_average in company_data.average" >
		    <span  v-if="from_average.average >=85">  
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            </span>
			<span  v-if="from_average.average >=75 && from_average.average <85">  
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
			<i class="material-icons text-muted-light">star_border</i>
            </span>
			<span  v-if="from_average.average >=65 && from_average.average <75">  
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
            </span>
			<span  v-if="from_average.average >=55 && from_average.average <65">  
            <i class="material-icons text-success">star</i>
            <i class="material-icons text-success">star</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
            </span>
			<span  v-if="from_average.average <=55">  
            <i class="material-icons text-success">star</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
			<i class="material-icons text-muted-light">star_border</i>
            </span>
          </div>
          </div>
        </div>
		
        <div class="card">
          <div class="card-body text-center">
            <div class="media-body">
              <a class="text-body"><strong>Total Students Enrolled</strong></a>
            </div>
            <span class="badge badge-info m-1" style="font-size: 16px;">{{ company_data.daily_students_registered_count }} | Daily</span>
            <span class="badge badge-primary m-1" style="font-size: 16px;">{{ company_data.monthly_students_registered_count }} | Monthly</span>
            <span class="badge badge-success m-1" style="font-size: 16px;">{{ company_data.yearly_students_registered_count }} | Yearly</span>
          </div>
        </div>
		
	  </div>
	  
	  

      <!-- <div class="row"> -->
        <!-- <div class="col"> -->
          <!-- <div class="text-left"> -->
            <!-- <div class="media-body"> -->
              <!-- <a class="text-body"><strong>Total quizzes taken</strong></a> -->
            <!-- </div> -->
            <!-- <span class="badge badge-info m-1" style="font-size: 15px;">{{ company_data.daily_quizzes_taken_count }} | Daily</span> -->
            <!-- <span class="badge badge-primary m-1" style="font-size: 15px;">{{ company_data.monthly_quizzes_taken_count }} | Monthly</span> -->
            <!-- <span class="badge badge-success m-1" style="font-size: 15px;">{{ company_data.yearly_quizzes_taken_count }} | Yearly</span> -->
          <!-- </div> -->
        <!-- </div> -->
        <!-- <div class="col"> -->
          <!-- <h5 class="text-muted mb-1">Company Rating</h5> -->
          <!-- <div class="rating"> -->
            <!-- <i class="material-icons text-success">star</i> -->
            <!-- <i class="material-icons text-success">star</i> -->
            <!-- <i class="material-icons text-success">star</i> -->
            <!-- <i class="material-icons text-muted-light">star_border</i> -->
            <!-- <i class="material-icons text-muted-light">star_border</i> -->
          <!-- </div> -->
        <!-- </div> -->
        <!-- <div class="col"> -->
          <!-- <div class="text-right"> -->
            <!-- <div class="media-body"> -->
              <!-- <a class="text-body"><strong>Total Students Enrolled</strong></a> -->
            <!-- </div> -->
            <!-- <span class="badge badge-info m-1" style="font-size: 15px;">{{ company_data.daily_students_registered_count }} | Daily</span> -->
            <!-- <span class="badge badge-primary m-1" style="font-size: 15px;">{{ company_data.monthly_students_registered_count }} | Monthly</span> -->
            <!-- <span class="badge badge-success m-1" style="font-size: 15px;">{{ company_data.yearly_students_registered_count }} | Yearly</span> -->
          <!-- </div> -->
        <!-- </div> -->
      <!-- </div> -->
    </div>
    <hr>
    <!--<h4>Courses by {{company_data.name}}</h4>
    <div class="card-columns">
      <div
        class="card"
        :key="course_company.id"
        v-for="course_company in company_courses"
      >
        <div class="card-header">
          <div class="media align-items-center">
            <div class="media-left">
              <router-link :to="{ name: 'instructor_edit_course' }">
                <img
                  :src="routes.server_api + course_company.course.course_image"
                  alt="Card image cap"
                  width="100"
                  class="rounded"
                  v-if="course_company.course.course_image"
                >
                <img
                  :src="routes.server_api+ '/img/default.png'"
                  alt="Card image cap"
                  width="100"
                  class="rounded"
                  v-else
                >
              </router-link>
            </div>
            <div class="media-body">
              <h4 class="card-title mb-0">
                <router-link :to="{ name: 'instructor_edit_course' }">{{course_company.course.title}}</router-link>
              </h4>
            </div>
          </div>
        </div>
      </div>
    </div>-->
	  <!--<div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <div class="flex">
              <h4 class="card-title">Company Stats</h4>
            <p class="card-subtitle">Last 7 Days</p>
            </div>
            <a
              href="instructor-earnings.html"
              class="btn btn-sm btn-primary"
            ><i class="material-icons">trending_up</i></a>
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th style="font-size: 15px;" scope="col">Companies</th>
                  <th style="font-size: 15px;" scope="col">Total Students</th>
                  <th style="font-size: 15px;" scope="col">Total Exams Today</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  :key="company.id"
                  v-for="company in datas.companies"
                >
                  <td class="btn_size">{{ company.name }}</td>
                  <td class="btn_size">{{ company.students_count }}</td>
                  <td class="btn_size">{{ company.daily_test_taken_count }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>-->
  </div>
</template>

<script>
import { eventBus } from "@/main";
export default {
  data() {
    return {
	  datas : [],
      company_data: {
        name: "",
        address: "",
        description: ""
      },
      company_courses: [],
      idcompany: ""
    };
  },
  computed: {
    user_info() {
      return this.$store.getters.authUser;
    }
  },
  mounted() {
    this.fetchCompanies();
    this.getDashboard();
  },
  methods: {
    fetchCompanies() {
      var self = this;
      // Only run if authUser is fetched
      if (this.user_info.id) {
        axios
          .get(
            this.routes.company.SHOW_ALL_COMPANY + "/" + self.user_info.id
          )
          .then(res => {
            this.company_data = res.data;
            this.company_courses = res.data.company_courses;
          })
          .catch(err => {
            console.log(err);
          });
      } else {
        //If this is the first time user visit page, wait until authUser is fetched
        eventBus.$on("authUserFetched", function() {
          axios
            .get(
              self.routes.company.SHOW_ALL_COMPANY + "/" + self.user_info.id
            )
            .then(res => {
              self.company_data = res.data;
              self.company_courses = res.data.company_courses;
            })
            .catch(err => {
              console.log(err);
            });
        });
      }
    },
	getDashboard() {
      axios.get(this.routes.admin.GET_DASHBOARD).then(response => {
        this.datas = response.data;
      });
    }
  }
};
</script>

<style>
</style>
