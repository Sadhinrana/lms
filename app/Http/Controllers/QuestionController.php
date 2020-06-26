<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Laravel\Passport\Client as PassportClient;

class QuestionController extends Controller
{
    private $passportClient;

    public function __construct()
    {
        $this->passportClient = PassportClient::find(2);
    }
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
		if(strstr($request->video_link, 'youtu.be')){
			$request->video_link = str_replace('youtu.be', 'www.youtube.com/embed', $request->video_link);
		}

        $userid = auth('api')->user();
        $question = new Question();
        $question->formula = $request->formula;
        $question->description = $request->description;
        $question->type = $request->type;
        $question->score = $request->score;
        if ($request->has('video_link')) {
            $question->video_link = $request->video_link;
        }
        $question->quiz_id = $request->quiz_id;
        $question->parent_id = $request->parent_id;

        // if($request->qimage != null) {
        //     $question->question_image = $this->handleQuestionImage($request->image, $request->quiz_id, null);
        // }
        if ($request->audio != null && isset($_FILES["audio"]["name"])) {
            $question->audio_file = $this->handleQuestionAudio($request->audio, $request->quiz_id, null);
        }else{
			$question->audio_file = null;
		}

        if ($question->save()) {
            $idques = $question->id;
        }
        return response()->json($question)->setStatusCode(200);
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
            $question = Question::find($id);
            if ($question->question_image) {
                $question->question_image = $question->question_image;
            }
            return $question;
        }

        if (!is_null($paginate) && ($id == "all")) {
            return Question::paginate($paginate);
        }
        return Question::all();
    }

    /**
     * Display the specified resource
     *
     * @param int $id_quiz
     * @return \Illuminate\Http\JsonResponse
     */
    public function show_question_of_quiz($id)
    {
        try {
            $questions = Question::where('quiz_id', $id)->where('parent_id', null)->orderBy('sort_id')->with('child_questions')->get()->map(function ($item) {
                if ($item->question_image) {
                    $item->question_image = $item->question_image;
                }
                foreach ($item->child_questions as $child_question) {
                    if ($child_question->type == 'matching_as_image') {
                        $child_question->title = $child_question->title;
                        foreach ($child_question->answers as $answer) {
                            $answer->title = $answer->title;
                        }
                    }
                }
                return $item;
            });

            return $questions;
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

	/*
	show review question pattern.
	*/
	public function review_quiz_pattern($id){
		return Question::select('id')->where('quiz_id', $id)->where('parent_id', null)->orderBy('sort_id')->with('child_questions')->select('id')->get();
	}

    public function getChildQuestion($parent_id)
    {
        return Question::where('parent_id', $parent_id)->orderBy('sort_id')->with('answers')->get()->map(function ($item) {
            if ($item->type == 'matching_as_image' || $item->type == 'matching_text_image') {
                if ($item->type == 'matching_as_image') {
                    $item->title = $item->title;
                }
                foreach ($item->answers as $answer) {
                    $answer->title = $answer->title;
                }
            }

            return $item;
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(Request $request, $id)
    {
		if(strstr($request->video_link, 'youtu.be')){
			$request->video_link = str_replace('youtu.be', 'www.youtube.com/embed', $request->video_link);
		}

        $question = Question::find($id);

        $data = [
            'sort_id' => $id,
            'title' => $request->title,
            'formula' => $request->formula,
            'description' => $request->description,
            'audio_file'  => isset($_FILES["audio"]["name"]) ? $this->handleQuestionAudio($request->audio, $request->quiz_id, null) : $request->audio,
            'question_image'=> $request->image ? fileUpload($request->image) : '',
            'video_link' => $request->has('video_link') ? $request->video_link : '',
            'type' => $request->type,
            'score' => $request->score,
            'quiz_id' => $request->quiz_id,
            'parent_id' => $request->parent_id,
        ];

        $question->update($data);

        return Response()->json([$request->all()])->setStatusCode(202);
    }


	 /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateforEditquestion(Request $request, $id)
    {
		if(strstr($request->video_link, 'youtu.be')){
			$request->video_link = str_replace('youtu.be', 'www.youtube.com/embed', $request->video_link);
		}

        $question = Question::find($id);

        $data = [
            'title' => $request->title,
            'formula' => $request->formula,
            'description' => $request->description,
            'audio_file'  => isset($_FILES["audio"]["name"]) ? $this->handleQuestionAudio($request->audio, $request->quiz_id, $request->audio_file) : $request->audio,
            'video_link' => $request->has('video_link') ? $request->video_link : '',
            'type' => $request->type,
            'score' => $request->score,
            'quiz_id' => $request->quiz_id,
            'parent_id' => $request->parent_id,
        ];

        if ($request->has('image') && $request->image_update) {
            Storage::disk('s3')->delete($question->question_image);
            $data['question_image'] = fileUpload($request->image);
        }

        $question->update($data);

        $request->image_update = false;

        return Response()->json([$request->all()])->setStatusCode(202);
    }

	public function newQuestion(Request $request){
		$question_id = DB::table('question')->insertGetId(['created_at'=>now()]);
		$data['id'] = $question_id;
		return response()->json($data)->setStatusCode(200);
	}

	public function create(Request $request)
    {

		if(strstr($request->video_link, 'youtu.be')){
			$request->video_link = str_replace('youtu.be', 'www.youtube.com/embed', $request->video_link);
		}

        $question = Question::find($request->id);

		$question = $question->update([
            'title' => $request->title,
            'formula' => $request->formula,
            'description' => $request->description,
            'question_image'=> $request->has('image') ? $request->image : '',
            'audio_file'  => isset($_FILES["audio"]["name"]) ? $this->handleQuestionAudio($request->audio, $request->quiz_id, $request->audio_file) : null,
            'video_link' => $request->has('video_link') ? $request->video_link : '',
            'type' => $request->type,
            'score' => $request->score,
            'quiz_id' => $request->quiz_id,
            'parent_id' => $request->parent_id,
        ]);

		return response()->json($question)->setStatusCode(200);
	}

    public function updateQuestionWithAudioFile(Request $request)
    {

        $userid = auth('api')->user();
        $question = Question::where('id', $request->id)->first();
        $question->title = $request->title;
        $question->description = $request->description;
        $question->type = $request->type;
        $question->score = $request->score;
        if (isset($request->video_link)) {
            $question->video_link = $request->video_link;
        }
        $question->quiz_id = $request->quiz_id;
        $question->parent_id = $request->parent_id;

        // if($request->qimage != null) {
        //     $question->question_image = $this->handleQuestionImage($request->image, $request->quiz_id, null);
        // }
        if ($request->audio != null && isset($_FILES["audio"]["name"])) {

            $question->audio_file = $this->handleQuestionAudio($request->audio, $request->quiz_id, $question->audio_file);
        }else{
			$question->audio_file = null;
		}

        if ($question->save()) {
            $idques = $question->id;
        }
        return response()->json($question)->setStatusCode(200);
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
        //return Question::find($id)->child_questions;
        if (in_array($userid->role, config('constant.dest'))) {
            $question = Question::find($id);
            Storage::disk('s3')->delete($question->question_image);
            $question->delete();
            return response()->json()->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

	public function destroy_new($id)
    {
        $userid = auth('api')->user();
        //return Question::find($id)->child_questions;
        if (in_array($userid->role, config('constant.dest'))) {
            $question = Question::find($id);
            $question->delete();
            return response()->json()->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }
    /**
     * Handle image upload
     */
    private function handleQuestionImage($image, $question_id, $old_image_url)
    {
        if ($old_image_url != null && file_exists(public_path($old_image_url))) {
            unlink(public_path($old_image_url));
        }

        $image = Image::make($image);
        $name = time() . '.jpg';
        $destination_path = public_path("/resources/question/images/$question_id/");
        $name = time() . '.jpg';
        $destination_path = public_path("/resources/question/$question_id/");

        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0775, true);
        }

        $image->fit(300);
        $image->save($destination_path . $name);
        return "/resources/question/images/$question_id/" . $name;
    }

    /**
     * Handle audio upload
     */
    private function handleQuestionAudio($audio, $question_id, $old_audio_file)
    {

        if ($old_audio_file != null && file_exists(public_path($old_audio_file))) {
            unlink(public_path($old_audio_file));
        }
        $name = time() . "." . $audio->getClientOriginalExtension();
        $destination_path = public_path("/resources/question/audios/$question_id/");

        if (!is_dir($destination_path)) {
            mkdir($destination_path, 0775, true);
        }
        $audio->move($destination_path, $name);

        return "/resources/question/audios/$question_id/" . $name;
        $image->save($destination_path . $name);
        return "/resources/question/$question_id/" . $name;
    }

    public function getQuestionByQuizID(Request $request)
    {
        $question = Question::where('quiz_id', $request->quiz_id)
            ->where('parent_id', null)
            //->where('id', 837)//5980,5979
			->orderBy('sort_id')
            ->where(function ($q) use ($request) {
                if ($request->except_question_id !== null) {
                    $q->where('id', '!=', $request->except_question_id);
                }
            })
            ->with('child_questions.child_questions.answers')
            ->whereDoesntHave('studentAnswer', function ($q) use ($request) {
                $q->where('student_id', Auth::user()->id)
                    ->where('attempt_id', $request->attempt_id);
            })
            ->take(1)->get()->map(function ($item) {
                if ($item->type == 'single_choice_as_image') {
                    foreach ($item->answers as $answer) {
                        $answer->title = $answer->title;
                    }
                }
                if ($item->type == 'matching_as_image' || $item->type == 'matching_text_image') {
                    foreach ($item->child_questions as $child_question) {
                        if ($item->type == 'matching_as_image') {
                            $child_question->title = $child_question->title;
                        }
                        foreach ($child_question->answers as $answer) {
                            $answer->title = $answer->title;
                        }
                    }
                }
                return $item;
            });

        if (count($question) > 0) {
            //Only random the order of answer if it's not Dropdown question
            if($question[0]->type !== 'dropdown'){
                $question->load(['answers' => function ($query) {
                    $query->inRandomOrder()->get();
                }]);

            }else{
                $question->load('answers');
            }

            //Only random the order of answer if it's not Dropdown question (for child question)
            if($question[0]->child_questions){
                foreach($question[0]->child_questions as $child_question){
                    if($child_question->type !== "dropdown"){
                        if ($child_question->type == "single_choice_as_image") {
                            $answers = Answer::where("question_id", $child_question->id)->inRandomOrder()->get();
                        }
                        else {
                            $answers = Answer::where("question_id", $child_question->id)->inRandomOrder()->get();
                        }
                        $child_question->answers = $answers;
                    }else{
                        $child_question->answers = Answer::where("question_id",$child_question->id)->get();
                    }
                }
            }

            $query = Question::where('quiz_id', $request->quiz_id)
                // ->where('parent_id', null)->orderBy('created_at','asc');
                ->where('parent_id', null)->orderBy('sort_id');
            $totalQuestionsInQuizCount = $query->count();
            $questionIndex = $query->get()->search(function ($questionResult, $key) use ($question) {
                return $questionResult->id == $question[0]->id;
            });


            $data = DB::table('answer')->where('question_id',$question[0]->id)->max('child_index');

            return [
                'child_index' => $data,
                'questions' => $question,
                'questionIndex' => $questionIndex + 1,
                'totalQuestionsInQuizCount' => $totalQuestionsInQuizCount
            ];
        }
    }

    public function getPreviousQuestion(Request $request)
    {
        $questions = Question::where('quiz_id', $request->quiz_id)
            ->where("parent_id", NULL)
            ->where(function ($q) use ($request) {
                if ($request->has('question_id')) {
                    $q->where('id', $request->question_id);
                } else {
                    $q->where('id','<', $request->current_question_id);
                }
            })
            ->with(['studentAnswer' => function ($q) use ($request)  {
                $q->where('student_id', Auth::user()->id)
                    ->where('attempt_id', $request->attempt_id);
            }])
            ->with(['answers' => function ($query) {
                $query->inRandomOrder()->get();
            }])
            //->with('child_questions.answers')
            ->with(['child_questions.studentAnswer' => function ($q) use ($request)  {
                $q->where('student_id', Auth::user()->id)
                    ->where('attempt_id', $request->attempt_id);
            }])
            ->with('child_questions.child_questions.answers')
            ->with(['child_questions.child_questions.studentAnswer' => function ($q) use ($request)  {
                $q->where('student_id', Auth::user()->id)
                    ->where('attempt_id', $request->attempt_id);
            }])
            ->orderBy('id','desc')
            ->take(1)->get()->map(function ($item) {
                if ($item->type == 'single_choice_as_image') {
                    foreach ($item->answers as $answer) {
                        $answer->title = $answer->title;
                    }
                }
                if ($item->type == 'matching_as_image' || $item->type == 'matching_text_image') {
                    foreach ($item->child_questions as $child_question) {
                        if ($item->type == 'matching_as_image') {
                            $child_question->title = $child_question->title;
                        }
                        foreach ($child_question->answers as $answer) {
                            $answer->title = $answer->title;
                        }
                    }
                }
                return $item;
            });

        if(count($questions) > 0 && $questions[0]->child_questions){
            foreach($questions[0]->child_questions as $child_question){
                if ($child_question->type == "single_choice_as_image") {
                    /*$answers = Answer::select("id", "question_id", "is_correct", "answer_order",
                        "child_index", "child_question", "created_at", "updated_at")
                        ->where("question_id", $child_question->id)->get();*/
                    $answers = Answer::where("question_id", $child_question->id)->get();
                }
                else {
                    $answers = Answer::where("question_id", $child_question->id)->inRandomOrder()->get();
                    if ($child_question->type == 'sorting_horizontal') {
                        $answer_content = $child_question->studentAnswer[0]->answer_content;
                        $arr = explode(",", $answer_content);
                        //$arr = array_reverse($arr);
                        $arr1 = [];
                        $bl = false;
                        foreach ($arr as $el) {
                            foreach ($answers as $answer) {
                                if ($el == $answer->id) {
                                    $bl = true;
                                    array_push($arr1, $answer);
                                }
                            }
                        }
                        $child_question->is_answered = 0;
                        if ($bl) {
                            $child_question->is_answered = 1;
                            $answers = $arr1;
                        }

                    }
                }
                $child_question->answers = $answers;
            }
        }

        $query = Question::where('quiz_id', $request->quiz_id)
            ->where('parent_id', null)->orderBy('created_at','asc');
        $totalQuestionsInQuizCount = $query->count();
        $questionIndex = $query->get()->search(function ($questionResult, $key) use ($questions) {
            return $questionResult->id == $questions[0]->id;
        });

        $data = DB::table('answer')->where('question_id',$questions[0]->id)->max('child_index');
        return [
            'child_index' => $data,
            'questions' => $questions,
            'questionIndex' => $questionIndex + 1,
            'totalQuestionsInQuizCount' => $totalQuestionsInQuizCount
        ];
    }

    public function deleteByID(Request $request)
    {
        Question::where('id', $request->id)->delete();
    }

    /*
     ** This method is about creating question & child questions, it's also creating answer for each
     */
    public function createMultipleQuestion(Request $request)
    {
        $question = new Question();
        $question->title = $request->parent_question['title'];
        $question->description = $request->parent_question['description'];
        $question->type = $request->parent_question['type'];
        $question->score = $request->parent_question['score'];
        if ($request->has('video_link')) {
            $question->video_link = $request->parent_question['video_link'];
        }
        $question->quiz_id = $request->parent_question['quiz_id'];
        $question->save();

        foreach ($request->child_questions as $child_question) {
            //Add child question :
            $new_question = new Question();
            if ($question->type == 'matching_as_image') {
                $new_question->title = fileUpload($child_question['question']);
            } else {
                $new_question->title = $child_question['question'];
            }
            $new_question->description = $child_question['question'];
            $new_question->type = $request->parent_question['type'];
            $new_question->score = 0;
            $new_question->quiz_id = $request->parent_question['quiz_id'];
            $new_question->parent_id = $question->id;
            $new_question->save();

            //Add answer for that question :
            $answer = new Answer();
            $answer->question_id = $new_question->id;
            if ($question->type == 'matching_as_image' || $question->type == 'matching_text_image') {
                $answer->title = fileUpload($child_question['answer']);
            } else {
                $answer->title = $child_question['answer'];
            }
            $answer->is_correct = 1;
            $answer->save();
        }
        return $question;
    }

    public function updateMultipleQuestion(Request $request)
    {
        $question = Question::find($request->parent_question['id']);
        $question->title = $request->parent_question['title'];
        $question->description = $request->parent_question['description'];
        $question->type = $request->parent_question['type'];
        $question->score = $request->parent_question['score'];
        if ($request->has('video_link')) {
            $question->video_link = $request->parent_question['video_link'];
        }
        $question->quiz_id = $request->parent_question['quiz_id'];
        $question->save();

        foreach ($request->child_questions as $child_question) {

            if (isset($child_question['id'])) {
                $updating_question = Question::find($child_question['id']);
                if ($question->type == 'matching_as_image') {
                    $data = $child_question['title'];
                    $data = explode(';base64,', $data, 2);

                    if (count($data) == 2) {
                        try {
                            $img = imagecreatefromstring(base64_decode($data[1]));
                            $path = explode('https://celtenglish.s3.eu-north-1.amazonaws.com/', $updating_question->title, 2);
                            $updating_question->title = fileUpload($child_question['title']);
                            Storage::disk('s3')->delete($path[1]);
                        } catch (\Exception $e) {

                        }
                    }
                } else {
                    $updating_question->title = $child_question['title'];
                }
                $updating_question->description = $child_question['description'];
                $updating_question->save();

                $updating_answer = Answer::find($child_question['answers'][0]['id']);
                if ($question->type == 'matching_as_image' || $question->type == 'matching_text_image') {
                    $data = $child_question['answers'][0]['title'];
                    $data = explode(';base64,', $data, 2);

                    if (count($data) == 2) {
                        try {
                            $img = imagecreatefromstring(base64_decode($data[1]));
                            $path = explode('https://celtenglish.s3.eu-north-1.amazonaws.com/', $updating_answer->title, 2);
                            $updating_answer->title = fileUpload($child_question['answers'][0]['title']);
                            Storage::disk('s3')->delete($path[1]);
                        } catch (\Exception $e) {

                        }
                    }
                } else {
                    $updating_answer->title = $child_question['answers'][0]['title'];
                }
                $updating_answer->save();
            } else {
                //Add child question :
                $new_question = new Question();
                if ($question->type == 'matching_as_image') {
                    $new_question->title = fileUpload($child_question['question']);
                } else {
                    $new_question->title = $child_question['question'];
                }
                $new_question->description = $child_question['question'];
                $new_question->type = $request->parent_question['type'];
                $new_question->score = 0;
                $new_question->quiz_id = $request->parent_question['quiz_id'];
                $new_question->parent_id = $question->id;
                $new_question->save();

                //Add answer for that question :
                $answer = new Answer();
                $answer->question_id = $new_question->id;
                if ($question->type == 'matching_as_image' || $question->type == 'matching_text_image') {
                    $answer->title = fileUpload($child_question['answer']);
                } else {
                    $answer->title = $child_question['answer'];
                }
                $answer->is_correct = 1;
                $answer->save();
            }
        }

        return $question;
    }

    public function reorderQuestions(Request $request)
    {
        foreach ($request->order as $index_id=>$question) {

			if (array_key_exists('id', $question)) {
						$parent_question = Question::find($question['id']);
						$parent_question->parent_id = null;
						$parent_question->sort_id = $index_id;
						$parent_question->save();
			}


            if (array_key_exists('children', $question)) {

                foreach ($question['children'] as $sort_id=>$child_question) {
                    $current_question = Question::find($child_question['id']);
					$current_question->sort_id = $sort_id;
					$current_question->parent_id = $question['id'];
					$current_question->save();
                }
            } else {

					$parent_question = Question::find($question['id']);
					$parent_question->parent_id = null;
					$parent_question->sort_id = $index_id;
					$parent_question->save();

            }
        }
        return $request->order;
    }

    public function getAllQuestionByQuizIdForReview(Request $request) {
        return $questions = Question::where('quiz_id', $request->quiz_id)
			->orderBy('sort_id')
            ->with(['studentAnswer' => function ($q) use ($request)  {
                $q->where('student_id', Auth::user()->id)
                    ->where('attempt_id', $request->attempt_id);
            }])->get()->map(function ($item) {
                if ($item->type == 'matching_as_image') {
                    $item->title = $item->title;
                }
                return $item;
            });
    }

	public function getSingleSetParent(Request $request){
		$data = DB::table('question')->select('id','title','type')->where([['quiz_id',$request->quiz_id],['type','single_set'],['parent_id',null]])->get();
		return response()->json($data);
	}

}
