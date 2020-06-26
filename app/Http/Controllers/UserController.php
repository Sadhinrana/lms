<?php

namespace App\Http\Controllers;

use App\Models\StudentGroup;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UpdateUserRequest;
use Intervention\Image\ImageManagerStatic as Image;
use Laravel\Passport\Client as PassportClient;
use Auth,Hash;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\QuizAttempt;


class UserController extends Controller
{
	private $passportClient;

    public $role;

    public function __construct(){
        $this->passportClient = PassportClient::find(2);
        $this->role = array(
            config('constant.roles.headmaster'),
            config('constant.roles.company_head')
        );
    }

    public function index($iduser=null,$paginate=null){

    	return $this->showInfoUser($iduser,$paginate);
    }

    public function showInfoUser($id='',$paginate=''){
        $userid = auth('api')->user();

        if(isset($userid->role) && in_array($userid->role,$this->role)) {
        	if(!is_null($id) && is_numeric($id)) {
        		return User::find($id);
        	}
            if(!is_null($paginate) && ($id == "all")) {
        	   return User::paginate($paginate);
            }
            return User::orderBy('id','DESC')->get();
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function showUserByRole($role){
			$user = auth('api')->user();
			if(isset($user->role) && in_array($user->role,$this->role)) {
				return User::where('role',$role)->get();
			}
		}

    public function createUser(UserRequest $request){
        $userid = auth('api')->user();
        if(isset($userid->role) && in_array($userid->role,$this->role)) {
            $user = new User();
            $user->email = $request->txt_email;
            $user->phone_number = $request->txt_phone_number;
            $user->password = Hash::make($request->txt_passwd);
            $user->first_name	= $request->txt_first_name;
            $user->last_name = $request->txt_last_name;
            $user->role = $this->getRealRoleFromRawNumber($request->cmb_role);
            $user->company_id = $request->company_id;
            $user->instructor_id = empty($request->instructor_id)? 0 : $request->instructor_id;
            if($request->has("image")) {
                $user->avatar_url = $this->handleUserAvatar($request->avatar_url, $user->id, null);
            }
            $user->save();

            if ($user->role == 'student') {
                foreach ($request->instructors as $instructor) {
                    $user->instructors()->attach($instructor['instructor_id'], ['lesson_mode' => $instructor['lesson_mode'], 'lesson_hour' => $instructor['lesson_hour'], 'study_mode' => $instructor['study_mode'], 'student_group_id' => $instructor['student_group_id']]);
                }
            }

            return response()->json([$request->all(),"result" => "Create User Successfully"])->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function editUser(UpdateUserRequest $request, $id)
    {
        $userid = auth('api')->user();
        if(isset($userid->role) && in_array($userid->role,$this->role)) {
        	$user = User::find($id);
            if(empty($request->password)){
                $passwd = $user->password;
            } else {
                $passwd = Hash::make($request->password);
            }
            if($request->has("avatar_url") && !empty($request->avatar_url)) {
                $data = explode(';base64,', $request->avatar_url, 2);

                if (count($data) == 2) {
                    try {
                        $img = imagecreatefromstring(base64_decode($data[1]));
                        $avatar_url = $this->handleUserAvatar($request->avatar_url, $user->id, null);
                    } catch (\Exception $e) {
                    }
                }
            }

            $data = [
                'email' => $request->email, 'password' => $passwd, 'phone_number' => $request->phone_number,
                'first_name' => $request->first_name,'last_name' => $request->last_name,
                'role'=> $this->getRealRoleFromRawNumber($request->role),
                'company_id' => $request->company_id, 'instructor_id' => empty($request->instructor_id)? 0 : $request->instructor_id
            ];
            if (isset($avatar_url)) {
                $data['avatar_url'] = $avatar_url;
            }

            $user->update($data);

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

            return Response()->json([$request->all()])->setStatusCode(202);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function getUserInstructor($id){
        $user = User::find($id);
        if($user->role == config('constant.roles.instructor')){
            return $user;
        }
        return response()->json(['result'=>'You do not instructor']);
    }

    public function getInstructorList(Request $request) {
        $companyId = $request->company_id;
        $userList = User::where(["company_id" => $companyId, 'role' => 'head_teacher'])
            ->get();
        return $userList;
    }

    public function getCompanyTeacherList(Request $request)
    {
        $companyId = $request->company_id;
        $teacherList = User::where(["company_id" => $companyId, 'role' => 'head_teacher'])
            ->get();
        foreach ($teacherList as $teacher) {
            $studentList = $teacher->students;
            $teacher->student_count = count($studentList);
            $score = $minus = 0;
            foreach ($studentList as $student) {
                $score += QuizAttempt::where("user_id", $student->id)->avg('total_score');
                if (!QuizAttempt::where("user_id", $student->id)->first()) {
                    $minus += 1;
                }
            }

            $teacher->gpa = $teacher->student_count > 0 && $teacher->student_count - $minus ? ceil($score / ($teacher->student_count - $minus)) : 0;
        }
        return $teacherList;
    }

    public function destroyUser($id){
    	$userid = auth('api')->user();
        	$user= User::find($id);
            $user->delete();
            return response()->json("Delete user successfully")->setStatusCode(200);
      }

        /**
     * Update a user infomation
     * @param UserUpdateRequest $request
     * @return Response json
     */
    public function update(UserUpdateRequest $request) {
        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        if (isset($request->password)){
            $user->password   = Hash::make($request->password);
        }
        if($request->has("image")) {
            $user->avatar_url = $this->handleUserAvatar($request->image, $user->id, $user->avatar_url);
        }

        if($user->save()) {
            return response()->json($user);
        }

        return response()->json(['message'=>'information update failed!']);
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
						//Warning : CHMOD is 0755, not 755. Server Linux required 4 digits
            mkdir($destination_path, 0775, true);
        }

        $image->fit(300);
        $image->save($destination_path.$name);
        return "/resources/avatar/$user_id/".$name;
    }
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
                //return config('constant.roles.head_teacher');
                return "head_teacher";
            break;
        }

    }

    public function getGroups()
    {
        try {
            return response()->json(['status' => 200, 'data' => StudentGroup::all()]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    public function studentTeachers()
    {
        try {
            return response()->json(['status' => 200, 'data' => request()->query('id') ? User::find(request()->query('id'))->instructors : auth()->user()->instructors]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }
}
