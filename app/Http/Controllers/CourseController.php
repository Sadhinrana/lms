<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Lesson;
use Laravel\Passport\Client as PassportClient;
use Auth;
use DB;
use App\Models\Users;
use App\Models\Category;
use Intervention\Image\ImageManagerStatic as Image;

class CourseController extends Controller
{
    private $passportClient;

    public function __construct()
    {
        $this->passportClient = PassportClient::find(2);
    }

    public function index($id = null, $paginate = null)
    {
        return $this->showCourse($id, $paginate);
    }

    public function showCourse($id = null, $paginate = '')
    {

        if (!is_null($id) && is_numeric($id)) {
            $course = Course::where('id', $id)->with(['studentCourse' => function ($q) {
                $q->where('student_id', Auth::user()->id);
            }])->with(['quiz', 'companyCourse' => function ($q) {
                $q->select('id', 'company_id', 'course_id')->with(['company' => function ($q) {
                    $q->select('id', 'company_avatar');
                }]);
            }])->first();
            $course["category"] = Category::find($course->category_id);
            return $course;
        }
        if (!is_null($paginate) && ($id == "all")) {
            $paginator = Course::with('lesson', 'category')->orderBy('created_at', 'DESC')->paginate(10);
            return $paginator;
        }
        return Course::orderBy('created_at', 'DESC')->get();

    }

    public function assignedCompanyCourses(Request $request)
    {
        $get_course_id = DB::table('users')
            ->select('users.company_id', 'company_course.course_id')
            ->join('company_course', 'company_course.company_id', '=', 'users.company_id')
            ->where('users.id', Auth::user()->id)
            ->get();

        if (isset($get_course_id) && !empty($get_course_id)) {
            foreach ($get_course_id as $key => $value) {
                $get_course_tuple = Course::where('id', $value->course_id)->first();

                $allCourses[] = $get_course_tuple;
            }

            return $allCourses;
        }
    }

    public function createCourse(CourseRequest $request)
    {
        $auth_user = auth('api')->user();
        if (in_array($auth_user->role, config('constant.route_permission.course.create'))) {
            $course = new Course();
            $course->title = $request->title;
            $course->description = $request->description;
            $course->video_link = $request->video_link;
            $course->category_id = $request->category_id;
            $course->duration = $request->duration;
            $course->instructor_id = $auth_user->id;
            $course->grade_to_pass = $request->grade_to_pass;
            $course->is_published = $request->is_published;
            $course->course_level = $request->course_level;
            if ($request->has("image")) {
                $course->course_image = $this->handleCourseImage($request->image, $course->id, null);
            }
            $course->save();
            return response()->json(["result" => "Create Course Successfully"])->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function editCourse(CourseRequest $request, $id)
    {
        $userid = auth('api')->user();
        if (in_array($userid->role, config('constant.route_permission.course.update'))) {
            $course = Course::where('id', $request->id)->first();
            if ($request->has("image")) {
                $course->course_image = $this->handleCourseImage($request->image, $course->id, null);
            }
            $course = $course->update([
                'title' => $request->title,
                'description' => $request->description,
                'video_link' => $request->video_link,
                'category_id' => $request->category_id,
                'duration' => $request->duration,
                'instructor_id' => $userid->id,
                'is_published' => $request->is_published,
                'grade_to_pass' => $request->grade_to_pass,
                'course_image' => $course->course_image,
                'course_level' => $request->course_level
            ]);
            return Response()->json([$request->all()])->setStatusCode(202);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }

    }

    public function searchCourse(Request $request)
    {
        $course_type = ['ALL' => 0, 'PUBLIC' => 1, 'PRIVATE' => 2, 'DRAFT' => 3];

        return Course::where('title', 'like', "%$request->keyword%")
            ->where(function ($q) use ($request) {
                if ($request->category_id != 0)
                    $q->where('category_id', $request->category_id);
            })
            ->where(function ($q) use ($request, $course_type) {
                switch ($request->course_type) {
                    case $course_type['ALL']:
                        break;
                    case $course_type['PUBLIC']:
                        $q->where('is_published', true);
                        break;
                    case $course_type['PRIVATE']:
                        $q->where('is_published', false);
                        break;
                    case $course_type['DRAFT']:
                        break;
                }
            })->get();
    }

    public function destroyCourse(Request $request)
    {
        $userid = auth('api')->user();
        // if(in_array($userid->role,config('constant.dest'))) {
        $course = Course::where('id', $request->id)->first();
        $course->companyCourse()->delete();
        $course->delete();
        return response()->json()->setStatusCode(200);
        // } else {
        // return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        // }

    }

    public function getAssiendCourse(Request $request)
    {
        $student_courses = DB::table('student_courses')->select('*')->where('student_id', $request->input('student_id'))->get();
        return response()->json($student_courses);
    }

    public function deleteStudentCourse(Request $request)
    {
        DB::table('student_courses')->where(['course_id' => $request->input('course_id'), 'student_id' => $request->input('student_id')])->delete();

        return response()->json(['status' => 200, 'msg' => 'Course deleted successfully.']);
    }

    public function duplicateCourse(Request $req)
    {
        $course = Course::find($req->idcourse);
        $lesson = Lesson::where('course_id', $req->idcourse)->get();
        $newcourse = $course->replicate();
        $newcourse->title = $course->title . "_Duplicate";
        if ($newcourse->save()) {
            foreach ($lesson as $lessonid) {
                $newlesson = $lessonid->replicate();
                $newlesson->title = $lessonid->title . "_Duplicate";
                $newlesson->course_id = $newcourse->id;
                $newlesson->save();
            }
            return response()->json("Duplicate Course Successfully");
        }

    }

    /**
     * Handle image upload
     */
    private function handleCourseImage($image, $course_id, $old_course_image_url)
    {
        if ($old_course_image_url != null && file_exists(public_path($old_course_image_url))) {
            unlink(public_path($old_course_image_url));
        }

        $image = Image::make($image);
        $name = time() . '.jpg';
        $destination_path = public_path("/resources/course/avatar/$course_id/");

        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0775, true);
        }

        $image->fit(300);
        $image->save($destination_path . $name);
        return "/resources/course/avatar/$course_id/" . $name;
    }

