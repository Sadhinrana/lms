<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\Quizz;
use App\Models\StudentAnswer;
use App\Models\StudentQuizBlock;

use App\Jobs\MarkQuizAttemptAsCompleteJob;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Session;
class QuizzController extends Controller
{
	protected $hours;
	protected $minute;
	protected $second;

    public function create(Request $request)
    {
        $this->validate($request,
            [
                'title' => 'required|string',
                //'description' => 'required|string',
                'duration' => 'integer|max:300,min:10',
                'retake_after' => 'integer|max:3000,min:10',
                'grade_to_pass' => 'numeric',
                'previous_quiz_id' => 'integer|nullable',
                'course_id' => 'required|integer',
            ]
        );

        $quizz = new Quizz();
        $quizz->title = $request->title;
        $quizz->description = $request->description;
        $quizz->grade_to_pass = $request->grade_to_pass;
        $quizz->creator_id = Auth::user()->id;
        $quizz->course_id = $request->course_id;
        $quizz->number_of_possible_retake = $request->number_of_possible_retake;

        if ($request->has("grade_to_pass") && $request->grade_to_pass != 0) {
            $quizz->grade_to_pass = $request->grade_to_pass;
        }

        if ($request->has("duration") && $request->duration != 0) {
            $quizz->duration = (int) $request->duration;
        }

        if ($request->has("retake_after") && $request->retake_after != 0) {
            $quizz->retake_after = (int) $request->retake_after;
        }

        if ($request->has("previous_quiz_id")) {
            $quizz->previous_quiz = $request->previous_quiz_id;
        }

        if ($request->has("image")) {
            $quizz->quiz_image = $this->handleQuizImage($request->image, $quizz->id, null);
        }
        $quizz->save();
		// $question_id = DB::table('question')->insertGetId(['created_at'=>now()]);
		// $quizz['question_id'] = $question_id;
        return response()->json($quizz)->setStatusCode(200);
    }

    public function searchQuizzes(Request $request)
    {
         // return Quizz::where('creator_id', Auth::user()->id)->where([['title', 'like', "%$request->keyword%"],['deleted',0]])->get();
        return Quizz::where([['title', 'like', "%$request->keyword%"],['deleted',0]])->get();
    }

    public function update(Request $request)
    {
        $this->validate($request,
            [
                'title' => 'required|string',
                //'description' => 'required|string',
                'duration' => 'integer|max:300,min:10',
                'retake_after' => 'integer',
                'grade_to_pass' => 'numeric',
                'previous_quiz_id' => 'integer',
                'course_id' => 'required|integer',
            ]
        );
        $quizz = Quizz::where('id', $request->id)->first();
        $quizz->title = $request->title;
        $quizz->description = $request->description;
        $quizz->grade_to_pass = $request->grade_to_pass;
        $quizz->course_id = $request->course_id;
        $quizz->number_of_possible_retake = $request->number_of_possible_retake;

        if ($request->has("grade_to_pass") && $request->grade_to_pass != 0) {
            $quizz->grade_to_pass = $request->grade_to_pass;
        }

        if ($request->has("duration") && $request->duration != 0) {
            $quizz->duration = (int) $request->duration;
        }

        if ($request->has("retake_after") && $request->retake_after >= 0) {
            $quizz->retake_after = (int) $request->retake_after;
        }

        if ($request->has("previous_quiz_id")) {
            $quizz->previous_quiz = $request->previous_quiz_id;
        }

        if ($request->has("image")) {
            $quizz->quiz_image = $this->handleQuizImage($request->image, $quizz->id, $quizz->quiz_image);
        }

        $quizz->save();
    }

    public function delete()
    {

    }

    public function getForManage()
    {
        return Quizz::where('creator_id', Auth::user()->id)->paginate(10);
    }

    public function getAll(){
      //return Quizz::all()->paginate(10);
      return Quizz::where('deleted', 0)->orderBy('id','desc')->paginate(10);
    }

    public function getQuizCreatedByCurrentContentManager(Request $requests)
    {
        return Quizz::where('creator_id', Auth::user()->id)->get();
    }

    public function getQuizByID(Request $request)
    {
        return Quizz::where('id', $request->id)->first();
    }

