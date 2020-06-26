<template>
  <div class="page">
    <div class="container">
      <div class="d-flex flex-column flex-sm-row flex-wrap mb-headings align-items-start align-items-sm-center">
        <div class="flex mb-2 mb-sm-0">
          <h1 class="h2">Attendance Report</h1>
        </div>
      </div>

      <div class="card card-body border-left-3 border-left-primary navbar-shadow mb-4">
        <form action="#">
          <div class="form-group row">
            <div :class="{'col-md-4' : user_info.role === 'company_head', 'col-md-3' : user_info.role !== 'company_head'}" v-if="user_info.role === 'auditor' || user_info.role === 'chief_auditor' || user_info.role === 'headmaster'">
              <select class="form-control custom-select" v-model="company_id" @change="getInstructorList(company_id)">
                <option value="0" selected="selected" disabled="disabled">Select Company</option>
                <option :value="company.id" v-for="company in companies" :key="company.id">{{ company.name }}</option>
              </select>
            </div>
            <div :class="{'col-md-4' : user_info.role === 'company_head', 'col-md-3' : user_info.role !== 'company_head'}" v-if="user_info.role === 'auditor' || user_info.role === 'chief_auditor' || user_info.role === 'headmaster' || user_info.role === 'company_head'">
              <select class="form-control custom-select" v-model="instructor_id" @change="getStudentAttendanceReport(1)">
                <option value="0">All Teachers</option>
                <option :value="instructor.id" v-for="instructor in instructors" :key="instructor.id">{{ instructor.first_name + " " + instructor.last_name }}</option>
              </select>
            </div>
            <div :class="{'col-md-4' : user_info.role === 'company_head', 'col-md-6' : user_info.role === 'head_teacher', 'col-md-3' : user_info.role === 'auditor' || user_info.role === 'chief_auditor' || user_info.role === 'headmaster'}">
              <select class="form-control custom-select" v-model="month" @change="getStudentAttendanceReport(1)">
                <option value="0" disabled="disabled">Select Month</option>
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
              </select>
            </div>
            <div :class="{'col-md-4' : user_info.role === 'company_head', 'col-md-6' : user_info.role === 'head_teacher', 'col-md-3' : user_info.role === 'auditor' || user_info.role === 'chief_auditor' || user_info.role === 'headmaster'}">
            <select class="form-control custom-select" v-model="year" @change="getStudentAttendanceReport(1)">
                <option value="0" disabled="disabled">Select Year</option>
                <option :value="2014 + i" v-for="i in 16" :key="i">{{ 2014 + i }}</option>
              </select>
            </div>
          </div>

          <div class="d-flex flex-column flex-sm-row align-items-sm-center" style="white-space: nowrap;">
            <small class="flex text-muted text-uppercase mr-3 mb-2 mb-sm-0">Displaying {{pagination.from}} to {{pagination.to}} out of {{pagination.total}} students</small>
          </div>
        </form>
      </div>

      <div class="card" v-if="users.length">
        <div class="card-body">
          <div class="row">
            <div class="col-lg">
              <div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>
                <table class="table mb-0">
                  <thead>
                  <tr>
                    <th>User</th>
                    <th v-if="user_info.role !== 'head_teacher'">Teacher</th>
                    <th>Lesson</th>
                    <th>Study</th>
                    <th class="text-center">Lesson Hour</th>
                    <th class="text-center">Total lessons</th>
                    <th class="text-center">Total hours</th>
                    <th class="text-center">Group</th>
                  </tr>
                  </thead>
                  <tbody class="list">
                    <tr class="selected" v-for="(user, index) in users" :key="index">
                      <td>
                        <div class="media align-items-center">
                          <div class="media-body">
                              <span class="js-lists-values-employee-name">
                                <a href="javascript:void(0)" v-if="user.teacher_id" @click="checkTeacher(user.id, user.teacher_id)">{{user.first_name + " " + user.last_name}}</a>
                                <a href="javascript:void(0)" v-else>{{user.first_name + " " + user.last_name}}</a>
                              </span>
                          </div>
                        </div>
                      </td>
                      <td v-if="user_info.role !== 'head_teacher'"><span v-if="user.instructor">{{ user.instructor.first_name + " " + user.instructor.last_name }}</span></td>
                      <td>{{ user.lesson_mode }}</td>
                      <td>{{ user.study_mode }}</td>
                      <td class="text-center">{{ hourMinutes(user.lesson_hour) }}</td>
                      <td class="text-center">{{ attendancesByTeacher(user.attendances, user.teacher_id) }}</td>
                      <td class="text-center">{{ hourMinutes(attendancesByTeacher(user.attendances, user.teacher_id) * user.lesson_hour) }}</td>
                      <td class="text-center">
                        <a class="btn btn-sm" v-if="user.student_group != null" :style="{'background-color': user.student_group.color_code,'border-color': user.student_group.color_code,'color': '#ffffff'}">{{user.student_group.title}}</a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="alert alert-light alert-dismissible border-1 border-left-3 border-left-warning" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="text-black-70">No results found.</div>
      </div>

      <!-- Pagination -->
      <ul class="pagination justify-content-center pagination-sm">
        <li v-if="pagination.current_page>1" class="page-item">
          <a @click.prevent="getStudentAttendanceReport(pagination.current_page - 1)" class="page-link" href="#" aria-label="Previous">
            <span aria-hidden="true" class="material-icons">chevron_left</span>
            <span>Prev</span>
          </a>
        </li>

        <li :key="page" v-for="page in pagesNumber" :class="[page == pagination.current_page ? 'active' : null , 'page-item']">
          <a href="#" v-on:click.prevent="getStudentAttendanceReport(page)" class="page-link" aria-label="Previous" >{{ page }}</a>
        </li>

        <li v-if="pagination.current_page < pagination.last_page">
          <a href="javascript:void(0)" aria-label="Next" v-on:click.prevent="getStudentAttendanceReport(pagination.current_page + 1)">
            <span aria-hidden="true"></span>
            <span aria-hidden="true" class="material-icons">chevron_right</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- modal-->
    <div class="modal fade" :id="'attendance' + modal_index">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h4 class="modal-title text-white">
              Total Attendance: {{ totalAttendancesCurrentMonth }}
            </h4>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body ReportCalendar">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- /modal -->
  </div>
