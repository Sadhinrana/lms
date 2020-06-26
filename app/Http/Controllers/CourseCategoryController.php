<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseCategoryRequest;
use App\Models\CourseCategory;
use Laravel\Passport\Client as PassportClient;
use Auth;
use App\Models\Users;
use Illuminate\Http\Response;
class CourseCategoryController extends Controller
{

    private $passportClient;

    public function __construct(){
        $this->passportClient = PassportClient::find(2);
    }

    public function index($idcate=null,$paginate= null){
        return $this->showCategory($idcate,$paginate);
    }	

    public function showCategory($id = '', $paginate=''){
        if(!is_null($id) && is_numeric($id)) {
            return CourseCategory::find($id);
        } 
        if(!is_null($paginate) && ($id == "all")) {   
            return CourseCategory::orderBy('id','desc')->paginate($paginate);
        }
        return CourseCategory::orderBy('id', 'desc')->get();
    }

    public function createCategory(CourseCategoryRequest $request){
        $userid = auth('api')->user();

        if(in_array($userid->role,config('constant.crud'))) {
            $cate = new CourseCategory();
            $cate->name = $request->txt_name;
            $cate->description = $request->txt_description;
            $cate->save();
            return response()->json(["result" => "Create Category Course Successfully"])->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function editCategory(CourseCategoryRequest $request, $id){
        $userid = auth('api')->user();
        if(in_array($userid->role,config('constant.crud'))) {
        	$cate = CourseCategory::find($id);
        	$cate = $cate->update(['name' => $request->txt_name, 'description' => $request->txt_description]);
            return Response()->json([$request->all()])->setStatusCode(202);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    	
    }

    public function destroyCategory($id){
        $userid = auth('api')->user();
        if(in_array($userid->role,config('constant.dest'))) {
        	$cate= CourseCategory::find($id);
            $cate->delete();
            return response()->json()->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
          
    }

    public function getCategories() {
        return response()->json(CourseCategory::all(),200);
    }

}
