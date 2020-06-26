<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use Laravel\Passport\Client as PassportClient;
use Auth;
use Illuminate\Http\Response;
use App\Models\Users;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;


class LessonController extends Controller
{
    private $passportClient;

    public function __construct(){
        $this->passportClient = PassportClient::find(2);
    }

    public function index($id = null,$paginate= null){
    	return $this->showLesson($id,$paginate);
    }

    public function showLesson($id = '', $paginate=''){
        if(!is_null($id) && is_numeric($id)) {
    		return Lesson::find($id);
    	}

        if(!is_null($paginate) && ($id == "all")) {
            return Lesson::paginate($paginate);
        }
        return Lesson::all();
    }


    public function createLesson(LessonRequest $request){
        $userid = auth('api')->user();
        $getorderlesson = DB::table('lesson')->where('course_id',$request->txt_course)->orderby('orders','DESC')->latest()->first();

        if(in_array($userid->role,config('constant.crud'))) {
            $lesson = new Lesson();
            $lesson->title = $request->txt_title;
            $lesson->description = $request->txt_description;
            $lesson->video_link = $request->txt_video_link;
            $lesson->course_id	= $request->txt_course;
            if($request->image != null) {
                $lesson->lesson_image = $this->handleLessonImage($request->image, $request->txt_course, null);
            }
            $lesson->orders = (is_null($getorderlesson) ? 0 : $getorderlesson->orders) + 1;
            $lesson->save();
            return response()->json(["result" => "Create lesson Successfully"])->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }

    }

    public function editLesson(LessonRequest $request, $id){
    	$userid = auth('api')->user();
        if(in_array($userid->role,config('constant.crud'))) {
        	$lesson = Lesson::find($id);
        	$lesson = $lesson->update([
            'title' => $request->txt_title,
        		'description' => $request->txt_description,
        		'video_link' => $request->txt_video_link,
        		'course_id' => $request->txt_course
        	]);
            return Response()->json([$request->all()])->setStatusCode(202);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }

    }

    public function destroyLesson($id){
    	$userid = auth('api')->user();
        if(in_array($userid->role,config('constant.dest'))) {
        	$lesson= Lesson::find($id);
            $lesson->delete();
            return response()->json()->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function orderLesson(Request $req){
        //return $req->ordersid;
    	$post_order = $req->has('ordersid') ? $req->ordersid : [];
    	if(count($post_order) > 0) {
    		foreach($post_order as $idorders) {
    			Lesson::where('id',$idorders[0])->where('course_id',$idorders[2])->update(['orders'=> $idorders[1]]);
    		}
    		return Response()->json(["result" => "Order Successfully"])->setStatusCode(202);
    	}
    	return response()->json(["result" => "Can't order lesson"])->setStatusCode(404);
    }

    public function getLessonByCourseId($Courseid){
        if(!is_null($Courseid)) {
            return Lesson::where('course_id',$Courseid)->get();
        }
        return Lesson::paginate(10);
    }

    public function getQuizCreated(Request $request) {
        return Lesson::where('course_id',$request->course_id)->get();
    }

    public function getLessonByCourseIdWithoutPaginate(Request $request) {
        return Lesson::where('course_id',$request->course_id)->get();
    }

    /**
     * Handle image upload
     */
    private function handleLessonImage($image,$course_id,$old_image_url) {
        if ($old_image_url != null && file_exists(public_path($old_image_url))) {
            unlink(public_path($old_image_url));
        }

        $image = Image::make($image);
        $name = time().'.jpg';
        $destination_path = public_path("/resources/course/$course_id/lesson/");

        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0775, true);
        }

        $image->fit(300);
        $image->save($destination_path.$name);
        return "/resources/course/$course_id/lesson/".$name;
      }
}
