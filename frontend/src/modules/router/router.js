import Vue from "vue";
import Router from "vue-router";
import AppHeader from "@/layouts/Header.vue";
import Sidebar from "@/layouts/Sidebar.vue";
import Help from "@/pages/general/help.vue";
import Forum from "@/pages/general/forum.vue";
import ForumThread from "@/pages/general/forum_thread.vue";
import ManageEssays from "@/pages/general/manage_essays.vue";
import Home from "@/pages/instructor/index.vue";
import CourseManager from "@/pages/instructor/course_manager.vue";
import LessonManager from "@/pages/instructor/lesson_manager.vue";
import QuizManager from "@/pages/instructor/quiz_manager.vue";
import InstructorEditProfile from "@/pages/instructor/edit_profile.vue";
import InstructorEditCourse from "@/pages/instructor/edit_course.vue";
import InstructorListening from "@/pages/instructor/listening.vue";
import InstructorAddCourse from "@/pages/instructor/add_course.vue";

import InstructorAddQuiz from "@/pages/instructor/add_quiz.vue";
import InstructorEditQuiz from "@/pages/instructor/edit_quiz.vue";

import InstructorReviewQuiz from "@/pages/instructor/review_quiz.vue";
import AuditorReviewQuiz from "@/pages/auditor/review_quiz.vue";
import ChiefAuditorReviewQuiz from "@/pages/chief_auditor/review_quiz.vue";
import InstructorAddLesson from "@/pages/instructor/add_lesson.vue";
import InstructorEditLesson from "@/pages/instructor/edit_lesson.vue";
import StudentDashboard from "@/pages/student/dashboard.vue";
import StudentLiveLessons from "@/pages/student/live_lessons.vue";
import StudentBrowseCourses from "@/pages/student/browsecourses.vue";
import StudentEssays from "@/pages/student/essays.vue";
import StudentEssaysReviews from "@/pages/student/essays_reviews.vue";
import studentProfile from "@/pages/student/profile.vue";
import StudentQuizResult from "@/pages/student/quizresult.vue";
import StudentMyCourses from "@/pages/student/mycourses.vue";
import GetStudentCourses from "@/pages/student/getcourses.vue";
import StudentViewCourse from "@/pages/student/viewcourse.vue";
import StudentTakeQuiz from "@/pages/student/takequiz.vue";
import StudentReviewQuiz from "@/pages/student/quiz_review.vue";
import StudentTakeCouse from "@/pages/student/takecourse.vue";
import StudentListening from "@/pages/student/listenings.vue";
import StudentAttendance from "@/pages/student/attendance.vue";
import StudentTakeListening from "@/pages/student/take_listening.vue";
import StudentEditProfile from "@/pages/student/editprofile.vue";
import StudentQuizzes from '@/pages/student/my_quizzes';
import AssignCoursetostudent from "@/pages/company_head/assign_course_student.vue";

import AdminEditUser from "@/pages/admin/edit_user.vue";
import AdminManageEssays from "@/pages/admin/manage_essays.vue";
import EditUserInstructor from "@/pages/instructor/edit_user.vue";
import AuditorEditUser from "@/pages/auditor/edit_user.vue";
import ChiefAuditorEditUser from "@/pages/chief_auditor/edit_user.vue";
import AdminAddCompany from "@/pages/admin/add_company.vue";
import AdminEditCompany from "@/pages/admin/edit_company.vue";
import AdminCompanyManager from "@/pages/admin/company_manager.vue";
import AdminManageStudent from "@/pages/admin/usermanager.vue";
import ChiefAuditorManageStudent from "@/pages/chief_auditor/usermanager.vue";
import AuditorManageStudent from "@/pages/auditor/usermanager.vue";
import InstructorManageStudent from "@/pages/instructor/usermanager.vue";
import HeadTeacherManageStudent from "@/pages/head_teacher/usermanager.vue";
import HeadTeacherDashboard from "@/pages/head_teacher/dashboard.vue";
import HeadTeacherManageLiveSession from "@/pages/head_teacher/manage_live_session.vue";
import ChiefAuditorDashboard from "@/pages/chief_auditor/auditor_dashboard.vue";
import AdminAddUser from "@/pages/admin/add_user.vue";
import InstructorAddUser from "@/pages/instructor/add_user.vue";
import Login from "@/pages/auth/login.vue";
import Register from "@/pages/auth/register";
import store from "./../state/state";
import Logout from "@/pages/auth/logout";
import ForgetPassword from "@/pages/auth/forget_password.vue";
import ResetPassword from "@/pages/auth/reset_password.vue";


