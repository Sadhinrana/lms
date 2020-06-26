<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StudentQuizRequest;
use App\Models\Student_quiz;
use App\Models\Users;
use Laravel\Passport\Client as PassportClient;
use Auth;

class StudentQuizController extends Controller
{
    private $passportClient;

    public function __construct(){
        $this->passportClient = PassportClient::find(2);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null,$paginate= null)
    {
        return $this->show($id,$paginate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userid = auth('api')->user();
        if(in_array($userid->role,config('constant.roles.student'))) {
            $student_quiz = new Student_quiz();
            $student_quiz->student_id = $userid->id;
            $student_quiz->quiz_id    = $request->quiz_id; 
            $student_quiz->time_taken = strtotime($request->time_taken);
            $student_quiz->start_time = strtotime($request->start_time);
            $student_quiz->save();
            return response()->json(["result" => "Add Quiz Successfully"])->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id='',$paginate='')
    {
        if(!is_null($id) && is_numeric($id)) {
            return Student_quiz::find($id);
        } 

        if(!is_null($paginate) && ($id == "all")) {   
            return Student_quiz::paginate($paginate);
        }
        return Student_quiz::all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentQuizRequest $request, $id)
    {
        $userid = auth('api')->user();
        $st_quiz = Student_quiz::find($id);
        if($st_quiz->student_id == $userid->id) {
            $st_quiz = $st_quiz->update([
                'student_id'    => $userid->id,
                'quiz_id'       => $request->quiz_id,
                'time_taken'    => strtotime($request->time_taken),
                'start_time'    => strtotime($request->start_time)
            ]);
            return Response()->json([$request->all()])->setStatusCode(202);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userid = auth('api')->user();
        $quiz= Student_quiz::find($id);
        if( ($quiz->student_id == $userid->id) or (in_array($userid->role,config('constant.dest'))) ) {
            $quiz->delete();
            return response()->json(["result" => "Delete Student Quiz Successfully"])->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }
}