</template>

<script>
  import {eventBus} from "@/main";
  import {Calendar} from "@fullcalendar/core";
  import dayGridPlugin from "@fullcalendar/daygrid";
  import interactionPlugin from "@fullcalendar/interaction";

  export default {
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
        instructors: [],
        instructorsByStudent: [],
        companies: [],
        user_id: '',
        instructor_id: '0',
        teacher_id: '',
        company_id: '0',
        month:'',
        year: '',
        added_success: false,
        added_error: false,
        added_error_messages: [],
        totalAttendancesCurrentMonth: 0,
        events: [],
        calendar: null,
        modal_index: 1,
      };
    },
    created() {
      let user = this.user_info;
    },
    mounted() {
      let _this = this;
      this.$store.dispatch("enableLoading");
      this.init();
      //If this is the first time user visit page, wait until authUser is fetched
      if (_this.user_info.id) {
        _this.month = _this.user_info.role === 'company_head' ? moment().format('MMMM') : moment().subtract(0, 'months').format('MMMM');
        _this.year = _this.user_info.role === 'company_head' ? moment().format('YYYY') : moment().subtract(0, 'months').format('YYYY');
        if (_this.user_info.role === 'head_teacher') {
          _this.instructor_id = _this.user_info.id;
        }
        if (_this.user_info.role !== 'head_teacher' && _this.user_info.role !== 'company_head') {
          _this.fetchCompanies();
        }
        if (_this.user_info.role === 'company_head') {
          _this.company_id = _this.user_info.company_id;
          _this.getInstructorList(_this.company_id);
        }
        _this.getStudentAttendanceReport(_this.pagination.current_page);
      } else {
        eventBus.$on("authUserFetched", function () {
          _this.month = _this.user_info.role === 'company_head' || _this.user_info.role === 'head_teacher' ? moment().format('MMMM') : moment().subtract(1, 'months').format('MMMM');
          _this.year = _this.user_info.role === 'company_head' || _this.user_info.role === 'head_teacher' ? moment().format('YYYY') : moment().subtract(1, 'months').format('YYYY');
          if (_this.user_info.role === 'head_teacher') {
            _this.instructor_id = _this.user_info.id;
          }
          if (_this.user_info.role !== 'head_teacher' && _this.user_info.role !== 'company_head') {
            _this.fetchCompanies();
          }
          if (_this.user_info.role === 'company_head') {
            _this.company_id = _this.user_info.company_id;
            _this.getInstructorList(_this.company_id);
          }
          _this.getStudentAttendanceReport(_this.pagination.current_page);
        });
      }
    },
    computed:{
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
      date: function () {
        return this.month + ' ' + this.year;
      },
      user_info() {
        return this.$store.getters.authUser;
      },
    },
    methods: {
      init() {
        let _this = this;
        //----This line bellow is one of the best thing I've ever created ! Ever ! :)) -----//
        //Append the modal element to another div outside of this file (located in App.vue) :
        $("#modelDestination").empty();
        $("#attendance" + this.modal_index).appendTo("#modelDestination");

        if (this.$session.get("attendance")) {
          if (this.$session.get("attendance") != "attendance" + this.modal_index) {
            var check_modal = this.$session.get("attendance");
            $("#" + check_modal).remove();
          }
        }

        $("#attendance" + this.modal_index).on("hidden.bs.modal", function (e) {
          $("#attendance" + _this.modal_index).modal("hide");
          _this.modal_index++;
          var modal_id = "attendance" + _this.modal_index;
          _this.$session.set("attendance", modal_id);
          $("#attendance" + _this.modal_index).remove();
        });
      },
      hourMinutes: function(totalMinutes) {
        let hours = Math.floor(totalMinutes / 60);
        let minutes = totalMinutes % 60;

        if (hours) {
          let hourText = hours > 1 ? 'hrs' : 'hr';
          return minutes ? hours + ' ' + hourText + ' ' + minutes + ' min' : hours + ' ' + hourText;
        } else {
          return minutes ? minutes + ' min' : '';
        }
      },
      fetchCompanies() {
        var self = this;
        axios
                .get(
                        this.routes.company.SHOW_ALL_COMPANY + "/" + self.user_info.id
                )
                .then(res => {
                  this.companies = res.data;
                })
                .catch(err => {
                  console.log(err);
                });
      },
      getInstructorList(company_id) {
        var self = this;
        this.instructor_id = '0';
        axios
                .post(
                        self.routes.company.INSTRUCTOR_LIST, {company_id: company_id}
                )
                .then(res => {
                  self.instructors = res.data;
                  self.getStudentAttendanceReport(1);
                })
                .catch(err => {
                  console.log(err);
                });
      },
      getStudentAttendanceReport: function (page) {
        this.$store.dispatch("enableLoading");
        var self = this;
        let params = {
          params: {
            company_id: self.company_id == 0 ? '' : self.company_id,
            instructor_id: self.instructor_id == 0 ? '' : self.instructor_id,
            page: page,
            date: self.date,
          }
        };

        axios
                .get(
                        self.routes.common.GET_ATTENDANCES, params
                )
                .then(response => {
                  self.users = response.data.data.data;
                  self.pagination.from = response.data.data.from;
                  self.pagination.to = response.data.data.to;
                  self.pagination.total = response.data.data.total;
                  self.pagination.per_page = response.data.data.per_page;
                  self.pagination.last_page = response.data.data.last_page;
                  self.pagination.current_page = response.data.data.current_page;
                  self.$store.dispatch("disableLoading");
                })
                .catch(err => {
                  console.log(err);
                  self.$store.dispatch("disableLoading");
                });
      },
      getEvents: function (params) {
        let _this = this;

        axios.get(_this.routes.user.ATTENDANCES, {params: params})
                .then(response => {
                  if (response.data.status === 200) {
                    _this.added_error = false;
                    _this.added_success = true;
                    _this.events = response.data.data;
                    _this.totalAttendancesCurrentMonth = response.data.attendancesRequestedMonth;
                    response.data.data.forEach(function (value, index, array) {
                      let evt = JSON.parse(value);
                      if(window.innerWidth <= 768){ evt.title = 'V'; }
                      _this.calendar.addEvent(evt);
                    });
                    _this.$store.dispatch("disableLoading");
                  } else {
                    swal(response.data.msg);
                  }
                })
                .catch(err => {
                  _this.added_error_messages = err.response.data;
                  _this.added_error = true;
                  _this.added_success = false;
                  _this.$store.dispatch("disableLoading");
                });
      },
      initializeFullCalendar: function () {
        let _this = this;
        $("#attendance" + this.modal_index).modal('show');
        let calendarEl = document.getElementById('calendar');
        calendarEl.innerHTML = '';
        _this.calendar = new Calendar(calendarEl, {
          defaultDate: new Date(_this.date),
          plugins: [dayGridPlugin, interactionPlugin],
          datesRender: function (info) {
            let monthS = info.view.activeStart.getUTCMonth() + 1;
            monthS = monthS < 10 ? '0' + monthS : monthS;
            let dtS = info.view.activeStart.getUTCDate();
            dtS = dtS < 10 ? '0' + dtS : dtS;
            let monthE = info.view.activeEnd.getUTCMonth() + 1;
            monthE = monthE < 10 ? '0' + monthE : monthE;
            let dtE = info.view.activeEnd.getUTCDate();
            dtE = dtE < 10 ? '0' + dtE : dtE;
            let param = {
              start: info.view.activeStart.getUTCFullYear() + '-' + monthS + '-' + dtS,
              end: info.view.activeEnd.getUTCFullYear() + '-' + monthE + '-' + dtE,
              month: new Date(info.view.title).getMonth() + 1,
              year: new Date(info.view.title).getFullYear(),
              user_id: _this.user_id,
              instructor_id: _this.teacher_id,
            };
            _this.$store.dispatch("enableLoading");
            let events = _this.calendar.getEvents();
            let len = events.length;
            for (let i = 0; i < len; i++) {
              events[i].remove();
            }
            _this.getEvents(param);
          },
        });
        _this.calendar.render();
      },
      checkTeacher: function (user_id, teacher_id) {
        this.user_id = user_id;
        this.teacher_id = teacher_id;
        this.initializeFullCalendar();
      },
      attendancesByTeacher: function (attendances, instructor_id) {
        let count = 0;
        attendances.forEach(function(value, index, array) {
          if (value.instructor_id === instructor_id) {
            count += 1;
          }
        });
        return count;
      }
    }
  };