import UserAttendance from "@/pages/general/user_attendance_report.vue";
import EditProfile from "@/pages/general/edit_profile.vue";

import CompanyProfile from "@/pages/company_head/company_profile.vue";
import CompanyManageLiveSessions from "@/pages/company_head/live_sessions.vue";
import CompanyUserManager from "@/pages/company_head/user_manager.vue";
import CompanyAddUser from "@/pages/company_head/add_user.vue";
import CompanyEditUser from "@/pages/company_head/edit_user.vue";
import AssignCourseToCompany from "@/pages/company_head/assign_course_company.vue";
import CompanySetting from "@/pages/company_head/company_setting.vue";
import CourseRequestManager from "@/pages/company_head/apply_course_request.vue";
// category
import CategoryCourse from "@/pages/category/category_manager.vue";
import CourseAddCategory from "@/pages/category/category_add.vue";
import CourseEditCategory from "@/pages/category/category_edit.vue";
import TestComponent from "@/pages/student/test.vue";
import { eventBus } from "@/main";
Vue.use(Router);

const roles = {
  STUDENT: "student",
  HEADMASTER: "headmaster",
  CONTENT_MANAGER: "content_manager",
  COMPANY_HEAD: "company_head",
  HEAD_TEACHER: "head_teacher",
  INSTRUCTOR: "instructor",
  CHIEF_AUDITOR: "chief_auditor",
  AUDITOR: "auditor",
  Teacher_Manager: "teacher_manager",
  ALL: "all"
};