    public function getAllCourseForAddQuiz(Request $request)
    {
        return Course::all();
    }

    public function uploadCourseAudio(Request $request)
    {
        $userid = Auth::user()->id;
        if ($request->audio != null) {
            $audio_file = $this->handleCourseAudio($request->audio, $request->course_id);
        }
        DB::table('course_files')->insert(['userid' => $userid, 'course_id' => $request->course_id, 'file_name' => $request->audio_name, 'file_url' => $audio_file]);

    }

    /**
     * Handle audio upload
     */
    private function handleCourseAudio($audio, $question_id)
    {
        // if ($old_audio_file != null && file_exists(public_path($old_audio_file))) {
        // unlink(public_path($old_audio_file));
        // }
        $name = time() . "." . $audio->getClientOriginalExtension();
        $destination_path = public_path("/resources/course/audios/$question_id/");

        if (!is_dir($destination_path)) {
            mkdir($destination_path, 0775, true);
        }
        $audio->move($destination_path, $name);

        return "/resources/course/audios/$question_id/" . $name;
        $image->save($destination_path . $name);
        return "/resources/course/$question_id/" . $name;
    }

    public function getCourseAudio(Request $request)
    {
        $data = DB::table('course_files')->select('id', 'userid', 'course_id', 'file_name', 'file_url')->where('course_id', $request->course_id)->get();
        return Response()->json($data)->setStatusCode(202);
    }

    public function deleteCourseAudio(Request $request)
    {
        DB::table('course_files')->where('id', $request->id)->delete();
    }

    public function essays($id)
    {
        try {
            $essays = Course::findOrFail($id)
                ->essays()
                ->with(['answers' => function($q) {
                $q->with(['reviews' => function($query) {
                    $query->where('is_student', 0)->first();
                }])
                    ->where('user_id', auth()->id())
                    ->latest()
                    ->get();
            }])->get();

            return response()->json(['status' => 200, 'data' => $essays]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }
}
