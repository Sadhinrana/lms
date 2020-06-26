<?php

namespace App\Http\Controllers;

use App\Models\CompanyCourse;
use App\Models\User;
use App\Models\Quizz;
use App\Models\QuizAttempt;
use App\Models\Company;
use App\Models\StudentQuizBlock;

use Illuminate\Http\Request;
use App\Http\Requests\AdminUpdateUserRequest;
use App\Http\Requests\AdminAddUserRequest;
use Intervention\Image\ImageManagerStatic as Image;
use Hash, DB, Auth, Carbon\Carbon;

class AdminController extends Controller
{
    public function addNewUser(AdminAddUserRequest $request){
		$user = new User();
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->phone_number = $request->phone_number;
        $user->password   = Hash::make($request->password);

		if($request->role == 6 ){
			 $user->role  = 'chief_auditor';
		} else if($request->role == 7 ){
			 $user->role = 'auditor';
		}else{
			 $user->role = $this->getRealRoleFromRawNumber($request->role);
		}

        $user->company_id = $request->company;
        if($request->has("image")) {
            $user->avatar_url = $this->handleUserAvatar($request->image, $user->id, null);
        }

        $user->instructor_id = $request->instructor_id;

        if($user->save()) {
            if ($user->role == 'student') {
                foreach ($request->instructors as $instructor) {
                    $user->instructors()->attach($instructor['instructor_id'], ['lesson_mode' => $instructor['lesson_mode'], 'lesson_hour' => $instructor['lesson_hour'], 'study_mode' => $instructor['study_mode'], 'student_group_id' => $instructor['student_group_id']]);
                }
            }

            return response()->json($user);
        }

        return response()->json(['message'=>'failed to add a new user!']);
    }