const router = new Router({
  linkExactActiveClass: "active",
  //mode: 'history',
  routes: [
    {
      path: "/test",
      name: "test",
      components: {
        header: AppHeader,
        default: TestComponent,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        title: "Home - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/",
      name: "home",
      components: { header: AppHeader, default: Home, sidebar: Sidebar },
      meta: {
        forVisitors: true,
        title: "Home - LMS",
        sidebar: roles.INSTRUCTOR,
        allowedRoles: [roles.HEADMASTER],
        redirect: {
          [roles.STUDENT]: 'student_dashboard',
          [roles.COMPANY_HEAD]: 'company_profile',
          [roles.CHIEF_AUDITOR]: 'auditor_dashboard',
          [roles.INSTRUCTOR]: 'instructor_manange_user',
          [roles.AUDITOR]: 'user_attendance',
          [roles.HEAD_TEACHER]: 'head_teacher_dashboard',
          [roles.Teacher_Manager]: 'teacher_manager_dashboard',
        }
      }
    },
    {
      path: "/help",
      name: "help",
      components: { header: AppHeader, default: Help, sidebar: Sidebar },
      meta: {
        forVisitors: true,
        title: "Help center - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/forum",
      name: "forum",
      components: { header: AppHeader, default: Forum, sidebar: Sidebar },
      meta: {
        forVisitors: true,
        title: "Forum - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/forum_thread/:id",
      name: "forum_thread",
      components: { header: AppHeader, default: ForumThread, sidebar: Sidebar },
      meta: {
        forVisitors: true,
        title: "Forum Thread - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/login",
      name: "login",
      components: { header: AppHeader, default: Login, sidebar: Sidebar },
      meta: { forVisitors: true, title: "Login - LMS" }
    },
    {
      path: "/forget_password",
      name: "forget_password",
      components: { header: AppHeader, default: ForgetPassword, sidebar: Sidebar },
      meta: { forVisitors: true, title: "Forget password - LMS" }
    },
    {
      path: "/reset_password/:token",
      name: "reset_password",
      components: { header: AppHeader, default: ResetPassword, sidebar: Sidebar },
      meta: { forVisitors: true, title: "Reset password - LMS" }
    },
    // {
      // path: "/register",
      // name: "register",
      // components: { header: AppHeader, default: Register, sidebar: Sidebar },
      // meta: { forVisitors: true, title: "Register - LMS" }
    // },
    {
      path: "/logout",
      name: "logout",
      components: { header: AppHeader, default: Logout, sidebar: Sidebar },
      meta: { forVisitors: true, title: "" }
    },
    {
      path: "/instructor/course_manager",
      name: "instructor_course_manager",
      components: {
        header: AppHeader,
        default: CourseManager,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER, roles.HEADMASTER],
        title: "Manage courses - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/instructor/quiz_manager",
      name: "instructor_quiz_manager",
      components: { header: AppHeader, default: QuizManager, sidebar: Sidebar },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER,roles.INSTRUCTOR, roles.HEADMASTER],
        title: "Manage quizzes - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/instructor/add_quiz",
      name: "instructor_add_quiz",
      components: {
        header: AppHeader,
        default: InstructorAddQuiz,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER,roles.INSTRUCTOR, roles.HEADMASTER],
        title: "Add Quiz - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
	//GS************************************
	{
      path: "/instructor/add_quiz/:course_id/:id",
      name: "instructor_add_quizz",
      components: {
        header: AppHeader,
        default: InstructorAddQuiz,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.INSTRUCTOR, roles.HEADMASTER,roles.COMPANY_HEAD,roles.CONTENT_MANAGER],
        title: "Add Quiz - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/instructor/edit_quiz/:id",
      name: "instructor_edit_quiz",
      components: {
        header: AppHeader,
        default: InstructorEditQuiz,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER,roles.INSTRUCTOR, roles.HEADMASTER,roles.COMPANY_HEAD],
        title: "Edit Quiz - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/instructor/edit_course/:id",
      name: "instructor_edit_course",
      components: {
        header: AppHeader,
        default: InstructorEditCourse,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER, roles.HEADMASTER,roles.CHIEF_AUDITOR],
        title: "Edit course - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
	{
      path: "/instructor/listenings",
      name: "instructor_listening",
      components: {
        header: AppHeader,
        default: InstructorListening,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER, roles.HEADMASTER,roles.CHIEF_AUDITOR,roles.HEAD_TEACHER,roles.INSTRUCTOR],
        title: "Edit course - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/student/attendance",
      name: "student_attendance",
      components: {
        header: AppHeader,
        default: StudentAttendance,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.STUDENT, roles.HEADMASTER],
        title: "Attendance - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/instructor/add_course",
      name: "instructor_add_course",
      components: {
        header: AppHeader,
        default: InstructorAddCourse,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER, roles.HEADMASTER],
        title: "Edit course - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/instructor/lesson_manager",
      name: "instructor_lesson_manager",
      components: {
        header: AppHeader,
        default: LessonManager,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER, roles.HEADMASTER],
        title: "Lesson manager - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/instructor/review_quiz/:user_id/:quiz_id/:attempt_id",
      name: "review_quiz",
      components: {
        header: AppHeader,
        default: InstructorReviewQuiz,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER, roles.HEAD_TEACHER, roles.CONTENT_MANAGER, roles.COMPANY_HEAD],
        title: "Review Quiz - LMS"
      }
    },
	{
      path: "/auditor/review_quiz/:user_id/:quiz_id/:attempt_id",
      name: "review_quizes",
      components: {
        header: AppHeader,
        default: AuditorReviewQuiz,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER, roles.INSTRUCTOR, roles.CONTENT_MANAGER, roles.AUDITOR],
        title: "Review Quiz - LMS"
      }
    },
	{
      path: "/chief_auditor/review_quiz/:user_id/:quiz_id/:attempt_id",
      name: "review_quized",
      components: {
        header: AppHeader,
        default: ChiefAuditorReviewQuiz,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER, roles.INSTRUCTOR, roles.CONTENT_MANAGER, roles.CHIEF_AUDITOR],
        title: "Review Quiz - LMS"
      }
    },
    {
      path: "/instructor/add_lesson",
      name: "instructor_add_lesson",
      components: {
        header: AppHeader,
        default: InstructorAddLesson,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER, roles.HEADMASTER,roles.COMPANY_HEAD],
        title: "Add lesson - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/instructor/add_lesson/:course_id",
      name: "instructor_add_lesson_id",
      components: {
        header: AppHeader,
        default: InstructorAddLesson,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER, roles.HEADMASTER,roles.COMPANY_HEAD],
        title: "Edit lesson - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/instructor/edit_lesson/:id",
      name: "instructor_edit_lesson",
      components: {
        header: AppHeader,
        default: InstructorEditLesson,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: true,
        allowedRoles: [roles.CONTENT_MANAGER, roles.HEADMASTER,roles.COMPANY_HEAD],
        title: "Edit lesson - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/student/dashboard",
      name: "student_dashboard",
      components: {
        header: AppHeader,
        default: StudentDashboard,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT, roles.HEADMASTER],
        title: "Student Dashboard - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/live_lessons",
      name: "student_live_lessons",
      components: {
        header: AppHeader,
        default: StudentLiveLessons,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT, roles.HEADMASTER],
        title: "Student Live Lessons - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/browse_courses",
      name: "student_browse_courses",
      components: {
        header: AppHeader,
        default: StudentBrowseCourses,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "Browse courses - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/my_courses",
      name: "student_my_courses",
      components: {
        header: AppHeader,
        default: StudentMyCourses,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "My courses - LMS",
        sidebar: roles.STUDENT
      }
    },
	{
      path: "/student/courses",
      name: "take_listening",
      components: {
        header: AppHeader,
        default: GetStudentCourses,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "My courses - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/profile",
      name: "student_profile",
      components: {
        header: AppHeader,
        default: studentProfile,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "Profile - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/take_course/:idcourse",
      name: "take_course",
      components: {
        header: AppHeader,
        default: StudentTakeCouse,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "Take course - LMS",
        sidebar: roles.STUDENT
      }
    },
	 {
      path: "/student/listening/:idcourse",
      name: "take_listenings",
      components: {
        header: AppHeader,
        default: StudentListening,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "Take course - LMS",
        sidebar: roles.STUDENT
      }
    },
	 {
      path: "/student/listening",
      name: "take_listening_student",
      components: {
        header: AppHeader,
        default: StudentTakeListening,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "Take course - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/take_quiz/:quiz_id",
      name: "take_quiz",
      components: {
        header: AppHeader,
        default: StudentTakeQuiz,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT, roles.HEADMASTER, roles.INSTRUCTOR],
        title: "Take quiz - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/take_quiz/review_question/:quiz_id/:question_id",
      name: "review_quiz_attempt_question",
      components: {
        header: AppHeader,
        default: StudentTakeQuiz,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT, roles.HEADMASTER, roles.INSTRUCTOR],
        title: "Take quiz - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/review_quiz/:quiz_id/:attempt_id",
      name: "review_quiz_question_and_answer",
      components: {
        header: AppHeader,
        default: StudentReviewQuiz,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT, roles.HEADMASTER, roles.INSTRUCTOR],
        title: "Quiz review - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/view_course/:id",
      name: "view_course",
      components: {
        header: AppHeader,
        default: StudentViewCourse,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "View course - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/my_quizzes",
      name: "my_quizzes",
      components: {
        header: AppHeader,
        default: StudentQuizzes,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT],
        title: "My quizzes - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/quiz_result/:quiz_id/:attempt_id",
      name: "quiz_result",
      components: {
        header: AppHeader,
        default: StudentQuizResult,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT, roles.HEADMASTER, roles.INSTRUCTOR],
        title: "Quiz result - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/assign_course",
      name: "assign_course",
      components: {
        header: AppHeader,
        default: AssignCoursetostudent,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT, roles.HEADMASTER],
        title: "Asign Course To Student - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/company/assign_course",
      name: "assign_course_company",
      components: {
        header: AppHeader,
        default: AssignCourseToCompany,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT, roles.HEADMASTER],
        title: "Asign Course To Company - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/admin/edit_user/:id",
      name: "admin_edit_user",
      components: {
        header: AppHeader,
        default: AdminEditUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER, roles.COMPANY_HEAD, roles.CHIEF_AUDITOR],
        title: "Edit user - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
	 {
      path: "/chief_auditor/edit_user/:id",
      name: "chief_auditor_edit_user",
      components: {
        header: AppHeader,
        default: ChiefAuditorEditUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER, roles.COMPANY_HEAD, roles.CHIEF_AUDITOR],
        title: "Edit user - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
	 {
      path: "/auditor/edit_user/:id",
      name: "auditor_edit_user",
      components: {
        header: AppHeader,
        default: AuditorEditUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER, roles.COMPANY_HEAD, roles.AUDITOR],
        title: "Edit user - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
	{
      path: "/admin/edit_user/:id",
      name: "instructor_edit_user",
      components: {
        header: AppHeader,
        default: AdminEditUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER, roles.COMPANY_HEAD, roles.CHIEF_AUDITOR,roles.INSTRUCTOR],
        title: "Edit user - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
	{
      path: "/instructor/edit_user/:id",
      name: "instructor_edit_users",
      components: {
        header: AppHeader,
        default: EditUserInstructor,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER, roles.INSTRUCTOR, roles.HEAD_TEACHER],
        title: "Edit user - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/admin/add_company",
      name: "admin_add_company",
      components: {
        header: AppHeader,
        default: AdminAddCompany,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER],
        title: "Add new company - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/admin/edit_company/:id",
      name: "admin_edit_company",
      components: {
        header: AppHeader,
        default: AdminEditCompany,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER],
        title: "Edit company - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/admin/manage_student",
      name: "admin_manange_user",
      components: {
        header: AppHeader,
        default: AdminManageStudent,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER],
        title: "Edit profile - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/admin/manage_essays",
      name: "admin_manage_essays",
      components: {
        header: AppHeader,
        default: AdminManageEssays,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER, roles.CONTENT_MANAGER],
        title: "Manage essays - LMS",
        sidebar: roles.HEADMASTER
      }
    },
	{
      path: "/chief_auditor/manage_student",
      name: "chief_auditor_manange_user",
      components: {
        header: AppHeader,
        default: ChiefAuditorManageStudent,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.CHIEF_AUDITOR],
        title: "Edit profile - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
	{
      path: "/auditor/manage_student",
      name: "auditor_manange_user",
      components: {
        header: AppHeader,
        default: AuditorManageStudent,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.AUDITOR],
        title: "Edit profile - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
	{
      path: "/instructor/manage_student",
      name: "instructor_manange_user",
      components: {
        header: AppHeader,
        default: InstructorManageStudent,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.INSTRUCTOR,roles.COMPANY_HEAD],
        title: "Edit profile - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/head_teacher/manage_student",
      name: "head_teacher_manange_user",
      components: {
        header: AppHeader,
        default: HeadTeacherManageStudent,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEAD_TEACHER,roles.COMPANY_HEAD],
        title: "Edit profile - LMS",
        sidebar: roles.HEAD_TEACHER
      }
    },
    {
      path: "/head_teacher/dashboard",
      name: "head_teacher_dashboard",
      components: {
        header: AppHeader,
        default: HeadTeacherDashboard,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEAD_TEACHER,roles.HEADMASTER],
        title: "Dashboard - LMS",
        sidebar: roles.HEAD_TEACHER
      }
    },
    {
      path: "/head_teacher/manage_live_session",
      name: "head_teacher_manage_live_session",
      components: {
        header: AppHeader,
        default: HeadTeacherManageLiveSession,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEAD_TEACHER, roles.HEADMASTER],
        title: "Live lessons - LMS",
        sidebar: roles.HEAD_TEACHER
      }
    },
    {
      path: "/head_teacher/user_attendance_report",
      name: "head_teacher_user_attendance",
      components: {
        header: AppHeader,
        default: UserAttendance,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEAD_TEACHER, roles.HEADMASTER],
        title: "User Attendance Report - LMS",
        sidebar: roles.HEAD_TEACHER
      }
    },
    {
      path: "/admin/add_user",
      name: "admin_add_user",
      components: {
        header: AppHeader,
        default: AdminAddUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        title: "Add new user - LMS",
        allowedRoles: [roles.HEADMASTER,roles.INSTRUCTOR],
        sidebar: roles.INSTRUCTOR
      }
    },
	{
      path: "/chief_auditor/add_user",
      name: "chief_auditor_add_user",
      components: {
        header: AppHeader,
        default: AdminAddUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        title: "Add new user - LMS",
        allowedRoles: [roles.CHIEF_AUDITOR],
        sidebar: roles.INSTRUCTOR
      }
    },
	{
      path: "/auditor/add_user",
      name: "auditor_add_user",
      components: {
        header: AppHeader,
        default: AdminAddUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        title: "Add new user - LMS",
        allowedRoles: [roles.AUDITOR],
        sidebar: roles.INSTRUCTOR
      }
    },
	{
      path: "/instructor/add_user",
      name: "instructor_add_user",
      components: {
        header: AppHeader,
        default: AdminAddUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        title: "Add new user - LMS",
        allowedRoles: [roles.INSTRUCTOR,roles.COMPANY_HEAD],
        sidebar: roles.INSTRUCTOR
      }
    },
	{
      path: "/instructor/add_users",
      name: "instructor_add_users",
      components: {
        header: AppHeader,
        default: InstructorAddUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        title: "Add new user - LMS",
        allowedRoles: [roles.INSTRUCTOR,roles.COMPANY_HEAD],
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/instructor/edit_profile",
      name: "instructor_edit_profile",
      components: { header: AppHeader, default: EditProfile, sidebar: Sidebar },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "Edit profile - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
	{
      path: "/chief_auditor/edit_profile",
      name: "chief_auditor_edit_profile",
      components: { header: AppHeader, default: EditProfile, sidebar: Sidebar },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "Edit profile - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/chief_auditor/user_attendance_report",
      name: "user_attendance",
      components: {
        header: AppHeader,
        default: UserAttendance,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.CHIEF_AUDITOR, roles.HEADMASTER, roles.AUDITOR],
        title: "User Attendance Report - LMS",
        sidebar: roles.CHIEF_AUDITOR
      }
    },
	{
      path: "/auditor/edit_profile",
      name: "auditor_edit_profile",
      components: { header: AppHeader, default: EditProfile, sidebar: Sidebar },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "Edit profile - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/admin/company_manager/",
      name: "admin_company_manager",
      components: {
        header: AppHeader,
        default: AdminCompanyManager,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.HEADMASTER],
        title: "Companies manager - LMS",
        sidebar: roles.INSTRUCTOR
      }
    },
    {
      path: "/student/edit_profile",
      name: "student_edit_profile",
      components: { header: AppHeader, default: EditProfile, sidebar: Sidebar },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT],
        title: "Edit profile - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/essays",
      name: "student_essays",
      components: { header: AppHeader, default: StudentEssays, sidebar: Sidebar },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT],
        title: "Student Essays - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/student/essays/reviews",
      name: "student_essays_reviews",
      components: { header: AppHeader, default: StudentEssaysReviews, sidebar: Sidebar },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.STUDENT],
        title: "Student Essays Reviews - LMS",
        sidebar: roles.STUDENT
      }
    },

    // category
    {
      path: "/course/category/edit",
      name: "category_edit_course",
      components: {
        header: AppHeader,
        default: CourseEditCategory,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        title: "Edit couse category - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/course/category/manage",
      name: "admin_category_course",
      components: {
        header: AppHeader,
        default: CategoryCourse,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        title: "Manage course category - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/course/category/add",
      name: "course_add_category",
      components: {
        header: AppHeader,
        default: CourseAddCategory,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        title: "Add course category - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/company_head/add_user",
      name: "company_add_user",
      components: {
        header: AppHeader,
        default: CompanyAddUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.COMPANY_HEAD, roles.HEADMASTER],
        title: "Add user for Company - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/company_head/user_attendance_report",
      name: "company_user_attendance",
      components: {
        header: AppHeader,
        default: UserAttendance,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.COMPANY_HEAD, roles.HEADMASTER],
        title: "User Attendance Report - LMS",
        sidebar: roles.COMPANY_HEAD
      }
    },
    {
      path: "/company_head/edit_user/:id",
      name: "company_edit_user",
      components: {
        header: AppHeader,
        default: CompanyEditUser,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.COMPANY_HEAD, roles.HEADMASTER],
        title: "Edit user for Company - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/company_head/settings",
      name: "company_setting",
      components: {
        header: AppHeader,
        default: CompanySetting,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.COMPANY_HEAD],
        title: "Company Setting - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/company_head/company_profile",
      name: "company_profile",
      components: {
        header: AppHeader,
        default: CompanyProfile,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "Company Profile - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/company_head/live_sessions",
      name: "manege_live_sessions",
      components: {
        header: AppHeader,
        default: CompanyManageLiveSessions,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.COMPANY_HEAD, roles.HEADMASTER],
        title: "Company Profile - LMS",
        sidebar: roles.COMPANY_HEAD
      }
    },
    {
      path: "/chief_auditor/auditor_dashboard",
      name: "auditor_dashboard",
      components: {
        header: AppHeader,
        default: ChiefAuditorDashboard,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.ALL],
        title: "Auditor Dashboard - LMS",
        sidebar: roles.CHIEF_AUDITOR
      }
    },
    {
      path: "/company_head/user_manager",
      name: "company_user_manager",
      components: {
        header: AppHeader,
        default: CompanyUserManager,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.COMPANY_HEAD, roles.HEADMASTER],
        title: "Company's User Manager - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/company_head/request_manager",
      name: "course_apply_request_manager",
      components: {
        header: AppHeader,
        default: CourseRequestManager,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.COMPANY_HEAD, roles.HEADMASTER],
        title: "Company's Request Manager - LMS",
        sidebar: roles.STUDENT
      }
    },
    {
      path: "/general/essays",
      name: "essay_manage",
      components: {
        header: AppHeader,
        default: ManageEssays,
        sidebar: Sidebar
      },
      meta: {
        forVisitors: false,
        allowedRoles: [roles.COMPANY_HEAD, roles.HEADMASTER, roles.HEAD_TEACHER],
        title: "Manage essays - LMS",
        sidebar: roles.STUDENT
      }
    },

    // Teacher Manager route
  ],
  scrollBehavior: to => {
    if (to.hash) {
      return { selector: to.hash };
    } else {
      return { x: 0, y: 0 };
    }
  }
});

router.beforeEach((to, from, next) => {
  document.title = to.meta.title;
  if (
    (to.name === "login" || to.path === "register") &&
    store.getters.isAuthorized
  ) {
    next("/");
  } else if (
    to.name != "login" &&
    to.name != "register" &&
    to.name != "forget_password" &&
    to.name != "reset_password" &&
    !store.getters.isAuthorized
  ) {
    next({
      path: "login"
    });
  } else {
    next();
  }

  var checkRoleAndRedirect = function () {
    if (_.has(store.getters.authUser, "role")) {
      if (
        _.has(to.meta, "allowedRoles") &&
        to.meta.allowedRoles.indexOf(store.getters.authUser.role) == -1 &&
        to.meta.allowedRoles != "all"
      ) {
        if (_.has(to.meta, "redirect")) {
          for (var key in to.meta.redirect) {
            if (store.getters.authUser.role == key) {
              next({ name: to.meta.redirect[key] })
            }
          }
        } else {
          next({ path: "/404" });
        }
      } else next();
    }
  };

  //----Check role for the first time client visit (wait until auth user is fetched):----//
  eventBus.$on("authUserFetched", e => {
    checkRoleAndRedirect()
  });
  //---If this is not the first time, then check it:---//
  checkRoleAndRedirect()

});

export default router;
