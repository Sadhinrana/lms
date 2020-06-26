<template>
  <div
          class="mdk-drawer js-mdk-drawer"
          id="default-drawer"
          v-show="!['login', 'register'].includes($route.name)"
  >
    <div class="mdk-drawer__content ">
      <div
              class="sidebar sidebar-left sidebar-dark bg-dark o-hidden"
              data-perfect-scrollbar
      >
        <div class="sidebar-p-y">

          <div
                  class="sidebar-heading"
                  v-if="$store.getters.authUser.role == 'chief_auditor'"
          >CHIEF-AUDITOR</div>
          <ul
                  class="sidebar-menu sm-active-button-bg"
                  v-if="$store.getters.authUser.role == 'chief_auditor'"
          >

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'auditor_dashboard'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">category</i> Dashboard
              </router-link>
            </li>
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'user_attendance'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">supervised_user_circle</i><span class="check">Attendance</span>
              </router-link>
            </li>
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'chief_auditor_edit_profile'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_box</i> Account Settings
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'logout'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">lock_open</i> Logout
              </router-link>
            </li>
          </ul>

          <div
                  class="sidebar-heading"
                  v-if="$store.getters.authUser.role == 'auditor'"
          >AUDITOR</div>
          <ul
                  class="sidebar-menu sm-active-button-bg"
                  v-if="$store.getters.authUser.role == 'auditor'"
          >
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'user_attendance'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">supervised_user_circle</i><span class="check">Attendance</span>
              </router-link>
            </li>
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'auditor_edit_profile'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_box</i> Account Settings
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'logout'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">lock_open</i> Logout
              </router-link>
            </li>
          </ul>

          <div
                  class="sidebar-heading"
                  v-if="$store.getters.authUser.role == 'headmaster'"
          >HEADMASTER</div>
          <ul
                  class="sidebar-menu sm-active-button-bg"
                  v-if="$store.getters.authUser.role == 'headmaster'"
          >
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'admin_company_manager'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">alternate_email</i> Manage Companies
              </router-link>
            </li>
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'admin_manange_user'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">supervised_user_circle</i> Manage Students
              </router-link>
            </li>

            <!-- <li data-toggle="collapse" data-target="#category" class="collapsed "> -->
            <!-- <a href="#" class="sidebar-menu-button"><i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">category</i> Manage Categories</a> -->
            <!-- </li>   -->
            <!-- <ul class="sub-menu collapse" id="category"> -->
            <!-- <li> -->
            <!-- <router-link :to="{ name: 'admin_category_course'}" class="sidebar-menu-button"> -->
            <!-- Add a Category -->
            <!-- </router-link> -->
            <!-- </li> -->
            <!-- </ul> -->

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'admin_category_course'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">category</i>Manage Categories
              </router-link>
            </li>




            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'assign_course'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assignment</i>Enroll Students
              </router-link>
            </li>




            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'assign_course_company'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assignment_returned</i>Assign Courses
              </router-link>
            </li>
            <!-- <li class="sidebar-menu-item"> -->
            <!-- <router-link -->
            <!-- :to="{ name: 'course_apply_request_manager'}" -->
            <!-- class="sidebar-menu-button" -->
            <!-- > -->
            <!-- <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assignment_returned</i>Student Registrations -->
            <!-- </router-link> -->
            <!-- </li> -->
          </ul>

          <div
                  class="sidebar-heading"
                  v-if="['company_head'].indexOf($store.getters.authUser.role) != -1"
          >Company Head</div>
          <ul
                  class="sidebar-menu sm-active-button-bg"
                  v-if="['company_head'].indexOf($store.getters.authUser.role) != -1"
          >
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'company_profile'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">alternate_email</i> Company Profile
              </router-link>
            </li>
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'company_user_manager'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">supervised_user_circle</i> Manage Students
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'company_add_user'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">supervised_user_circle</i> Add a Student
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'manege_live_sessions'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">school</i> Live Sessions
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'company_user_attendance'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">supervised_user_circle</i> Attendance
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'essay_manage'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">school</i><span class="check">Manage Essays</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'instructor_edit_profile'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_box</i> Account Settings
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'logout'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">lock_open</i> Logout
              </router-link>
            </li>

            <!-- <li class="sidebar-menu-item"> -->
            <!-- <router-link -->
            <!-- :to="{ name: 'course_apply_request_manager'}" -->
            <!-- class="sidebar-menu-button" -->
            <!-- > -->
            <!-- <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">supervised_user_circle</i> Manager requests -->
            <!-- </router-link> -->
            <!-- </li> -->

          </ul>

          <div
                  class="sidebar-heading"
                  v-if="['headmaster','content_manager'].indexOf($store.getters.authUser.role) != -1"
          >Content Manage</div>
          <ul
                  class="sidebar-menu sm-active-button-bg"
                  v-if="['headmaster','content_manager' ].indexOf($store.getters.authUser.role) != -1"
          >
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'instructor_course_manager'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">import_contacts</i> Manage Courses
              </router-link>
            </li>
            <!-- <li class="sidebar-menu-item">
              <router-link
                :to="{ name: 'instructor_lesson_manager'}"
                class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">library_books</i> Lesson Manager
              </router-link>
            </li> -->
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'instructor_quiz_manager'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">help</i> Manage Exams
              </router-link>
            </li>
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'admin_manage_essays'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">import_contacts</i> Manage Essays
              </router-link>
            </li>

            <!-- For Listening Menu -->


            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'instructor_listening'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">listening</i><span class="check">Listening</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'instructor_edit_profile'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_box</i> Account Settings
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'logout'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">lock_open</i> Logout
              </router-link>
            </li>
          </ul>

          <div
                  class="sidebar-heading"
                  v-if="['head_teacher'].indexOf($store.getters.authUser.role) != -1"
          >Teacher</div>
          <ul
                  class="sidebar-menu sm-active-button-bg"
                  v-if="['head_teacher'].indexOf($store.getters.authUser.role) != -1"
          >
            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'head_teacher_dashboard'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dashboard</i> Dashboard
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'head_teacher_manange_user'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">supervised_user_circle</i> Manage Students
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'instructor_listening'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">listening</i><span class="check">Listening</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'head_teacher_user_attendance'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">school</i><span class="check">Attendance</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'essay_manage'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">school</i><span class="check">Manage Essays</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'head_teacher_manage_live_session'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">school</i><span class="check">Live Lessons</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'instructor_edit_profile'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_box</i> Account Settings
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'logout'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">lock_open</i> Logout
              </router-link>
            </li>

          </ul>

          <div
                  class="sidebar-heading"
                  v-if="['instructor'].indexOf($store.getters.authUser.role) != -1"
          >Instructor</div>
          <ul
                  class="sidebar-menu sm-active-button-bg"
                  v-if="['instructor'].indexOf($store.getters.authUser.role) != -1"
          >

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'instructor_manange_user'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">supervised_user_circle</i> Manage Users
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'instructor_listening'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">listening</i><span class="check">Listening</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'instructor_edit_profile'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_box</i> Account Settings
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'logout'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">lock_open</i> Logout
              </router-link>
            </li>

          </ul>

          <div
                  class="sidebar-heading"
                  v-if="['student'].indexOf($store.getters.authUser.role) != -1"
          >Student</div>
          <ul
                  class="sidebar-menu sm-active-button-bg"
                  v-if="['student'].indexOf($store.getters.authUser.role) != -1"
          >

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'student_dashboard'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dashboard</i> Dashboard
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'student_live_lessons'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">school</i> My Lessons
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'student_my_courses'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">school</i> My Courses
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'my_quizzes'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">poll</i> Exam Results
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'take_listening_student'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">listening</i><span class="check">Listening</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'student_attendance'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">school</i><span class="check">Attendance</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'student_essays'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">school</i><span class="check">Essays</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'student_essays_reviews'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons checks">school</i><span class="check">Essays reviews</span>
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'student_edit_profile'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_box</i> Edit Account
              </router-link>
            </li>

            <li class="sidebar-menu-item">
              <router-link
                      :to="{ name: 'logout'}"
                      class="sidebar-menu-button"
              >
                <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">lock_open</i> Logout
              </router-link>
            </li>
          </ul>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    watch: {
      $route(to, from) {
        //--------Close the sidebar if using mobile--------//
        if (window.innerWidth < 1000) {
          var drawerNode = document.querySelector(".js-mdk-drawer");
          var drawer = drawerNode.mdkDrawer;
          drawer.opened = false;
        }
      }
    },
    mounted() {
      !(function(e) {
        var t = {};
        function o(r) {
          if (t[r]) return t[r].exports;
          var n = (t[r] = { i: r, l: !1, exports: {} });
          return e[r].call(n.exports, n, n.exports, o), (n.l = !0), n.exports;
        }
        (o.m = e),
                (o.c = t),
                (o.d = function(e, t, r) {
                  o.o(e, t) ||
                  Object.defineProperty(e, t, {
                    configurable: !1,
                    enumerable: !0,
                    get: r
                  });
                }),
                (o.n = function(e) {
                  var t =
                          e && e.__esModule
                                  ? function() {
                                    return e.default;
                                  }
                                  : function() {
                                    return e;
                                  };
                  return o.d(t, "a", t), t;
                }),
                (o.o = function(e, t) {
                  return Object.prototype.hasOwnProperty.call(e, t);
                }),
                (o.p = "/"),
                o((o.s = 90));
      })({
        90: function(e, t, o) {
          e.exports = o(91);
        },
        91: function(e, t, o) {
          "use strict";
          o(92), o(93), o(94), o(95), o(96);
        },
        92: function(e, t) {
          !(function() {
            "use strict";
            domFactory.handler.autoInit(), $('[data-toggle="tooltip"]').tooltip();
          })();
        },
        93: function(e, t) {
          !(function() {
            "use strict";
            document.querySelectorAll("[data-perfect-scrollbar]").forEach(e => {
              const t = new PerfectScrollbar(e, {
                wheelPropagation: !1,
                swipeEasing: !1
              });
              Object.defineProperty(e, "PerfectScrollbar", {
                configurable: !0,
                writable: !1,
                value: t
              });
            });
          })();
        },
        94: function(e, t) {
          !(function() {
            "use strict";
            window.addEventListener("load", function() {
              $(".preloader").fadeOut(), domFactory.handler.upgradeAll();
            });
          })();
        },
        95: function(e, t) {
          !(function() {
            "use strict";
            var e = document.querySelectorAll('[data-toggle="sidebar"]');
            (e = Array.prototype.slice.call(e)).forEach(function(e) {
              e.addEventListener("click", function(e) {
                var t =
                        e.currentTarget.getAttribute("data-target") ||
                        "#default-drawer",
                        o = document.querySelector(t);
                o && o.mdkDrawer.toggle();
              });
            }),
                    $(".sidebar .collapse").on("show.bs.collapse", function(e) {
                      $(this)
                              .closest(".sidebar-menu")
                              .find(".open")
                              .find(".collapse")
                              .collapse("hide"),
                              $(this)
                                      .closest("li")
                                      .addClass("open");
                    }),
                    $(".sidebar .collapse").on("hidden.bs.collapse", function(e) {
                      $(this)
                              .closest("li")
                              .removeClass("open");
                    }),
                    $(".sidebar .collapse").on(
                            "show.bs.collapse hide.bs.collapse shown.bs.collapse hidden.bs.collapse",
                            function() {
                              const e = $(this)
                                      .closest("[data-perfect-scrollbar]")
                                      .get(0);
                              e &&
                              void 0 !== e.PerfectScrollbar &&
                              e.PerfectScrollbar.update();
                            }
                    );
          })();
        },
        96: function(e, t) {
          !(function() {
            "use strict";

          })();
        }
      });
    },
    methods: {}
  };
</script>

<style lang="scss">
  ul {
    list-style-type: none;
  }
</style>
<style>
  .checks{
    width: 23px;
  }
</style>