    /**
     * Get list of all user by newest order
     * @param Request $request
     * @return User json
     */
    public function getUserList(Request $request) {
      return User::orderBy('created_at','desc')
		->where('role','!=','Headmaster')
          ->where(function ($q) use ($request) {
              if ($request->keyword) {
                  $q->where(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$request->keyword%")
                      ->orWhere('email', 'like', "%$request->keyword%");
              }
          })
        ->with("StudentQuizBlock")
        ->paginate(10);
    }

	/**
     * Get list of Student.
     * @param null
     * @return User json
     */
	public function getStudentList(){
		return User::orderBy('created_at','desc')
		->where('role','=','student')
        ->with("StudentQuizBlock")
        ->paginate(10);
	}

	/**
     * Get list of Student as per company based.
     * @param company id if exist
     * @return \Illuminate\Http\JsonResponse
     */
	public function getStudentListCompanyBased()
    {
        try {
            $users = request()->query('page') ?
                auth()->user()
                ->students()
                ->orderBy('created_at', 'desc')
                ->where('role', '=', 'student')
                ->with("StudentQuizBlock")
                ->paginate(30) :
                auth()->user()
                ->students()
                ->orderBy('created_at', 'desc')
                ->where('role', '=', 'student')
                ->with("StudentQuizBlock")
                ->get();

            return response()->json(['status' => 200, 'data' => $users]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Get a user infomation by id
     * @param Request $request
     * @return Response json
     */
    public function getUserInfo(Request $request) {
        $user = User::with(['instructors' => function ($q) {
            $q->select('student_teacher.teacher_id AS instructor_id', 'student_teacher.lesson_mode', 'student_teacher.lesson_hour', 'student_teacher.study_mode', 'student_teacher.student_group_id');
        }])->where('id',$request->id)->first();

        return $user;
    }

    public function searchForUsers(Request $request) {
        return User::where('first_name','like', "%$request->keyword%")
        ->orWhere('last_name','like', "%$request->keyword%")
        ->orWhere('email','like',"%$request->keyword%")->get();
    }

	/*
	 ** Search only student data.
	*/
	public function searchForStudent(Request $request) {
        return User::where('first_name','like', "%$request->keyword%")
		->where('role','=','student')
        ->where('last_name','like', "%$request->keyword%")
        ->where('email','like',"%$request->keyword%")->get();
    }

	/*
	 ** Search only student data company.
	*/
	public function searchForStudentsInstructor(Request $request) {
        return User::where('role','=','student')
		->where('company_id','=',$request->company_id)
		->where('first_name','like', "%$request->keyword%")
        ->where('last_name','like', "%$request->keyword%")
        ->where('email','like',"%$request->keyword%")->get();
    }

    /**
     * Update a user infomation
     * @param AdminUpdateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserInfo(AdminUpdateUserRequest $request) {
        $user = User::where('id',$request->id)->firstOrFail();
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->phone_number      = $request->phone_number;
        $user->password   = $request->has('password') ? Hash::make($request->password) : $user->password;
        $user->role       = $this->getRealRoleFromRawNumber($request->role);
        $user->company_id = $request->company;
        $user->instructor_id = $request->instructor_id;

        if($request->has("image")) {
            $user->avatar_url = $this->handleUserAvatar($request->image, $user->id, $user->avatar_url);
        }

        if($user->save()) {
            if ($user->role == 'student') {
                $instructors = [];
                foreach ($request->instructors as $instructor) {
                    $instructors[$instructor['instructor_id']] = [
                        'lesson_mode' => $instructor['lesson_mode'],
                        'lesson_hour' => $instructor['lesson_hour'],
                        'study_mode' => $instructor['study_mode'],
                        'student_group_id' => $instructor['student_group_id']
                    ];
                }
                $user->instructors()->sync($instructors);
            }

            return response()->json($user);
        }

        return response()->json(['message'=>'information update failed!']);
    }

    /**
     * Delete a user by id
     * @param Request $request
     * @return Response json
     */
    public function deleteUser(Request $request) {
        User::where('id',$request->id)->delete();
    }

    /**
     * Handle image upload
     */
    private function handleUserAvatar($image,$user_id,$old_avatar_url) {
        if ($old_avatar_url != null && file_exists(public_path($old_avatar_url))) {
            unlink(public_path($old_avatar_url));
        }

        $image = Image::make($image);
        $name = time().'.jpg';
        $destination_path = public_path("/resources/avatar/$user_id/");

        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0775, true);
        }

        $image->fit(300);
        $image->save($destination_path.$name);
        return "/resources/avatar/$user_id/".$name;
    }

    /**
     * Get role from number
     * @param String $raw_role
     * @return String
     */
    private function getRealRoleFromRawNumber($raw_role) {
        switch($raw_role){
            case "1":
                return config('constant.roles.headmaster');
            break;
            case "2":
                return config('constant.roles.content_manager');
            break;
            case "3":
                return config('constant.roles.company_head');
            break;
            case "4":
                return config('constant.roles.instructor');
            break;
            case "5":
                return config('constant.roles.student');
            break;
			case "6":
                return config('constant.roles.chief_auditor');
            break;
			case "7":
                return config('constant.roles.auditor');
            break;
            case "8":
                return "head_teacher";
            break;
            case "9":
                return "teacher_manager";
            break;
        }
    }

    public function getDashboardInformation(Request $request) {
        $result['totalQuizzesCount'] = Quizz::count();
        $result['todayQuizzesTakenCount'] =  QuizAttempt::whereDate('created_at', Carbon::today())->count();
        $result['yesterdayQuizzesTakenCount'] = QuizAttempt::whereDate('created_at', Carbon::yesterday())->count();
        $result['weeklyQuizzesTakenCount'] = QuizAttempt::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $result['lastMonthQuizzesTakenCount'] = QuizAttempt::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $result['thisMonthQuizzessTakenCount'] = QuizAttempt::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->count();
        $result['thisYearQuizzessTakenCount'] = QuizAttempt::whereYear('created_at', Carbon::now()->year)->count();
        $result['todayQuizzesTakenPercentage'] = round(($result['todayQuizzesTakenCount'] * 100) / ($result['totalQuizzesCount']?: 100));
        $result['yesterdayQuizzesTakenPercentage'] = round(($result['yesterdayQuizzesTakenCount'] * 100) / ($result['totalQuizzesCount']?:100));
        $result['weeklyQuizzesTakenPercentage'] = round(($result['weeklyQuizzesTakenCount'] * 100) / ($result['totalQuizzesCount']?:100));
        $result['lastMonthQuizzesTakenPercentage'] = round(($result['lastMonthQuizzesTakenCount'] * 100) / ($result['totalQuizzesCount']?:100));
        $result['thisMonthQuizzessTakenPercentage'] = round(($result['thisMonthQuizzessTakenCount'] * 100) / ($result['totalQuizzesCount']?:100));
        $result['thisYearQuizzessTakenPercentage'] = round(($result['thisYearQuizzessTakenCount'] * 100) / ($result['totalQuizzesCount']?:100));
        $result['totalQuizzesTaken'] = QuizAttempt::all()->count();
        $result['mostPopularQuiz'] = QuizAttempt::select('quiz_id', DB::raw('COUNT(quiz_id) as count'))
        ->groupBy('quiz_id')
        ->orderBy('count', 'desc')
        ->with('quiz')
        ->take(1)->first();
        $result['latestQuizAttempt'] = QuizAttempt::orderBy('created_at','desc')->with('quiz')->first();
        $result['mostPopularQuizTakenCount'] = $result['mostPopularQuiz']?(QuizAttempt::where('quiz_id', $result['mostPopularQuiz']->quiz_id)->count()):0;

        $result['companies'] = Company::with('headmaster')->withCount(['students'])->get();
        foreach ($result['companies'] as &$company) {
            $company->teachers_count =
            User::whereHas('course', function ($q) use ($company) {
                $q->whereHas('companyCourse', function($q) use ($company) {
                    $q->where('company_id', $company->id);
                });
            })->count();
            $company->daily_test_taken_count = QuizAttempt::whereDate('created_at', Carbon::today())->whereHas('quiz', function ($q) use ($company) {
                $q->whereHas('course', function ($q) use ($company) {
                    $q->whereHas('companyCourse', function($q) use ($company) {
                        $q->where('company_id', $company->id);
                    });
                });
            })->count();
        }
        return $result;
    }

    public function blockStudentToTakeAllQuizzes(Request $request) {
        $studentQuizBlock = StudentQuizBlock::where('blocker_id', Auth::user()->id)
            ->where('user_id', $request->user_id)->first();

        if ($studentQuizBlock) {
            $studentQuizBlock->description = $request->reason ? $request->reason : null;
            $studentQuizBlock->is_blocked = !$studentQuizBlock->is_blocked;
            $studentQuizBlock->save();
         } else {
            $studentQuizBlock = new StudentQuizBlock();
            $studentQuizBlock->blocker_id = Auth::user()->id;
            $studentQuizBlock->user_id = $request->user_id;
            $studentQuizBlock->is_blocked = true;
            $studentQuizBlock->description = $request->reason ? $request->reason : null;
            $studentQuizBlock->save();
            return $studentQuizBlock;
        }

        return $studentQuizBlock;
    }

    /**
     * Get list of assigned company courses.
     * @param null
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignedCompanyCourses()
    {
        try {
            $assignedCompanyCourses = CompanyCourse::with("company", 'course')
                ->orderBy('id', 'desc')
                ->paginate(10);

            return response()->json(['status' => 200, 'data' => $assignedCompanyCourses]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Destroy assigned company courses.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCompanyCourse(Request $request)
    {
        try {
            CompanyCourse::find($request->id)->delete();

            return response()->json(['status' => 200, 'msg' => 'Course removed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }
}