</script>

<style lang='scss'>
  @import '~@fullcalendar/core/main.css';
  @import '~@fullcalendar/daygrid/main.css';

  .page__container.full {
    @media screen and (max-width: 768px) {
      padding: 0;
    }
  }

  .fc-scroller{
    overflow: initial!important;
    display: inline-block!important;
    height: inherit!important;
  }
  .fc th, .fc td {
    padding: 5px;
    @media screen and (max-width: 768px) {
      padding: 0;
    }
  }
  .fc-today-button{
    text-transform: capitalize!important;
  }
  .fc-toolbar h2{
    @media screen and (max-width: 500px) {
      font-size: 17px;
      font-weight: bold;
    }
  }
  .fc-past {
    opacity: 0.3;
  }

  .ReportCalendar{
    .fc-day {
      position: relative;
      &:before {
        display: none!important;
        content: none!important;
      }
    }
    .fc-event {
      width: auto;
      position: relative;
      font-size: 12px;
      color: #000;
      z-index: 99;
      padding: 5px 10px;
      background: #e8e8e8;
      border-radius: 4px;
      top: 0;
      text-align: center;
      @media screen and (max-width: 1024px) {
        top: 0;
      }
      @media screen and (max-width: 991px) {
        top: 0;
      }
      @media screen and (max-width: 500px) {
        top: 0;
      }
    }
  }
</style>
