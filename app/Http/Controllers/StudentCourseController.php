<?php

namespace App\Http\Controllers;
use App\Models\StudentCourse as StudentCourse;
use App\Models\Course;
use App\Models\Category;
use App\Models\Quizz;
use App\Models\StudentQuiz;
use App\Models\QuizAttempt;
use App\Models\User;
use DB;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

class StudentCourseController extends Controller
{
    /**
     * Get LIST of course by student
     * return @listOfCourse
     */
    public function getCoursesByStudentId($id){
      $studentCourse = StudentCourse::where('student_courses.student_id', $id)
          ->leftJoin('course', 'course.id', '=', 'student_courses.course_id')
          ->where('student_courses.is_approved',true)
          ->where('course.id', '>', 0)
          ->paginate(10);
      foreach ($studentCourse as $course) {
        $course['course_content'] = Course::find($course->course_id);
        $course['category'] = Category::find($course['course_content']->category_id);
        $course['quiz_completed_count'] = QuizAttempt::where('user_id',$id)
          ->where('is_completed', true)
          ->whereHas('quiz', function($q) use ($course) {
            $q->where('course_id', $course['course_id']);
          })->distinct('quiz_id')->count('quiz_id');

        $course['total_quizzes_count'] = Quizz::where('course_id', $course['course_id'])->count();
        $course['completed_quiz_percentage'] = 0;
        if ($course['total_quizzes_count'] > 0) {
          $course['quiz_completed_percentage'] = round($course['quiz_completed_count'] * 100 / $course['total_quizzes_count']);
        }
      }
      return $studentCourse;
    }

    public function getCoursesByInstructorId(Request $request, $id){
        $UserInfo = User::where('id', $id)->first();
        if($UserInfo->role == 'head_teacher'){
            $userList = User::where('instructor_id', $id)->get()->toArray();
            $arr = array_column($userList, 'id');
            $studentCourse = DB::table("student_courses")
                ->join('users', 'student_courses.student_id', '=', 'users.id')
                ->join('course', 'student_courses.course_id', '=', 'course.id')
                ->where('student_courses.is_approved', true)
                ->where('users.role', 'student')
                ->where('users.company_id', $UserInfo->company_id)
                ->groupBy("student_courses.course_id")
                ->paginate(10);
        } elseif($UserInfo->role == 'headmaster'){
            $studentCourse = DB::table("student_courses")
                ->join('course', 'student_courses.course_id', '=', 'course.id')
                ->where('student_courses.is_approved', true)
                ->groupBy("student_courses.course_id")
                ->paginate(10);
        } else {
            $userList = User::where('instructor_id', $id)->get()->toArray();
            $arr = array_column($userList, 'id');
            $studentCourse = DB::table("student_courses")
                ->join('course', 'student_courses.course_id', '=', 'course.id')
                ->where('student_courses.is_approved', true)
                ->whereIn('student_courses.student_id', $arr)
                ->groupBy("student_courses.course_id")
                ->paginate(10);
        }

        foreach ($studentCourse as $course) {
            $course->course_content = Course::find($course->course_id);
            $course->category = Category::find($course->course_content->category_id);
            $course->quiz_completed_count = QuizAttempt::where('user_id', $course->student_id)
                ->where('is_completed', true)
                ->whereHas('quiz', function($q) use ($course) {
                    $q->where('course_id', $course->course_id);
                })->distinct('quiz_id')->count('quiz_id');

            $course->total_quizzes_count = Quizz::where('course_id', $course->course_id)->count();
            $course->completed_quiz_percentage = 0;
            if ($course->total_quizzes_count > 0) {
                $course->quiz_completed_percentage = round($course->quiz_completed_count * 100 / $course->total_quizzes_count);
            }
        }
        return $studentCourse;
    }

    public function assignCourse(Request $req){
      $student = StudentCourse::where('student_id', '=', $req->txt_student)
              ->where('course_id', '=', $req->txt_course)->first();
      if($student) {
        return response()->json("Course is Duplicate")->setStatusCode(500);
      } else {
        $studentcourse = new StudentCourse();
        $studentcourse->student_id = $req->txt_student;
        $studentcourse->course_id = $req->txt_course;
        $studentcourse->is_approved = 1;
        if($studentcourse->save()) {
          return response()->json("Assign Course to Student Successfully");
        } else { return response()->json("Error Assign Course To Student"); }
      }
    }

    /**
     * Student send a request to apply course
     */
    public function requestToApplyCourse(Request $request){
      try {
        $course = Course::where('id', $request->id)->firstOrFail();
      } catch (\Exception $e) {
        exit();
      }

      $isRejected = StudentCourse::where('course_id',$request->id)
      ->where('is_rejected',true)
      ->where('student_id',Auth::user()->id)->first() ? true : false;

      if ($isRejected) {
        return response()->json(['error'=>'you have been rejected to appy this course'],500);
      }

      $apply_course_request = new StudentCourse();
      $apply_course_request->course_id = $request->id;
      $apply_course_request->student_id = Auth::user()->id;
      $apply_course_request->save();

      return response()->json('success',200);
    }
}
