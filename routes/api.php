<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return view('welcome');
});
/* password reset */
Route::get('test-debug', function(){
    dd('test-debug');
});
Route::post('reset-password', 'ResetPasswordController@sendMail');
Route::post('reset-password/{token}', 'ResetPasswordController@checkIfTokenValid');
Route::put('reset-password', 'ResetPasswordController@resetPassword');

//--------Just For testing---------//
use App\Models\Course;

Route::get('get_captcha',function(){
  $res = app('captcha')->create('default', true);
  return $res;
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->get('/answer_api/get_image_data', 'AnswerController@getImageData');

//Api User routes
Route::get('showuser','UserController@index');
Route::get('showuser/{id?}/{paginate?}','UserController@index');
Route::get('showuserbyrole/{role}','UserController@showUserByRole');
Route::post('adduser','UserController@createUser');
Route::get('groups','UserController@getGroups');
Route::get('student-teachers','UserController@studentTeachers')->middleware('auth:api');
Route::put('edituser/{id}','UserController@editUser');
Route::get('deluser/{id}','UserController@destroyUser');
Route::get('instructor/{id}','UserController@getUserInstructor');
Route::post('get_instructor_list', 'UserController@getInstructorList');
Route::post('get_company_teacher_list', 'UserController@getCompanyTeacherList');
Route::resource('attendances', 'AttendanceController')->only('index', 'store')->middleware('auth:api');
Route::get('student/attendances', 'AttendanceController@getStudentAttendanceReport')->middleware('auth:api');


//Api Company routes
Route::get('showcompany','CompanyController@index');
Route::get('getstudentRecord','CompanyController@getstudentRecord');
Route::get('getstudentRecord/{id?}/{paginate?}','CompanyController@getstudentRecord');
Route::get('showallcompany','CompanyController@getallcompanyRecord');
Route::get('showallcompany/{id?}/{paginate?}','CompanyController@getallcompanyRecord');
Route::get('showcompany/{id?}/{paginate?}','CompanyController@index');
Route::post('addcompany','CompanyController@createCompany');
Route::put('editcompany/{id}','CompanyController@editCompany');
Route::get('delcompany/{id}','CompanyController@destroyCompany');


//Api Company Course routes
Route::get('companycourse','CompanyCourseController@index');
Route::get('companycourse/{id?}/{paginate?}','CompanyCourseController@index');
Route::post('addcompanycourse','CompanyCourseController@createCompanyCourse');
Route::put('editcompanycourse/{id}','CompanyCourseController@editCompanyCourse');
Route::get('delcompanycourse/{id}','CompanyCourseController@destroyCompanyCourse');
Route::get('courseofcompany/{idcompany}','CompanyCourseController@getCoursesByCompanyId');
Route::get('userofcompany/{idcompany}','CompanyController@GetUserByCompanyId');

//StudentCourse
Route::get('getcoursebystudent/{id}','StudentCourseController@getCoursesByStudentId');
Route::get('getcoursebyinstructor/{id}','StudentCourseController@getCoursesByInstructorId');

//Api Lesson routes
Route::get('lesson','LessonController@index');
Route::get('lesson/{id?}/{paginate?}','LessonController@index');
Route::post('addlesson','LessonController@createLesson');
Route::put('editlesson/{id}','LessonController@editLesson');
Route::get('dellesson/{id}','LessonController@destroyLesson');
Route::patch('orderlesson','LessonController@orderLesson');
Route::get('lessonbycourseid/{id}','LessonController@getLessonByCourseId');

//Api Question Routes
Route::get('question','QuestionController@index');
Route::get('question/{id?}/{paginate?}','QuestionController@index');
Route::post('addquestion','QuestionController@store');
Route::post('add_multiple_question','QuestionController@createMultipleQuestion');
Route::post('create','QuestionController@create');
Route::post('newQuestion','QuestionController@newQuestion');
Route::put('updatequestion/{id}','QuestionController@update');
Route::put('updatequestions/{id}','QuestionController@updateforEditquestion');
Route::put('reorder','QuestionController@reorderQuestions');
Route::put('update_multiple_question','QuestionController@updateMultipleQuestion');
Route::get('delquestion/{id}','QuestionController@destroy');
Route::get('delquestion_new/{id}','QuestionController@destroy_new');
Route::get('question-of-quiz/{id}','QuestionController@show_question_of_quiz');
Route::get('review_quiz_pattern/{id}','QuestionController@review_quiz_pattern');
Route::get('get_child_questions/{parent_id}','QuestionController@getChildQuestion');
Route::post('getSingleSetParent','QuestionController@getSingleSetParent');
//Api Answer Routes
Route::get('answer','AnswerController@index');
Route::get('answer/{id?}/{paginate?}','AnswerController@index');
Route::post('addanswer','AnswerController@store');
Route::put('updateanswer/{id}','AnswerController@update');
Route::get('delanswer/{id}','AnswerController@destroy');
Route::get('answerbyquest/{id}','AnswerController@getAnswerByQuestion');

//Api Student Quiz Routes
Route::get('student-quiz','StudentQuizController@index');
Route::get('student-quiz/{id?}/{paginate?}','StudentQuizController@index');
Route::post('addstudent-quiz','StudentQuizController@store');
Route::put('updatestudent-quiz/{id}','StudentQuizController@update');
Route::get('delstudent-quiz/{id}','StudentQuizController@destroy');
Route::get('exam-quiz/{id}','QuizzController@getExamQuizInfoById');

//Api Student Answer Routes
Route::get('student-answer','StudentAnswerController@index');
Route::get('student-answer/{id?}/{paginate?}','StudentAnswerController@index');
Route::get('delstudent-answer/{id}','StudentAnswerController@destroy');
Route::get('get_answer_by_student/{id}','StudentAnswerController@getAnswerByStudentID');
Route::get('get_quiz_result/{student_id}/{quiz_id}/{attempt_id}','StudentAnswerController@getQuizResultByStudentAndQuiz');
Route::get('getResultReview/{student_id}/{quiz_id}/{attempt_id}','StudentAnswerController@getResultReview');
Route::get('get_quiz_result_by_student/{quiz_id}/{attempt_id}','StudentAnswerController@getQuizResultByStudent');
Route::post('addstudent-answer','StudentAnswerController@store');
Route::put('updatestudent-answer/{id}','StudentAnswerController@update');
Route::get('get_quiz_part_score/{student_id}/{quiz_id}/{attempt_id}','StudentAnswerController@getQuizPartScoreResult');


// auth routes
Route::post('/login',['as'=>'api.post.login','uses'=>'CustomAuthController@login']);
Route::post('/register',['as'=>'api.post.register','uses'=>'CustomAuthController@register']);

// api routes
Route::group(['middleware' => ['auth:api']], function() {

    Route::group(['prefix' => 'admin'], function() {
        Route::get('/get_user_list',['as'=>'api.admin.get.user.list','uses'=>'AdminController@getUserList']);
        Route::get('/get_student_list',['as'=>'api.admin.get.student.list','uses'=>'AdminController@getStudentList']);
        Route::get('/get_student_list_company_based',['as'=>'api.admin.get.student.list.company.based','uses'=>'AdminController@getStudentListCompanyBased']);
        Route::get('/get_user_info',['as'=>'api.admin.get.user.info','uses'=>'AdminController@getUserInfo']);
        Route::patch('/update_user',['as'=>'api.admin.patch.user.info','uses'=>'AdminController@updateUserInfo']);
        Route::post('/ad_new_user',['as'=>'api.admin.post.new.user','uses'=>'AdminController@addNewUser']);
        Route::get('/search_for_users', ['as'=>'api.admin.search.users','uses'=>'AdminController@searchForUsers']);
        Route::get('/search_for_students', ['as'=>'api.admin.search.student','uses'=>'AdminController@searchForStudent']);
        Route::get('/search_for_students_instructor', ['as'=>'api.admin.search.students.instructor','uses'=>'AdminController@searchForStudentsInstructor']);
        Route::get('/get_dashboard_informartion',['as'=>'api.admin.get.dashboard','uses'=>'AdminController@getDashboardInformation']);
        Route::post('/block_user_to_take_quizzes',['uses' => 'AdminController@blockStudentToTakeAllQuizzes']);
        Route::get('assigned_company_courses', 'AdminController@assignedCompanyCourses');
        Route::post('deleteCompanyCourse', 'AdminController@deleteCompanyCourse');
        Route::resource('essays', 'EssayController')->except('create', 'show');
        Route::post('essays/{essay}/answer', 'EssayAnswerController@store');
        Route::get('essays/answers', 'EssayAnswerController@answers');
        Route::get('essays/answers-by-teacher', 'EssayAnswerController@answersByTeacher');
        Route::get('essays/answers/{user}/closed', 'EssayAnswerController@usersClosedAnswers');
        Route::post('essays/answers/{answer}', 'EssayAnswerController@update');
        Route::post('essays/review', 'EssayReviewController@store');
        Route::post('essays/review/seen', 'EssayReviewController@update');
    });

    //Api Category routes
    Route::get('category','CourseCategoryController@index');
    Route::get('category/{id?}/{paginate?}','CourseCategoryController@index');
    Route::get('delcategory/{id}','CourseCategoryController@destroyCategory');
    Route::post('addcategory','CourseCategoryController@createCategory');
    Route::put('editcategory/{id}','CourseCategoryController@editCategory');
    Route::get('categories','CourseCategoryController@getCategories');


    //Api Course routes
    Route::get('get_all_course_for_quiz','CourseController@getAllCourseForAddQuiz');
    Route::get('courses/{id?}/{paginate?}','CourseController@index');
    Route::get('course/essays/{course}','CourseController@essays');
    Route::delete('course/delete','CourseController@destroyCourse');
    Route::post('deleteStudentCourse','CourseController@deleteStudentCourse');
    Route::post('addcourse','CourseController@createCourse');
    Route::put('editcourse/{id}','CourseController@editCourse');
    Route::get('searchcourse','CourseController@searchCourse');
    Route::post('duplicateCourse','CourseController@duplicateCourse');
	Route::post('upload_course_audio','CourseController@uploadCourseAudio');
	Route::post('get_course_audio','CourseController@getCourseAudio');
	Route::post('delete_course_audio','CourseController@deleteCourseAudio');
	Route::post('getAssiendCourse','CourseController@getAssiendCourse');
	Route::get('getAssignedCompanyCourses','CourseController@assignedCompanyCourses');
    Route::group(['prefix' => 'user'], function() {
        Route::patch('update','UserController@update');
    });

    Route::post('assigncoursestudent','StudentCourseController@assignCourse');
    Route::post('assigncoursecompany','CompanyCourseController@assignCourseToCompany');

    // approve & reject request for course
    Route::post('apply_course','StudentCourseController@requestToApplyCourse');
    Route::patch('accept_apply_course_request','CompanyCourseController@acceptApplyCourseRequest');
    Route::patch('reject_apply_course_request','CompanyCourseController@rejectApplyCourseRequest');
    Route::get('get_user_request','CompanyCourseController@getCourseRequests');
    Route::post('force_student_to_register','CompanyCourseController@forceStudentToRegister');

    Route::get('get_company_info','CompanyController@getCompanyInfo');
    Route::put('update_company_info','CompanyController@updateCompanyInfo');
	Route::get('search_for_users_company', ['as'=>'api.admin.search.users','uses'=>'CompanyController@searchForUsersOfCompany']);

    // lesson
    Route::get('get_all_lesson_by_course','LessonController@getLessonByCourseIdWithoutPaginate');

    // quiz
    Route::group(['prefix' => 'quiz'], function() {
        Route::get('get_quiz_created','QuizzController@getQuizCreatedByCurrentContentManager');
        Route::post('create','QuizzController@create');
        Route::get('get_by_id','QuizzController@getQuizByID');
        Route::put('update','QuizzController@update');
        Route::get('get_for_manage','QuizzController@getForManage');
        Route::get('get_all','QuizzController@getAll'); // This is only for Headmaster - remember to use middleware for this one
        Route::post('submit_answer','QuizzController@submitAnswer');
        Route::delete('delete_quiz','QuizzController@deleteQuiz');
        Route::get('search_for_quizzes','QuizzController@searchQuizzes');
        Route::post('take_or_continue_quiz','QuizzController@takeOrContinueQuiz');
        Route::post('check_if_quiz_attempt_has_completed', 'QuizzController@checkIfQuizzAttemptHasCompleted');
        Route::post('mark_quiz_attempt_as_done','QuizzController@markQuizAttemptAsDone');
        Route::get('get_quiz_attempts','QuizzController@getQuizAttemptsByAuthID');
        Route::get('get_quiz_attempts_by_user/{user_id}','QuizzController@getQuizAttemptsByUserID');
		Route::post('quiz_block','QuizzController@quiz_block');
		Route::post('getQuizBlock','QuizzController@getQuizBlock');
		Route::post('QuizBlockUpdate','QuizzController@QuizBlockUpdate');
		Route::post('QuizBlockUpdateAttempt','QuizzController@QuizBlockUpdateAttempt');
		Route::post('getQuizForStudent','QuizzController@getQuizForStudent');
    });

    Route::group(['prefix' => 'question_api'],function() {
        Route::get('get_questions','QuestionController@getQuestionByQuizID');
        Route::delete('delete_by_id','QuestionController@deleteByID');
        Route::post('update_question_with_audio','QuestionController@updateQuestionWithAudioFile');
        Route::get('get_previous_question','QuestionController@getPreviousQuestion');
        Route::get('get_all_question_for_review','QuestionController@getAllQuestionByQuizIdForReview');
    });

    Route::group(['prefix' => 'answer_api'], function() {
        Route::post('create_multiple', 'AnswerController@addMultipleAnswerByQuestionID');
        Route::post('create_multiple_for_image', 'AnswerController@addMultipleAnswerByQuestionIDForImage');
        Route::delete('delete_multiple','AnswerController@deleteMultipleAnswers');
        Route::delete('delete_multiple_except','AnswerController@deleteMultipleAnswersExcept');
        Route::put('update_multiple','AnswerController@updateMultipleAnswer');
    });

    // Live sessions route
    Route::resource('live_lessons', 'LiveLessonController')->except('create', 'show');
    Route::get('live_lessons/teacher','LiveLessonController@liveLessonsByTeacher');
    Route::get('live_lessons/student','LiveLessonController@liveLessonsByStudent');
});
