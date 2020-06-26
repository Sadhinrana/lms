<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use Laravel\Passport\Client as PassportClient;
use Auth, Hash, Carbon\Carbon;
use Illuminate\Http\Response;
use App\Models\Company;
use App\Models\CompanyCourse;
use App\Models\User;
use App\Models\QuizAttempt;
use App\Models\StudentCourse;
use Intervention\Image\ImageManagerStatic as Image;
use DB;

class CompanyController extends Controller
{
    private $passportClient;

    public function __construct(){
        $this->passportClient = PassportClient::find(2);
    }

    public function index($idcompany=null,$paginate=null){
    	return $this->showCompany($idcompany,$paginate);
    }

    public function showCompany($id='',$paginate=''){
        // $userid = auth('api')->user();
        //if(isset($userid->role)) {
        	if(!is_null($id) && is_numeric($id)) {
                $company = Company::where('id', $id)->with(['companyCourses' => function($q) {
                    $q->with('course')->get();
                }])->first();	
                /*$company->daily_quizzes_taken_count = QuizAttempt::whereDate('created_at', Carbon::today())->whereHas('quiz', function ($q) use ($company) {
                    $q->whereHas('course', function ($q) use ($company) {
                        $q->whereHas('companyCourse', function($q) use ($company) {
                            $q->where('company_id', $company->id);
                        });
                    });
                })->count();
                $company->monthly_quizzes_taken_count = QuizAttempt::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->whereHas('quiz', function ($q) use ($company) {
                    $q->whereHas('course', function ($q) use ($company) {
                        $q->whereHas('companyCourse', function($q) use ($company) {
                            $q->where('company_id', $company->id);
                        });
                    });
                })->count();
                $company->yearly_quizzes_taken_count = QuizAttempt::whereYear('created_at', Carbon::now()->year)->whereHas('quiz', function ($q) use ($company) {
                    $q->whereHas('course', function ($q) use ($company) {
                        $q->whereHas('companyCourse', function($q) use ($company) {
                            $q->where('company_id', $company->id);
                        });
                    });
                })->count();*/
				

				$get_daily_quizzes_taken_count = DB::select('select count(*) as daily_aggregate from users left JOIN quiz_attempt ON quiz_attempt.user_id = users.id where  date(quiz_attempt.created_at) = "'.Carbon::today().'" AND users.company_id = "'.$company->id.'" AND users.role ="student"');
				
				$company->daily_quizzes_taken_count = $get_daily_quizzes_taken_count[0]->daily_aggregate;

				$get_monthly_quizzes_taken_count = DB::select('select count(*) as monthly_aggregate from users left JOIN quiz_attempt ON quiz_attempt.user_id = users.id where month(date(quiz_attempt.created_at)) = "'.Carbon::now()->month.'" AND users.company_id = "'.$company->id.'" AND users.role ="student"');
				
				$company->monthly_quizzes_taken_count = $get_monthly_quizzes_taken_count[0]->monthly_aggregate;
				
				$get_yearly_quizzes_taken_count = DB::select('select count(*) as yearly_aggregate from users left JOIN quiz_attempt ON quiz_attempt.user_id = users.id where year(date(quiz_attempt.created_at)) = "'.Carbon::now()->year.'" AND users.company_id = "'.$company->id.'" AND users.role ="student"');
				
				$company->yearly_quizzes_taken_count = $get_yearly_quizzes_taken_count[0]->yearly_aggregate;
				
				
                $company->daily_students_registered_count = User::whereDate('created_at', Carbon::today())->where('company_id', $company->id)->count();
                $company->monthly_students_registered_count = User::whereMonth('created_at', '=', Carbon::now()->month)->where('company_id', $company->id)->count();
                $company->yearly_students_registered_count = User::whereYear('created_at', Carbon::now()->year)->where('company_id', $company->id)->count();
             
			   return $company;
        	}
            if(!is_null($paginate) or ($id == "all")) {
                $paginator = Company::orderBy('id', 'desc')->paginate($paginate);
            } else {
                $paginator = Company::orderBy('id', 'desc')->get();
            }
			
			
			
                //---------Add Company Head information to paginator before return-----------//
            $paginator->transform(function ($item, $key) {
				$item['company_head'] = User::where('id',$item->company_head_id)->first();
				// $item['company_head'] = Company::find($item->id)->user()->first();
              return $item;
            });
            return $paginator;
    }
	
