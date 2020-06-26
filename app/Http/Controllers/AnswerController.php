<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null, $paginate = null)
    {
        return $this->show($id, $paginate);
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

        $answer = new Answer();
        $answer->question_id = $request->question_id;
        $answer->title = $request->title;
        $answer->is_correct = $request->is_correct;
        $answer->save();
        return $answer;

    }

    public function addMultipleAnswerByQuestionID(Request $request)
    {
        foreach ($request["answers"] as $answerData) {
            $answer = new Answer();
            $answer->question_id = $answerData["question_id"];
            $answer->title = $answerData["title"];
			if(!empty($answerData["child_index"])){
				$answer->child_index = $answerData["child_index"];
			}
			if(!empty($answerData["child_question"])){
				$answer->child_question = $answerData["child_question"];
			}
			if(!empty($answerData["formula"])){
				$answer->formula = $answerData["formula"];
			}
            $answer->is_correct = $answerData["is_correct"] == 1 || $answerData["is_correct"] == true ? 1 : 0;
            $answer->save();
        }
    }
	
	public function addMultipleAnswerByQuestionIDForImage(Request $request)
    {
        foreach ($request["answers"] as $answerData) {
            $answer = new Answer();
            $answer->question_id = $answerData["question_id"];
            $answer->title = $answerData["single_choice_as_image"] ? fileUpload($answerData["single_choice_as_image"]) : '';
            $answer->is_correct = $answerData["is_correct"] == 1 || $answerData["is_correct"] == true ? 1 : 0;
            $answer->save();
        }
    }

    public function deleteMultipleAnswers(Request $request)
    {
        $answers = Answer::find($request["answers_to_be_delete_array"]);

        foreach ($answers as $answer) {
            if ($answer->question->type == 'single_choice_as_image') {
                $path = explode('https://celtenglish.s3.eu-north-1.amazonaws.com/', $answer->title, 2);
                Storage::disk('s3')->delete($path[1]);
            }
        }

        Answer::whereIn("id", $request["answers_to_be_delete_array"])->delete();
    }

    public function deleteMultipleAnswersExcept(Request $request)
    {
        Answer::where('question_id', $request->question_id)
                      ->whereNotIn('id', $request->answers_id)
                      ->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = '', $paginate = '')
    {
        if (!is_null($id) && is_numeric($id)) {
            return Answer::find($id);
        }

        if (!is_null($paginate) && ($id == "all")) {
            return Answer::paginate($paginate);
        }
        return Answer::all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userid = auth('api')->user();
        if (in_array($userid->role, config('constant.crud'))) {
            $answer = Answer::find($id);
            $answer = $answer->update([
                'question_id' => $request->question_id,
                'title' => $request->title,
                'is_correct' => $request->is_correct,
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
        if (in_array($userid->role, config('constant.dest'))) {
            $answer = Answer::find($id);
            $answer->delete();
            return response()->json()->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function getAnswerByQuestion($idquest)
    {
        return Answer::Where('question_id', $idquest)->get()->map(function ($item) {
            if ($item->question->type == 'single_choice_as_image') {
                $item->title = $item->title;
            }
            return $item;
        });
    }

    public function updateMultipleAnswer(Request $request)
    {
        $answerInlist = Answer::where('id', $request->answers[0]['id'])->with('question')->first();
        foreach ($request->answers as $answerData) {
            switch ($answerInlist->question->type) {
                case 'true_false':
                case 'yes_no':
                case 'single_choice':
                case 'single_choice_as_image':
                case 'math_set':
                case 'multiple_choice':
                case 'single_set':
                case 'sorting_horizontal':
                case 'summary':
                    $answer = Answer::where('id', $answerData['id'])->first();

                    if ($answerInlist->question->type == 'single_choice_as_image') {
                        $data = $answerData['title'];
                        $data = explode(';base64,', $data, 2);

                        if (count($data) == 2) {
                            try {
                                $img = imagecreatefromstring(base64_decode($data[1]));
                                $path = explode('https://celtenglish.s3.eu-north-1.amazonaws.com/', $answer->title, 2);
                                $answer->title = fileUpload($answerData['title']);
                                Storage::disk('s3')->delete($path[1]);
                            } catch (\Exception $e) {

                            }
                        }
                    } else {
                        $answer->title = $answerData['title'];
                    }
                    if(!empty($answerData["child_index"])){
                            $answer->child_index = $answerData["child_index"];
                        }
                        if(!empty($answerData["child_question"])){
                            $answer->child_question = $answerData["child_question"];
                        }
                        if(!empty($answerData["formula"])){
                            $answer->formula = $answerData["formula"];
                        }
                        $answer->is_correct = $answerData["is_correct"] == 1 || $answerData["is_correct"] == true ? 1 : 0;
                        $answer->save();
                        break;
            }
        }
    }

    public function getImageData(Request $request) {
        $answer = Answer::select('title')->where('id', $request->answer_id)->get();
        $data = $answer[0]->title;
        //return $data;
        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = base64_decode($data);
        return response($data)->header("Content-Type", "image/jpeg");
    }
}
