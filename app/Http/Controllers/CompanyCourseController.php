<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CompanyCourseRequest;
use Laravel\Passport\Client as PassportClient;
use Auth,Hash;
use Illuminate\Http\Response;
use App\Models\CompanyCourse;
use App\Models\Course;
use App\Models\Category;
use App\Models\StudentCourse as StudentCourse;
use App\Models\User;

class CompanyCourseController extends Controller
{
    private $passportClient;

    public function __construct(){
        $this->passportClient = PassportClient::find(2);
    }

    public function index($idcompany=null,$paginate=null){
    	return $this->showCompanyCourse($idcompany,$paginate);
    }

    public function showCompanyCourse($id='',$paginate=''){
        $userid = auth('api')->user();
        if(isset($userid->role)) {
        	if(!is_null($id) && is_numeric($id)) {
        		return CompanyCourse::find($id);
        	}
            if(!is_null($paginate) && ($id == "all")) {
        	   return CompanyCourse::orderBy('id','desc')->paginate($paginate);
            }
            return CompanyCourse::orderBy('id', 'desc')->get();
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function createCompanyCourse(CompanyRequest $request){
        $userid = auth('api')->user();
        if(isset($userid->role) && in_array($userid->role,config('constant.dest'))) {
            $company = new CompanyCourse();
            $company->company_id = $request->txt_company;
            $company->course_id = $request->txt_course;
            $company->save();
            return response()->json([$request->all(),"result" => "Create Company Course Successfully"])->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function editCompanyCourse(CompanyRequest $request, $id){
        $userid = auth('api')->user();
        if(isset($userid->role) && in_array($userid->role,config('constant.dest'))) {
        	$company = CompanyCourse::find($id);
        	$company->update([
        		'company_id' => $request->txt_company,
        		'course_id' => $request->txt_course
        	]);
            return Response()->json([$request->all()])->setStatusCode(202);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }

    }

    public function destroyCompanyCourse($id){
    	$userid = auth('api')->user();
        if(isset($userid->role) && in_array($userid->role,config('constant.dest'))) {
        	$company= CompanyCourse::find($id);
            $company->delete();
            return response()->json("Delete company successfully")->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }

    }

    public function getCoursesByCompanyId($idcompany){
        $companycourse = CompanyCourse::where('company_id',$idcompany)->paginate(10);
        foreach($companycourse as $course) {
            $course['course_content'] = Course::find($course->course_id);
            $course['category'] = Category::find($course['course_content']->category_id);
        }
        return $companycourse;
    }

    public function assignCourseToCompany(Request $req){
        $company = CompanyCourse::where('company_id', '=', $req->txt_company)
              ->where('course_id', '=', $req->txt_course)->first();
        if($company) {
            return response()->json("Course is Duplicate")->setStatusCode(404);
        } else {
            $companycourse = new CompanyCourse();
            $companycourse->company_id = $req->txt_company;
            $companycourse->course_id = $req->txt_course;
            if($companycourse->save()) {
              return response()->json("Assign Course to Student Successfully");
            } else { return response()->json("Error Assign Course To Student"); }
        }
    }

    /**
     * Approve or reject the request to join course of student
     */
    public function acceptApplyCourseRequest(Request $request) {
      try {
        $apply_course_request = StudentCourse::where('id',$request->id)->firstOrFail();
      }
      catch(\Exception $e) {

          exit();
      }

      $apply_course_request->is_approved = true;
      $apply_course_request->save();
    }

    public function rejectApplyCourseRequest(Request $request) {
        try {
            $apply_course_request = StudentCourse::where('id',$request->id)->firstOrFail();
          }
          catch(\Exception $e) {

              exit();
          }

          $apply_course_request->is_rejected = true;
          $apply_course_request->save();
    }

    public function getCourseRequests(){
      if(Auth::user()->role == "headmaster"){
        $requests = StudentCourse::where('is_approved',0)
        ->where('is_rejected',0)
        ->with('course')
        ->with('student')
        ->orderBy('id','DESC')
        ->paginate(10);
		

        return response()->json($requests);
      }else{
        $requests = StudentCourse::whereHas('course',function($q){
            $q->whereHas('companyCourse',function($q){
                $q->where([['company_id',Auth::user()->company_id],['is_rejected',0]]);
            });
        })
        ->with('course')
        ->with(['student'=>function($q){
            $q->with('companyLearning');
        }])
        ->paginate(10);
		

        return response()->json($requests);
      }
    }
    
    public function forceStudentToRegister(Request $request) {
        $studentRequest = StudentCourse::where('id', $request->id)->with('student')->first();
        $user = User::where('id', $studentRequest->student->id)->first();
        $user->is_deleted = true;
        $user->save();
    } 
}