	/*
	** Get student record company based.	
	*/
	public function getstudentRecord($id=''){	
		     	if(!is_null($id) && is_numeric($id)) {
						
					return $sql = 	DB::select('SELECT AVG(quiz_attempt.total_score) as average FROM quiz_attempt INNER JOIN users ON users.id = quiz_attempt.user_id WHERE users.company_id="'.$id.'" AND users.role ="student" ');
					 /* $get_users = DB::select('SELECT id FROM users WHERE users.company_id="'.$id.'" AND users.role ="student"');
					echo '<pre>';
						print_r($get_users);
					foreach($get_users as $key=>$value){
						 $sql = DB::select('SELECT AVG(quiz_attempt.total_score) as average FROM quiz_attempt WHERE quiz_attempt.user_id="'.$value->id.'" ');
						echo '<pre>';
						print_r($sql);
					}   */
				
				}
	}
	
	/*
	** Get all company stat for chief auditor.
	*/
	
	public function getallcompanyRecord($id='',$paginate=''){
		
			if(!is_null($id) && is_numeric($id)) {
				//$get_company_head = DB::select('SELECT id,company_id FROM users WHERE users.role ="company_head"');
				$get_company_head = DB::select('SELECT id FROM company');
				foreach ($get_company_head as $key=>$value)
				{	
						
			    $company = Company::where('id', $value->id)->with(['companyCourses' => function($q) {
                    $q->with('course')->get();
                }])->first();
				
               /*  $company->daily_quizzes_taken_count = QuizAttempt::whereDate('created_at', Carbon::today())->whereHas('quiz', function ($q) use ($company) {
                    $q->whereHas('course', function ($q) use ($company) {
                        $q->whereHas('companyCourse', function($q) use ($company) {
                            $q->where('company_id', $company->id);
                        });
                    });
                })->count();
				 */
				
				$get_daily_quizzes_taken_count = DB::select('select count(*) as daily_aggregate from users left JOIN quiz_attempt ON quiz_attempt.user_id = users.id where  date(quiz_attempt.created_at) = "'.Carbon::today().'" AND users.company_id = "'.$company->id.'" AND users.role ="student"');
				
				$company->daily_quizzes_taken_count = $get_daily_quizzes_taken_count[0]->daily_aggregate;
				
				
               /*  $company->monthly_quizzes_taken_count = QuizAttempt::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->whereHas('quiz', function ($q) use ($company) {
                    $q->whereHas('course', function ($q) use ($company) {
                        $q->whereHas('companyCourse', function($q) use ($company) {
                            $q->where('company_id', $company->id);
                        });
                    });
                })->count(); */
				
				$get_monthly_quizzes_taken_count = DB::select('select count(*) as monthly_aggregate from users left JOIN quiz_attempt ON quiz_attempt.user_id = users.id where month(date(quiz_attempt.created_at)) = "'.Carbon::now()->month.'" AND users.company_id = "'.$company->id.'" AND users.role ="student"');
				
				$company->monthly_quizzes_taken_count = $get_monthly_quizzes_taken_count[0]->monthly_aggregate;
				
				/* 
                $company->yearly_quizzes_taken_count = QuizAttempt::whereYear('created_at', Carbon::now()->year)->whereHas('quiz', function ($q) use ($company) {
                    $q->whereHas('course', function ($q) use ($company) {
                        $q->whereHas('companyCourse', function($q) use ($company) {
                            $q->where('company_id', $company->id);
                        });
                    });
                })->count(); */
				
				
				$get_yearly_quizzes_taken_count = DB::select('select count(*) as yearly_aggregate from users left JOIN quiz_attempt ON quiz_attempt.user_id = users.id where year(date(quiz_attempt.created_at)) = "'.Carbon::now()->year.'" AND users.company_id = "'.$company->id.'" AND users.role ="student"');
				
				$company->yearly_quizzes_taken_count = $get_yearly_quizzes_taken_count[0]->yearly_aggregate;

                $company->daily_students_registered_count = User::whereDate('created_at', Carbon::today())->where('company_id', $company->id)->count();
                $company->monthly_students_registered_count = User::whereMonth('created_at', '=', Carbon::now()->month)->where('company_id', $company->id)->count();
                $company->yearly_students_registered_count = User::whereYear('created_at', Carbon::now()->year)->where('company_id', $company->id)->count();
				
				$company->average = 	DB::select('SELECT AVG(quiz_attempt.total_score) as average FROM quiz_attempt INNER JOIN users ON users.id = quiz_attempt.user_id WHERE users.company_id="'.$value->id.'" AND users.role ="student" ');	
				
                $newArray[] =  $company;
						
				}
				
				return $newArray;
						
        	}
		
            if(!is_null($paginate) or ($id == "all")) {
                $paginator = Company::orderBy('id', 'desc')->paginate($paginate);
            } else {
                $paginator = Company::orderBy('id', 'desc')->get();
            }
                //---------Add Company Head information to paginator before return-----------//
            $paginator->transform(function ($item, $key) {
              // $item['company_head'] = Company::find($item->id)->user()->first();
			  $item['company_head'] = User::where('id',$item->company_head_id)->first();
              return $item;
            });
            return $paginator;
		
	}