    private function handleQuizImage($image, $quiz_id, $old_quiz_image_url)
    {
        if ($old_quiz_image_url != null && file_exists(public_path($old_quiz_image_url))) {
            unlink(public_path($old_quiz_image_url));
        }

        $image = Image::make($image);
        $name = time() . '.jpg';
        $destination_path = public_path("/resources/quiz/avatar/$quiz_id/");

        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0775, true);
        }

        $image->fit(300);
        $image->save($destination_path . $name);
        return "/resources/quiz/avatar/$quiz_id/" . $name;
    }

    public function submitAnswer(Request $request)
    {
      $deletedOldAnswer = false;
      //return response()->json($request->questions);
        foreach ($request->questions as $question) {
            switch ($question["type"]) {
                case 'single_choice':
                case 'math_set':
				case 'single_choice_as_image':
				case 'math_set':
                case 'yes_no':
                case 'true_false':
				case 'single_set':
				case 'parent':
                    StudentAnswer::where('attempt_id', $request->attempt_id)
                        ->where('quiz_id', $question["quiz_id"])
                        ->where('question_id', $question["id"])
                        ->delete();
                    $student_answer = new StudentAnswer();
                    $student_answer->question_id = $question["id"];
                    $student_answer->quiz_id = $question["quiz_id"];
                    $student_answer->answer_id = $question["selected_answer_id"];
					$student_answer->answer_content = $question['selected_answer_id'];
                    $student_answer->student_id = Auth::user()->id;
                    $student_answer->attempt_id = $request->attempt_id;
                    $student_answer->save();
                    break;
                case 'multiple_choice':
                    StudentAnswer::where('attempt_id', $request->attempt_id)
                        ->where('quiz_id', $question["quiz_id"])
                        ->where('question_id', $question["id"])
                        ->delete();
					if(!empty($question["selected_answers_id"])){
						foreach ($question["selected_answers_id"] as $answer_id) {
							$student_answer = new StudentAnswer();
							$student_answer->question_id = $question["id"];
							$student_answer->quiz_id = $question["quiz_id"];
							$student_answer->answer_content = $answer_id;
							$student_answer->answer_id = $answer_id;
							$student_answer->student_id = Auth::user()->id;
							$student_answer->attempt_id = $request->attempt_id;
							$student_answer->save();
						}
					}else{
						$student_answer = new StudentAnswer();
						$student_answer->question_id = $question["id"];
						$student_answer->quiz_id = $question["quiz_id"];
						$student_answer->answer_content = $question['selected_answer_id'];
						$student_answer->student_id = Auth::user()->id;
						$student_answer->attempt_id = $request->attempt_id;
						$student_answer->save();
					}
                    break;
                case 'fill_in_blank':
                case 'dropdown':
                  if(!$deletedOldAnswer){
                    StudentAnswer::where('attempt_id', $request->attempt_id)
                        ->where('quiz_id', $question["quiz_id"])
                        ->where('question_id', $question["id"])
                        ->delete();
                    $deletedOldAnswer = true;
                  }
                    $student_answer = new StudentAnswer();
                    $student_answer->question_id = $question["id"];
                    $student_answer->quiz_id = $question["quiz_id"];
                    $student_answer->answer_id = $question["selected_answer_id"];
                    $student_answer->answer_content = $question['answer_content'];
                    $student_answer->student_id = Auth::user()->id;
                    $student_answer->attempt_id = $request->attempt_id;
                    $student_answer->save();
                    break;
                case 'drag_drop':
                  if(!$deletedOldAnswer){
                    StudentAnswer::where('attempt_id', $request->attempt_id)
                        ->where('quiz_id', $question["quiz_id"])
                        ->where('question_id', $question["id"])
                        ->delete();
                    $deletedOldAnswer = true;
                  }
                    $student_answer = new StudentAnswer();
                    $student_answer->question_id = $question["id"];
                    $student_answer->quiz_id = $question["quiz_id"];
                    $student_answer->answer_id = $question["selected_answer_id"];
                    $student_answer->answer_content = $question['answer_content'];
                    $student_answer->student_id = Auth::user()->id;
                    $student_answer->attempt_id = $request->attempt_id;
                    $student_answer->save();
                    break;
                case 'matching':
                case 'matching_as_image':
                case 'matching_text_image':
                    StudentAnswer::where('attempt_id', $request->attempt_id)
                        ->where('quiz_id', $question["quiz_id"])
                        ->where('question_id', $question["id"])
                        ->delete();
                    $student_answer = new StudentAnswer();
                    $student_answer->question_id = $question["id"];
                    $student_answer->quiz_id = $question["quiz_id"];
                    $student_answer->answer_id = $question["selected_answer_id"];
					$student_answer->answer_content = $question['selected_answer_id'];
                    $student_answer->student_id = Auth::user()->id;
                    $student_answer->attempt_id = $request->attempt_id;
                    $student_answer->save();
                    break;
                case 'open_end':
                    StudentAnswer::where('question_id' ,$question["id"])
                        ->where('quiz_id', $question["quiz_id"])
                        ->where('attempt_id', $request->attempt_id)
                        ->delete();
                    $student_answer = new StudentAnswer();
                    $student_answer->question_id = $question["id"];
                    $student_answer->quiz_id = $question["quiz_id"];
                    $student_answer->answer_content = $question['answer_content'];
                    $student_answer->student_id = Auth::user()->id;
                    $student_answer->attempt_id = $request->attempt_id;
                    $student_answer->save();
                    break;
                case 'sorting_horizontal':
                case 'summary':
                    StudentAnswer::where('quiz_id', $question["quiz_id"])
                        ->where('question_id' ,$question["id"])
                        ->where('attempt_id', $request->attempt_id)
                        ->delete();
                    $student_answer = new StudentAnswer();
                    $student_answer->question_id = $question["id"];
                    $student_answer->quiz_id = $question["quiz_id"];
                    $student_answer->answer_content = $question['answer_content'];
                    // $student_answer->answer_content = '';
                    // foreach($question['answers'] as $answer){
                    //   $student_answer->answer_content = $student_answer->answer_content.$answer['id'].',';
                    // }
                    $student_answer->student_id = Auth::user()->id;
                    $student_answer->attempt_id = $request->attempt_id;
                    $student_answer->save();
                    break;
            }
        }

        $question = Question::where('quiz_id', $request->questions[0]["quiz_id"])
            ->whereDoesntHave('studentAnswer', function ($q) use ($request) {
                $q->where('student_id', Auth::user()->id)
                    ->where('attempt_id', $request->attempt_id);
            })
            ->where('parent_id', null)
			->orderBy('sort_id')
            ->with(['answers' => function ($query) {
                $query->inRandomOrder()->get();
            }])
            ->with('child_questions')
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

        if(count($question) > 0 && $question[0]->child_questions){
            foreach($question[0]->child_questions as $child_question){
                if ($child_question->type == "sorting_horizontal") {
                    $child_question->description = '';
                }
                if ($child_question->type == "single_choice_as_image") {
                    /*$answers = Answer::select("id", "question_id", "is_correct", "answer_order",
                        "child_index", "child_question", "created_at", "updated_at")
                        ->where("question_id", $child_question->id)->get();*/
                    $answers = Answer::where("question_id", $child_question->id)->get();
                }
                else {
                    $answers = Answer::where("question_id", $child_question->id)->inRandomOrder()->get();
                }
                $child_question->answers = $answers;
            }
        }

        $query = Question::where('quiz_id', $request->questions[0]["quiz_id"])
            // ->where('parent_id', null)->orderBy('created_at','asc');
            ->where('parent_id', null)->orderBy('sort_id');
        $totalQuestionsInQuizCount = $query->count();
        $questionIndex = $query->get()->search(function ($questionResult, $key) use ($question) {
            if (count($question) > 0) {
                return $questionResult->id == $question[0]->id;
            }
            return false;
        });
        if(isset($question[0]['id'])){
			$data = DB::table('answer')->where('question_id',$question[0]['id'])->max('child_index');

			return [
				'child_index' => $data,
				'questions' => $question,
				'questionIndex' => $questionIndex + 1,
				'totalQuestionsInQuizCount' => $totalQuestionsInQuizCount
			];
		}else{
			return [
				'questions' => $question,
				'questionIndex' => $questionIndex + 1,
				'totalQuestionsInQuizCount' => $totalQuestionsInQuizCount
			];
		}
    }

    public function deleteQuiz(Request $request)
    {
        //$quiz = Quizz::where('id', $request->quiz['id'])->delete();
        $quiz = Quizz::where('id', $request->quiz['id'])->update(['deleted' => 1]);
		$update_quiz_blocked = DB::table('quiz_blocked')->where('quiz_id', $request->quiz['id'])->update(['deleted' => 1]);
		//DB::table('quiz_blocked')->where('quiz_id', $request->quiz['id'])->delete();
    }

    public function takeOrContinueQuiz(Request $request)
    {
		$studentQuizBlock = StudentQuizBlock::where('user_id', Auth::user()->id)->first();
        $quiz = Quizz::where('id', $request->id)->first();
        $quizBlock = DB::table('quiz_blocked')->where(['quiz_id' => $request->id, 'student_id' => auth()->id(), 'course_id' => $quiz->course_id])->orderBy('updated_at', 'desc')->first();
        $quizAttempt = QuizAttempt::where('user_id', Auth::user()->id)
            ->where('quiz_id', $request->id)
            ->where('is_completed', 0)->first();

        if ($quizAttempt != null) {
            // if the current time is greater than the quiz duration from the start time: it means time up for this quiz

            if ($quiz->duration !== null && $this->checkIfTakeTimeIsValid($quizAttempt->start_time, $quiz->duration)[0]) {
                $quizAttempt->end_time = date('Y-m-d H:i:s');
                $quizAttempt->is_completed = 1;
                $quizAttempt->save();
                // if the retake time > 0, create a new retake and return it
                if ($quiz->number_of_possible_retake > 0) {
                    $quizAttemptsCount = QuizAttempt::where('quiz_id', $quiz->id)
                        ->where('user_id', Auth::user()->id)
                        ->count();
                    if ($quizAttemptsCount < $quiz->number_of_possible_retake) {
                        $quizAttempt = new QuizAttempt();
                        $quizAttempt->user_id = Auth::user()->id;
                        $quizAttempt->quiz_id = $quiz->id;
                        if ($quiz->duration != null) {
                            $quizAttempt->start_time = $quizBlock->updated_at;
                        }
                        $quizAttempt->save();
                        $quizAttempt->quizDuration = $quiz->duration;
                        $this->performQueueToFinishQuizAttempt($quizAttempt->id, $quizAttempt->quizDuration);
                        return [
                            'quizAttempt' => $quizAttempt,
                            'timeZone' => config('app.timezone'),
                            'studentQuizBlock' => $studentQuizBlock ? $studentQuizBlock : null
                        ];
                    } else {
                        return response()->json(['error' => [
                            'message' => "You have no turn left to take this quiz!",
                            'type' => 'out_of_time',
                        ]], 500);
                    }

                } else {
                    // return the error message
                    return response()->json(['error' => [
                        'message' => "You have no time left for this quiz!",
                        'type' => 'out_of_time',
                    ]], 500);
                }
            }
            else {
                $quizAttempt->quizDuration = $quiz->duration;
                return [
                    'quizAttempt' => $quizAttempt,
                    'timeZone' => config('app.timezone'),
                    'studentQuizBlock' => $studentQuizBlock ? $studentQuizBlock : null
                ];
            }
        }

        if ($quiz->number_of_possible_retake > 0) {

            $quizAttemptCount = QuizAttempt::where('user_id', Auth::user()->id)->where('quiz_id', $quiz->id)->count();
            if (($quizAttemptCount + 1) > $quiz->number_of_possible_retake) {
                return response()->json(['error' => [
                    'message' => "You have no turn left to take this quiz!",
                    'type' => 'out_of_turn',
                ]], 500);
            }
            else {

                // get the newest quiz attempt by current user
                $quizAttempt = QuizAttempt::where('user_id', Auth::user()->id)
                    ->where('quiz_id', $request->id)
                    ->where('is_completed', 1)->orderBy('created_at', 'desc')->first();

                if ($quizAttempt != null) {
                    // if the current time is not greater than the retake after, it means the
                    // student has just done the quiz and have to wait an amount of time to be able to retake this quiz again
                    if (!$this->checkIfTakeTimeIsValid($quizAttempt->end_time, $quiz->retake_after)[0]) {
                        $interval = $this->checkIfTakeTimeIsValid($quizAttempt->end_time, $quiz->retake_after)[1];
                        return response()->json(['error' => [
                            'message' => "Ooops!. You should wait $interval  to retake the exam. Please review your lessons and get ready.",
                            'type' => 'wait_to_retake',
                        ]], 500);
                    }
                }
                $quizAttempt = new QuizAttempt();
                $quizAttempt->user_id = Auth::user()->id;
                $quizAttempt->quiz_id = $quiz->id;
                if ($quiz->duration != null) {
                    $quizAttempt->start_time = $quizBlock->updated_at;
                }
                $quizAttempt->is_completed = 0;
                $quizAttempt->save();
                $quizAttempt->quizDuration = $quiz->duration;
//                $this->performQueueToFinishQuizAttempt($quizAttempt->id, $quizAttempt->quizDuration);
                return [
                    'quizAttempt' => $quizAttempt,
                    'timeZone' => config('app.timezone'),
                    'studentQuizBlock' => $studentQuizBlock ? $studentQuizBlock : null
                ];
            }
        }
        else {
            $quizAttemptsCount = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('user_id', Auth::user()->id)
                ->count();
            if ($quizAttemptsCount === 0) {
                $quizAttempt = new QuizAttempt();
                $quizAttempt->user_id = Auth::user()->id;
                $quizAttempt->quiz_id = $quiz->id;
                if ($quiz->duration != null) {
                    $quizAttempt->start_time = $quizBlock->updated_at;
                }
                $quizAttempt->is_completed = 0;
                $quizAttempt->save();
                $quizAttempt->quizDuration = $quiz->duration;
//                $this->performQueueToFinishQuizAttempt($quizAttempt->id, $quizAttempt->quizDuration);
                return [
                    'quizAttempt' => $quizAttempt,
                    'timeZone' => config('app.timezone'),
                    'studentQuizBlock' => $studentQuizBlock ? $studentQuizBlock : null
                ];
            } else {
                return response()->json(['error' => [
                    'message' => "This quiz is for one time taken.",
                    'type' => 'one_time_taken',
                ]], 500);
            }
        }
    }

    public function checkIfQuizzAttemptHasCompleted(Request $request)
    {
        $studentAnswersCount = StudentAnswer::where('attempt_id', $request->attempt_id)->count();
        $questionsOfCurrentQuizCount = Question::where('quiz_id', $request->quiz_id)->count();
        if ($studentAnswersCount == $questionsOfCurrentQuizCount) {
            $quizAttempt = QuizAttempt::where('quiz_id', $request->quiz_id)
                ->where('user_id', Auth::user()->id)
                ->where('id', $request->attempt_id)
                ->first();
            $quizAttempt->is_completed = true;
            $quizAttempt->end_time = date('Y-m-d H:i:s');
            $quizAttempt->save();
            return response()->json(['completed' => 1]);
        }
        return response()->json(['completed' => 0]);
    }

    public function markQuizAttemptAsDone(Request $request)
    {
        $total_score = 0;
        $result = $this->getQuizResult(auth('api')->user()->id, $request->quiz_id, $request->attempt_id);
		$counts = 0;
		$correct_count = 0;
        foreach ($result as $key => $question) {

			$id_str = [];
			foreach($question as $index => $que){
			//print_r($que);

				if($que['question']['type'] == 'multiple_choice' || $que['question']['type'] == 'single_choice'|| $que['question']['type'] == 'single_choice_as_image' || $que['question']['type'] == 'true_false' || $que['question']['type'] == 'yes_no' || $que['question']['type'] == 'single_set' || $que['question']['type'] == 'math_set'){
					$correct_count = DB::table('answer')->where([['question_id',$que['question_id']],['is_correct',1]])->count();
					if(isset($que['answer']['is_correct']) && $que['answer']['is_correct'] == 1){
						$counts = $counts+1;
					}
				}
				else if($que['question']['type'] == 'drag_drop'){
					$correct_count = DB::table('answer')->where([['question_id',$que['question_id']],['is_correct',1]])->count();

					$student_answer = DB::table('student_answer')->select('answer_content')->where([['answer_id',$que['answer']['id']],['attempt_id',$que['attempt']['id']]])->first();

					if(isset($student_answer->answer_content) && strtolower($student_answer->answer_content) == strtolower($que['answer']['title'])){
						$counts = $counts+1;
						$total_score = $total_score + $que['question']['score']/$correct_count;
						//print_r($total_score .'='. $que['question']['type']);
					}
				}
				else if($que['question']['type'] == 'fill_in_blank'){
					$correct_count = DB::table('answer')->where([['question_id',$que['question_id']],['is_correct',1]])->count();

					$student_answer = DB::table('student_answer')->select('answer_content')->where([['answer_id',$que['answer']['id']],['attempt_id',$que['attempt']['id']]])->first();

					$actual_answer = explode("|",$que['answer']['title']);
					$actual_answer = array_map('strtolower', $actual_answer);
                    $check_actual_answer = preg_replace('/\s+/', ' ', trim(str_replace(array('?', '.'),'', strtolower($student_answer->answer_content))));
                    //dd($actual_answer);
					if(in_array($check_actual_answer,$actual_answer)){
							$counts = $counts+1;
							$total_score = $total_score + $que['question']['score']/$correct_count;
					}

				}
				else if($que['question']['type'] == 'matching' && empty($que['question']['child_questions'])){
					$correct_count = DB::table('question')->where('parent_id',$que['question']['parent_id'])->count();

					$student_answer = DB::table('student_answer')->select('answer_id')->where([['answer_id',$que['answer']['id']],['attempt_id',$que['attempt']['id']]])->first();


					if(isset($student_answer->answer_id) && $student_answer->answer_id == $que['answer']['id']){
						$counts = $counts+1;
					}
				}
				else if($que['question']['type'] == 'matching_as_image' && empty($que['question']['child_questions'])){
					$correct_count = DB::table('question')->where('parent_id',$que['question']['parent_id'])->count();

					$student_answer = DB::table('student_answer')->select('answer_id')->where([['answer_id',$que['answer']['id']],['attempt_id',$que['attempt']['id']]])->first();


					if(isset($student_answer->answer_id) && $student_answer->answer_id == $que['answer']['id']){
						$counts = $counts+1;
					}
				}
				else if($que['question']['type'] == 'matching_text_image' && empty($que['question']['child_questions'])){
					$correct_count = DB::table('question')->where('parent_id',$que['question']['parent_id'])->count();

					$student_answer = DB::table('student_answer')->select('answer_id')->where([['answer_id',$que['answer']['id']],['attempt_id',$que['attempt']['id']]])->first();


					if(isset($student_answer->answer_id) && $student_answer->answer_id == $que['answer']['id']){
						$counts = $counts+1;
						//print_r($total_score .'='. $que['question']['type']);
					}
				}
				else if($que['question']['type'] == 'dropdown'){
					$correct_count = DB::table('answer')->where([['question_id',$que['question_id']],['is_correct',1]])->count();

					if($que['answer']['is_correct'] == 1){
						$counts = $counts+1;
						$total_score = $total_score + $que['question']['score']/$correct_count;
						//print_r($total_score .'='. $que['question']['type']);
					}
				}
				else if($que['question']['type'] == 'summary' || $que['question']['type'] == 'sorting_horizontal'){
					$correct = DB::table('answer')->select('id')->where('question_id',$que['question_id'])->get();
					$data = json_decode(json_encode($correct),true);
					$dd = explode(",",$que['answer_content']);
					foreach($data as $k => $value){
						$correct_count = $k+1;
						if( $value['id'] == $dd[$k]){
							$counts = $k+1;
						}
					}

				}

				if($correct_count == $counts && $correct_count != 0 && $counts != 0){
					if($que['question']['type'] != 'fill_in_blank' && $que['question']['type'] != 'drag_drop' && $que['question']['type'] != 'dropdown'){
						if($que['question']['type'] == 'matching' || $que['question']['type'] == 'matching_as_image' || $que['question']['type'] == 'matching_text_image'){
							$score = DB::table('question')->select('score')->where('id',$que['question']['parent_id'])->first();
							$total_score = $total_score + $score->score;
						}else{
							$total_score = $total_score + $que['question']['score'];
						}
						//print_r($total_score .'='. $que['question']['type']);
					}
					$counts = 0;
					$correct_count = 0;
				}

			}
        }

        $quizAttempt = QuizAttempt::where('quiz_id', $request->quiz_id)
            ->where('user_id', Auth::user()->id)
            ->where('id', $request->attempt_id)
            ->first();
        $quizAttempt->is_completed = true;
        $quizAttempt->end_time = date('Y-m-d H:i:s');
        $quizAttempt->total_score = $total_score;
        $quizAttempt->save();

        DB::table('quiz_blocked')
            ->where([['quiz_id', $request->quiz_id], ['student_id', Auth::user()->id]])
            ->update(['is_block' => 1]);

        return response()->json(['marked' => false]);
    }

    private function checkIfTakeTimeIsValid($startTime, $duration)
    {

        $time = new \DateTime($startTime);
        $time->add(new \DateInterval('PT' . $duration . 'M'));

        $overallTime = $time->format('Y-m-d H:i:s');
        $currentDateTime = date('Y-m-d H:i:s');
        $interval = date_diff(new \DateTime($overallTime), new \DateTime($currentDateTime))->format('%h:%i:%s');
        return [$currentDateTime > $overallTime, $interval];
    }

    private function getQuizAttempts($user_id)
    {
        $quizAttempts = QuizAttempt::where(['user_id' => $user_id, 'is_completed' => 1])->with('quiz')->orderBy('id','DESC')
            ->get()
            ->each(function ($attempt) {
                  $attempt->questions_count = Question::where('quiz_id', $attempt->quiz_id)->count();
                  $attempt->questions_answered_count = StudentAnswer::where('quiz_id', $attempt->quiz_id)
                      ->where('attempt_id', $attempt->id)
                      ->count();
                  $attempt->quiz_attempt = QuizAttempt::where('id', $attempt->id)->first();
                  $attempt->question_anwered_percentage = 0;
                  if ($attempt->questions_count > 0) {
                      $attempt->question_anwered_percentage = round((intval($attempt->questions_answered_count) * 100)
                          / intval($attempt->questions_count));
                  }
              });

          //Return pagination for collection from AppServiceProvider
          return $quizAttempts->paginate(16);
    }

	public function quiz_block(Request $request)
    {
		$get_user = DB::table('users')->select('id')->where('is_deleted',0)->get();

		foreach($get_user as $val){
			$question_id = DB::table('quiz_blocked')->insert(['student_id' => $val->id, 'course_id' => $request->input('course_id'), 'quiz_id' => $request->input('quiz_id'), 'quiz_name' => $request->input('quiz_name')]);
		}
	}

	public function getQuizBlock(Request $request)
    {
        $nowStr = date("Y-m-d H:i:s");
		$data = $request->input('data');
		$delete_flag =0;
		$get_user = DB::table('quiz')->select('*')->where('course_id',$data['course_id'])->get();
		$get_user = json_decode(json_encode($get_user),true);

		foreach($get_user as $val){
			$check_quiz = DB::table('quiz_blocked')->select('id')->where([['student_id',$data['student_id']],['course_id',$data['course_id']],['quiz_id',$val['id']]])->first();
			if(!isset($check_quiz->id)){
			    $cur_date = date('Y-m-d H:i:s');
				DB::table('quiz_blocked')->insert(['student_id'=>$data['student_id'], 'course_id'=>$data['course_id'], 'quiz_id'=>$val['id'], 'quiz_name'=>$val['title'], 'is_block'=>'1','deleted'=>$val['deleted'], 'updated_at' => $cur_date]);
			}
		}

		$get_quiz = DB::table('quiz_blocked')->select('*')->where([['student_id',$data['student_id']],['course_id',$data['course_id']]])->where('deleted',$delete_flag)->get();

        foreach ($get_quiz as &$quiz) {
            $q = Quizz::find($quiz->quiz_id);
            DB::table('quiz_blocked')
                ->where('id', $quiz->id)
                ->whereRaw('DATE_ADD(updated_at, INTERVAL "' . $q->duration . '" MINUTE) <= "' . $nowStr . '"')
                ->update(['is_block' => 1]);
            if (Carbon::parse($quiz->updated_at)->addMinute($q->duration)->format('Y-m-d H:i:s') <= $nowStr) {
                $quiz->is_block = 1;
            }
        }

		return response()->json($get_quiz);
	}

	public function QuizBlockUpdate(Request $request){
		$data = $request->input();
        $cur_date = date('Y-m-d H:i:s');
		if(!isset($data['is_block'])){
			DB::table('quiz_blocked')->where('quiz_id',$data['quiz_id'])->update(['quiz_name'=>$data['quiz_name'], 'updated_at' => $cur_date]);
		}else{
			DB::table('quiz_blocked')->where('id',$data['id'])->update(['is_block'=>$data['is_block'], 'updated_at' => $cur_date]);
		}
	}

	/*
	** @return quiz blocked if student pass the exam.
	*/
	public function QuizBlockUpdateAttempt(Request $request){
		$data = $request->input();
        $cur_date = date('Y-m-d H:i:s');
		if(!empty($data['student_id'])){
			DB::table('quiz_blocked')->where([['student_id',$data['student_id']],['quiz_id',$data['quiz_id']]])->update(['is_block'=>$data['is_block'], 'updated_at' => $cur_date]);
		} else {
			DB::table('quiz_blocked')->where([['student_id',$data['student_id']],['quiz_id',$data['quiz_id']]])->update(['is_block'=>$data['is_block'], 'updated_at' => $cur_date]);
		}
	}

	public function getQuizForStudent(Request $request)
    {
		$data = $request->input();
		$delete_flag = 0;
		$nowStr = date("Y-m-d H:i:s");
		$ret = DB::table('quiz_blocked')
            ->where('is_block', 3)
            ->whereRaw('DATE_ADD(updated_at, INTERVAL 90 MINUTE) <= "'.$nowStr.'"')
            ->update(['is_block' => 1]);

		$get_quiz = DB::table('quiz_blocked')
            ->select('quiz_blocked.*', 'quiz.duration')
            ->join('quiz', 'quiz.id', '=', 'quiz_blocked.quiz_id')
            ->where([['quiz_blocked.course_id',$data['course_id']],['quiz_blocked.student_id',Auth::user()->id]])
            /*->where(function ($q) {
                $q->where('is_block',2)
                    ->orWhere('is_block',3);
            })*/
            ->where('quiz_blocked.deleted',$delete_flag)->orderByRaw('LENGTH(quiz_blocked.id) desc')->orderBy('quiz_blocked.id', 'asc')->get();

        foreach ($get_quiz as &$quiz) {
            $q = Quizz::find($quiz->quiz_id);
            DB::table('quiz_blocked')
                ->where('id', $quiz->id)
                ->whereRaw('DATE_ADD(updated_at, INTERVAL "' . $q->duration . '" MINUTE) <= "' . $nowStr . '"')
                ->update(['is_block' => 1]);
            if (Carbon::parse($quiz->updated_at)->addMinute($q->duration)->format('Y-m-d H:i:s') <= $nowStr) {
                $quiz->is_block = 1;
            }
        }

        return response()->json(['status' => 200, 'data' => $get_quiz, 'timeZone' => config('app.timezone')]);
	}

    public function getQuizAttemptsByUserID($user_id){
		return $this->getQuizAttempts($user_id);
    }

    public function getQuizAttemptsByAuthID(){
      return $this->getQuizAttempts(Auth::user()->id);
    }

    private function performQueueToFinishQuizAttempt($attemptId, $duration) {
        if ($duration != NULL && intval($duration) > 0) {
            $finishQuizAttemptJob = (new MarkQuizAttemptAsCompleteJob($attemptId))
                ->delay(Carbon::now()->addMinutes(intval($duration)));
            dispatch($finishQuizAttemptJob);
        }
    }

    public function getExamQuizInfoById($quiz_id){
	    return QuizAttempt::where('id', $quiz_id)->first();
    }

}