    public function createCompany(CompanyRequest $request){
        $userid = auth('api')->user();
        if(isset($userid->role) && in_array($userid->role,config('constant.dest'))) {
            $company = new Company();
            $company->name = $request->name;
            $company->description = $request->description;
            $company->company_head_id = $request->instructor;
            $company->city = $request->city;
            $company->country = $request->country;
            $company->save();
            if($request->image != null) {
                $company->company_avatar = $this->handleCompanyAvatar($request->image, $company->id, null);
                $company->save();
            }
            $this->setLevelUser($request->instructor,$company->id);
            return response()->json([$request->all(),"result" => "Create User Successfully"])->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function editCompany(CompanyRequest $request, $id){
        $userid = auth('api')->user();
        if(isset($userid->role) && in_array($userid->role,config('constant.dest'))) {
        	$company = Company::find($id);
            $this->setLevelUser($request->instructor,$company->id);

            if($request->image != null) {
                $company->company_avatar = $this->handleCompanyAvatar($request->image, $company->id, $company->company_avatar);
            }
        	$company->update([
        		'name' => $request->name, 'description' => $request->description,
                'company_head_id' => $request->instructor,
                'city' => $request->city,
                'country' => $request->country,
                'company_avatar' => $company->company_avatar
        	]);

            return Response()->json([$request->all()])->setStatusCode(202);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }

    }

    public function destroyCompany($id){
    	$userid = auth('api')->user();
        if(isset($userid->role) && in_array($userid->role,config('constant.dest'))) {
        	$company= Company::find($id);
            $company->delete();
            return response()->json("Delete company successfully")->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    private function setLevelUser($iduser,$companyid){
        $user = User::find($iduser);
        $user->update(['role'=>config('constant.roles.company_head'),'company_id'=>$companyid]);
        return Response()->json("Set level Successfully")->setStatusCode(202);
    }

    public function GetUserByCompanyId($idcompany, Request $request)
    {
        return User::where('company_id',$idcompany)
          ->where(function ($q) {
              $q->where('role','student')
                  ->orWhere('role','head_teacher');
          })
            ->where(function ($q) use ($request) {
                if ($request->keyword) {
                    $q->where(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$request->keyword%")
                        ->orWhere('email', 'like', "%$request->keyword%");
                }
            })
        ->orderBy('id', 'desc')->paginate(10);
    }
	
	public function searchForUsers(Request $request) {
        return User::where('first_name','like', "%$request->keyword%")
        ->orWhere('last_name','like', "%$request->keyword%")
        ->orWhere('email','like',"%$request->keyword%")
		->orWhere('role','student')
		->orWhere('role','instructor')->get();
    }

	public function searchForUsersOfCompany(Request $request)
    {
        return User::where('company_id',$request->company_id)
		->where('role','student')
		->where(function ($q) use ($request) {
            $q->where(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$request->keyword%")
                ->orWhere('last_name','like', "%$request->keyword%")
                ->orWhere('email','like',"%$request->keyword%");
        })
		->get();
    }

    public function getCompanyInfo() {
        return Company::where('company_head_id',Auth::user()->id)->first();
    }

    public function updateCompanyInfo(Request $request) {
        $this->validate($request,
            [
                'name'=>'required|string',
                'image'=>'imageable',
                'country' =>'required|string',
                'city' =>'required|string',
                'description' => 'required|string'
            ]
        );

        $company = Company::where('company_head_id',Auth::user()->id)->first();
        $company->name = $request->name;
        $company->city = $request->city;
        $company->country = $request->country;
        $company->description = $request->description;
        if ($request->has("image")) {
            $company->company_avatar = $this->handleCompanyImage($request->image,$company->id,$company->company_avatar);
        }
        $company->save();

    }

    private function handleCompanyImage($image,$company_id,$old_company_avatar) {
        if ($old_company_avatar != null && file_exists(public_path($old_company_avatar))) {
            unlink(public_path($old_company_avatar));
        }

        $image = Image::make($image);
        $name = time().'.jpg';
        $destination_path = public_path("/resources/company/avatar/$company_id/");

        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0775, true);
        }

        $image->fit(300);
        $image->save($destination_path.$name);
        return "/resources/company/avatar/$company_id/".$name;
    }

    private function handleCompanyAvatar($image,$company_id,$old_avatar_url) {
        if ($old_avatar_url != null && file_exists(public_path($old_avatar_url))) {
            unlink(public_path($old_avatar_url));
        }

        $image = Image::make($image);
        $name = time().'.jpg';
        $destination_path = public_path("/resources/avatar/company/$company_id/");

        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0775, true);
        }

        $image->fit(300);
        $image->save($destination_path.$name);
        return "/resources/avatar/company/$company_id/".$name;
    }


}
