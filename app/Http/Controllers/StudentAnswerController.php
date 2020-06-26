<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Models\StudentAnswer;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Response;
use Auth;
use Laravel\Passport\Client as PassportClient;
use DB;

class StudentAnswerController extends Controller
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userid = auth('api')->user();
        if (in_array($userid->role, config('constant.roles.student'))) {
            $studentAnswer = new StudentAnswer();
            $studentAnswer->student_id = $userid->id;
            $studentAnswer->answer_id = $request->answer_id;
            $studentAnswer->question_id = $request->question_id;
            $studentAnswer->attempt_id = $request->attempt_id;
            $studentAnswer->save();
            return response()->json(["result" => "Add answer Successfully"])->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = '', $paginate = '')
    {
        if (!is_null($id) && is_numeric($id)) {
            return StudentAnswer::find($id);
        }
        if (!is_null($paginate) && ($id == "all")) {
            return StudentAnswer::paginate($paginate);
        }
        return StudentAnswer::all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $st_answer = StudentAnswer::find($id);
        $userid = auth('api')->user();
        if ($answer->student_id == $userid->id) {
            $st_answer = $st_answer->update([
                'student_id' => $userid->id,
                'answer_id' => $request->answer_id,
                'question_id' => $request->question_id
            ]);
            return Response()->json([$request->all()])->setStatusCode(202);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $answer = StudentAnswer::find($id);
        $userid = auth('api')->user();
        if (($answer->student_id == $userid->id) or (in_array($userid->role, config('constant.dest')))) {
            $answer->delete();
            return response()->json(["result" => "Delete Student Answer Successfully"])->setStatusCode(200);
        } else {
            return response()->json(["result" => "You do not have permission"])->setStatusCode(404);
        }
    }

    public function getAnswerByStudentID(Request $request, $id)
    {
        $answers = StudentAnswer::where('student_id', $id)->with('question')->with('answer')->get();
        return $answers;
    }

    public function getQuizResultByStudentAndQuiz(Request $request, $student_id, $quiz_id, $attempt_id)
    {
        return $this->getQuizResult($student_id, $quiz_id, $attempt_id);

    }

    public function getQuizPartScoreResult(Request $request, $student_id, $quiz_id, $attempt_id)
    {
        $question = DB::table('question')->select('*')->where('quiz_id', $quiz_id)->get();
        $question = json_decode($question, true);
        $FinalScore = 0;
        $Listening_Part1_got = 0;
        $Listening_Part1_out_of = 0;
        $VocGramPart2score_got = 0;
        $VocGramPart2score_out_of = 0;
        $VocReadingPart3score_got = 0;
        $VocReadingPart3score_out_of = 0;
        $VocabularyPart4score_got = 0;
        $VocabularyPart4score_out_of = 0;
        $FormingSenPart5score_got = 0;
        $FormingSenPart5score_out_of = 0;

        foreach ($question as $val) {
            $correct_count = DB::table('answer')->where([['question_id', $val['id']], ['is_correct', 1]])->count();
            $student_answer = DB::table('student_answer')->select('*')->where([['student_id', $student_id], ['quiz_id', $quiz_id], ['attempt_id', $attempt_id], ['question_id', $val['id']]])->get();
            $student_answer = json_decode($student_answer, true);
            $count = 0;
            $answer_data_correct = [];
            $answer_data_wrong = [];
            $total_score = 0;

            if ($val['type'] == 'single_choice'
                || $val['type'] == 'single_choice_as_image'
                || $val['type'] == 'true_false'
                || $val['type'] == 'yes_no'
                || $val['type'] == 'math_set'
                || $val['type'] == 'multiple_choice') {

                if ($val['type']=='single_choice' &&!empty($val['audio_file'])) {
                    $Listening_Part1_out_of += $val['score'];;
                }
                if ($val['type']=='single_choice' &&empty($val['audio_file'])) {
                    $VocGramPart2score_out_of += $val['score'];;
                }

                foreach ($student_answer as $value) {
                    $answer = DB::table('answer')->where('id', $value['answer_content'])->first();
                    if (isset($answer->is_correct)) {
                        if ($answer->is_correct == 1) {
                            $count++;

                        }
                    }

                    $answer_data_correct = [];
                    $answer_data_wrong = [];
                    if ($correct_count == $count && $correct_count != 0) {
                        if ($val['type']=='single_choice' &&!empty($val['audio_file'])) {
                            $Listening_Part1_got += $val['score'];
                        }
                        if ($val['type']=='single_choice' &&empty($val['audio_file'])) {
                            $VocGramPart2score_got += $val['score'];
                        }
                        $answers = DB::table('answer')->where('id', $value['answer_content'])->first();
                        $answer_data_correct = array('answer_id' => $answers->id, 'answer_title' => $answers->title);
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_correct,
                            'score' => $val['score'],
                            'result' => 1
                        );
                        $FinalScore = $FinalScore + $val['score'];

                    } else {
                        $answers = DB::table('answer')->where('id', $value['answer_content'])->first();
                        if (isset($answers)) {
                            $answer_data_wrong = array('answer_id' => $answers->id, 'answer_title' => $answers->title);
                        } else {
                            $answer_data_wrong = array('answer_id' => '', 'answer_title' => 'NOT Attempt');
                        }
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }
                }
            }

            if ($val['type'] == 'drag_drop') {
                $answer_data_correct = [];
                $answer_data_wrong = [];
                foreach ($student_answer as $value) {

                    $answer = DB::table('answer')->select('title', 'id', 'is_correct')->where('id', $value['answer_id'])->first();
                    //dd($val['score']);
                    if (strtolower($answer->title) == strtolower($value['answer_content'])) {
                        $count++;
                        $total_score = $val['score'] / $correct_count;

                        $answer_data_correct = array('answer_id' => '', 'answer_title' => $value['answer_content']);
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_correct,
                            'score' => $total_score,
                            'result' => 1
                        );
                        $FinalScore = $FinalScore + $total_score;
                    } else if (strtolower($answer->title) != strtolower($value['answer_content']) && isset($value['answer_content'])) {

                        $answer_data_wrong = array('answer_id' => '', 'answer_title' => $value['answer_content']);

                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }
                }
            }

            if ($val['type'] == 'fill_in_blank') {
                $answer_data_correct = [];
                $answer_data_wrong = [];
                $VocabularyPart4score_out_of += $val['score'];
                foreach ($student_answer as $value) {
                    $answer = DB::table('answer')->select('title', 'id', 'is_correct')->where('id', $value['answer_id'])->first();

                    if (!empty($answer->title)) {
                        $actual_answer = explode("|", $answer->title);
                        $actual_answer = array_map('strtolower', $actual_answer);
                        $check_actual_answer = preg_replace('/\s+/', ' ', trim(str_replace(array('?', '.'), '', strtolower($value['answer_content']))));
                        if (in_array($check_actual_answer, $actual_answer)) {
                            $count++;
                            $total_score = $val['score'] / $correct_count;
                            $VocabularyPart4score_got += $total_score;
                            $answer_data_correct = array('answer_id' => '', 'answer_title' => $value['answer_content']);
                            $data[] = array(
                                'question_id' => $val['id'],
                                'type' => $val['type'],
                                'question' => $val['title'],
                                'answer' => $answer_data_correct,
                                'score' => $total_score,
                                'result' => 1
                            );
                            $FinalScore = $FinalScore + $total_score;
                        } else {
                            $answer_data_wrong = array('answer_id' => '', 'answer_title' => $value['answer_content']);

                            $data[] = array(
                                'question_id' => $val['id'],
                                'type' => $val['type'],
                                'question' => $val['title'],
                                'answer' => $answer_data_wrong,
                                'score' => 0,
                                'result' => 0
                            );
                        }
                    } else {
                        $answer_data_wrong = array('answer_id' => '', 'answer_title' => $value['answer_content']);

                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }
                }
            }

            if ($val['type'] == 'dropdown') {
//                $VocGramPart2score_out_of += $val['score'];
                $answer_data_correct = [];
                $answer_data_wrong = [];
                foreach ($student_answer as $value) {
                    $answer = DB::table('answer')->select('title', 'id', 'is_correct')->where('id', $value['answer_id'])->first();
                    if ($answer->id == $value['answer_content'] && $answer->is_correct == 1) {
                        $count++;
                        $total_score = $val['score'] / $correct_count;
//                        $VocGramPart2score_got += $val['score'];
                        $answer_data_correct = array('answer_id' => '', 'answer_title' => $answer->title);
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_correct,
                            'score' => $total_score,
                            'result' => 1
                        );
                        $FinalScore = $FinalScore + $total_score;
                    } else if ($answer->id != $value['answer_content'] && isset($value['answer_content'])) {

                        $answer_data_wrong = array('answer_id' => '', 'answer_title' => $answer->title);

                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }
                }

            }

            if ($val['type'] == 'summary' || $val['type'] == 'sorting_horizontal') {
                $correct_cnt = 0;
                $cnt = 0;
                if ($val['type']=='sorting_horizontal'){
                    $FormingSenPart5score_out_of += $val['score'];

                }
                foreach ($student_answer as $value) {
                    $correct = DB::table('answer')->select('id', 'title')->where('question_id', $value['question_id'])->get();
                    $array = json_decode(json_encode($correct), true);
                    $dd = explode(",", $value['answer_content']);

                    $finalArr = array_values(array_filter($dd));
                    $finalArr = array_map('intval', $finalArr);

                    $arr_answer_content = array();
                    if (!empty($finalArr)) {
                        foreach ($array as $k => $vals) {
                            $correct_cnt++;
                            if ($vals['id'] == $finalArr[$k]) {
                                $cnt++;
                            }
                        }

                        foreach ($finalArr as $k => $v) {
                            foreach ($array as $k1 => $v1) {
                                if ($v == $v1['id']) {
                                    array_push($arr_answer_content, $v1['title']);
                                }
                            }
                        }
                    }

                    $answer_content = implode(" ", $arr_answer_content);
                    if (!empty($correct_cnt) && !empty($cnt) && $correct_cnt == $cnt) {
                        $answer_data_correct = array('answer_id' => '', 'answer_title' => $answer_content);
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_correct,
                            'score' => $val['score'],
                            'result' => 1
                        );
                        if ($val['type']=='sorting_horizontal') {
                            $FormingSenPart5score_got += $val['score'];
                        }
                        $FinalScore = $FinalScore + $val['score'];
                    } else {
                        $answer_data_wrong = array('answer_id' => '', 'answer_title' => $answer_content);
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }
                }
            }

            if ($val['type'] == 'single_set' || $val['type'] == 'parent') {
                $answer_data_correct = [];
                $answer_data_wrong = [];
                foreach ($student_answer as $value) {
                    if ($val['parent_id'] != null) {
                        if ($val['type']=='single_set'){
                            $VocReadingPart3score_out_of += $val['score'];
                        }
                        $answer = DB::table('answer')->where('id', $value['answer_content'])->first();
                        if (isset($answer->is_correct)) {
                            if ($answer->is_correct == 1) {
                                $count++;
                            }
                        }
                        if ($correct_count == $count && isset($value['answer_content'])) {
                            if ($val['type']=='single_set') {
                                $VocReadingPart3score_got += $val['score'];
                            }
                            $answers = DB::table('answer')->where('id', $value['answer_content'])->first();
                            $answer_data_correct = array('answer_id' => $answers->id, 'answer_title' => $answers->title);
                            $data[] = array(
                                'question_id' => $val['id'],
                                'type' => $val['type'],
                                'question' => $val['title'],
                                'answer' => $answer_data_correct,
                                'score' => $val['score'],
                                'result' => 1
                            );
                            $FinalScore = $FinalScore + $val['score'];
                        } else {
                            $answers = DB::table('answer')->where('id', $value['answer_content'])->first();
                            if (isset($answers)) {
                                $answer_data_wrong = array('answer_id' => $answers->id, 'answer_title' => $answers->title);
                            } else {
                                $answer_data_wrong = array('answer_id' => '', 'answer_title' => 'NOT Attempt');
                            }
                            $data[] = array(
                                'question_id' => $val['id'],
                                'type' => $val['type'],
                                'question' => $val['title'],
                                'answer' => $answer_data_wrong,
                                'score' => 0,
                                'result' => 0
                            );
                        }
                    }
                }
            }

            if ($val['type'] == 'matching' || $val['type'] == 'matching_as_image' || $val['type'] == 'matching_text_image') {
//                $FormingSenPart5score_out_of += $val['score'];
                if ($val['parent_id'] == null) {
                    $count1 = 0;
                    $matching_count = 0;
                    $child_questions = DB::table('question')->select('*')->where('parent_id', $val['id'])->get();
                    $child_questions = json_decode($child_questions, true);
                    foreach ($child_questions as $value) {
                        $answer1 = DB::table('answer')->select('id')->where('question_id', $value['id'])->first();
                        $answer2 = DB::table('student_answer')->select('answer_content')->where([['question_id', $value['id']], ['attempt_id', $attempt_id]])->first();
                        if (isset($answer1) && isset($answer2)) {
                            $matching_count++;
                            if ($answer1->id == $answer2->answer_content) {
                                $count1++;
                            }
                        }
                    }
                    if ($matching_count == $count1) {
//                        $FormingSenPart5score_got += $val['score'];
                        $answer_data_correct = array('answer_id' => '', 'answer_title' => 'MATCH');
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_correct,
                            'score' => $val['score'],
                            'result' => 1
                        );
                        $FinalScore = $FinalScore + $val['score'];
                    } else {
                        $answer_data_wrong = array('answer_id' => '', 'answer_title' => 'NOT MATCH');
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }
                }
            }
        }

        $data_arr['data'] = array(
            'user_id' => $student_id,
            'quiz_id' => $quiz_id,
            'attempt_id' => $attempt_id,
        );

        $data_arr['score'] = array(
            'Listening_Part1_got' => $Listening_Part1_got,
            'Listening_Part1_out_of' => $Listening_Part1_out_of,
            'VocGramPart2score_got' => $VocGramPart2score_got,
            'VocGramPart2score_out_of' => $VocGramPart2score_out_of,
            'VocReadingPart3score_got' => $VocReadingPart3score_got,
            'VocReadingPart3score_out_of' => $VocReadingPart3score_out_of,
            'VocabularyPart4score_got' => $VocabularyPart4score_got,
            'VocabularyPart4score_out_of' => $VocabularyPart4score_out_of,
            'FormingSenPart5score_got' => $FormingSenPart5score_got,
            'FormingSenPart5score_out_of' => $FormingSenPart5score_out_of,
        );

        return response()->json($data_arr);
    }

    public function getResultReview(Request $request, $student_id, $quiz_id, $attempt_id)
    {
        $question = DB::table('question')->select('*')->where('quiz_id', $quiz_id)->get();
        $question = json_decode($question, true);
        $FinalScore = 0;
        foreach ($question as $val) {

            $correct_count = DB::table('answer')->where([['question_id', $val['id']], ['is_correct', 1]])->count();
            $student_answer = DB::table('student_answer')->select('*')->where([['student_id', $student_id], ['quiz_id', $quiz_id], ['attempt_id', $attempt_id], ['question_id', $val['id']]])->get();
            $student_answer = json_decode($student_answer, true);
            $count = 0;
            $answer_data_correct = [];
            $answer_data_wrong = [];
            $total_score = 0;

            if ($val['type'] == 'single_choice'
                || $val['type'] == 'single_choice_as_image'
                || $val['type'] == 'true_false'
                || $val['type'] == 'yes_no'
                || $val['type'] == 'math_set'
                || $val['type'] == 'multiple_choice') {

                foreach ($student_answer as $value) {
                    $answer = DB::table('answer')->where('id', $value['answer_content'])->first();
                    if (isset($answer->is_correct)) {
                        if ($answer->is_correct == 1) {
                            $count++;
                        }
                    }

                    $answer_data_correct = [];
                    $answer_data_wrong = [];
                    if ($correct_count == $count && $correct_count != 0) {
                        $answers = DB::table('answer')->where('id', $value['answer_content'])->first();
                        $answer_data_correct = array('answer_id' => $answers->id, 'answer_title' => $answers->title);
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_correct,
                            'score' => $val['score'],
                            'result' => 1
                        );
                        $FinalScore = $FinalScore + $val['score'];

                    } else {
                        $answers = DB::table('answer')->where('id', $value['answer_content'])->first();
                        if (isset($answers)) {
                            $answer_data_wrong = array('answer_id' => $answers->id, 'answer_title' => $answers->title);
                        } else {
                            $answer_data_wrong = array('answer_id' => '', 'answer_title' => 'NOT Attempt');
                        }
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }
                }
            }
            if ($val['type'] == 'drag_drop') {
                $answer_data_correct = [];
                $answer_data_wrong = [];
                foreach ($student_answer as $value) {
                    $answer = DB::table('answer')->select('title', 'id', 'is_correct')->where('id', $value['answer_id'])->first();
                    //dd($val['score']);
                    if (strtolower($answer->title) == strtolower($value['answer_content'])) {
                        $count++;
                        $total_score = $val['score'] / $correct_count;
                        $answer_data_correct = array('answer_id' => '', 'answer_title' => $value['answer_content']);
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_correct,
                            'score' => $total_score,
                            'result' => 1
                        );
                        $FinalScore = $FinalScore + $total_score;
                    } else if (strtolower($answer->title) != strtolower($value['answer_content']) && isset($value['answer_content'])) {

                        $answer_data_wrong = array('answer_id' => '', 'answer_title' => $value['answer_content']);

                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }
                }
            }

            if ($val['type'] == 'fill_in_blank') {
                $answer_data_correct = [];
                $answer_data_wrong = [];
                foreach ($student_answer as $value) {
                    $answer = DB::table('answer')->select('title', 'id', 'is_correct')->where('id', $value['answer_id'])->first();

                    if (!empty($answer->title)) {

                        $actual_answer = explode("|", $answer->title);
                        $actual_answer = array_map('strtolower', $actual_answer);
                        $check_actual_answer = preg_replace('/\s+/', ' ', trim(str_replace(array('?', '.'), '', strtolower($value['answer_content']))));
                        if (in_array($check_actual_answer, $actual_answer)) {

                            $count++;
                            $total_score = $val['score'] / $correct_count;
                            $answer_data_correct = array('answer_id' => '', 'answer_title' => $value['answer_content']);
                            $data[] = array(
                                'question_id' => $val['id'],
                                'type' => $val['type'],
                                'question' => $val['title'],
                                'answer' => $answer_data_correct,
                                'score' => $total_score,
                                'result' => 1
                            );
                            $FinalScore = $FinalScore + $total_score;

                        } else {

                            $answer_data_wrong = array('answer_id' => '', 'answer_title' => $value['answer_content']);

                            $data[] = array(
                                'question_id' => $val['id'],
                                'type' => $val['type'],
                                'question' => $val['title'],
                                'answer' => $answer_data_wrong,
                                'score' => 0,
                                'result' => 0
                            );

                        }

                    } else {

                        $answer_data_wrong = array('answer_id' => '', 'answer_title' => $value['answer_content']);

                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );

                    }

                }
            }

            if ($val['type'] == 'dropdown') {
                $answer_data_correct = [];
                $answer_data_wrong = [];
                foreach ($student_answer as $value) {
                    $answer = DB::table('answer')->select('title', 'id', 'is_correct')->where('id', $value['answer_id'])->first();
                    if ($answer->id == $value['answer_content'] && $answer->is_correct == 1) {
                        $count++;
                        $total_score = $val['score'] / $correct_count;
                        $answer_data_correct = array('answer_id' => '', 'answer_title' => $answer->title);
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_correct,
                            'score' => $total_score,
                            'result' => 1
                        );
                        $FinalScore = $FinalScore + $total_score;
                    } else if ($answer->id != $value['answer_content'] && isset($value['answer_content'])) {

                        $answer_data_wrong = array('answer_id' => '', 'answer_title' => $answer->title);

                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }
                }

            }
            if ($val['type'] == 'summary' || $val['type'] == 'sorting_horizontal') {
                $correct_cnt = 0;
                $cnt = 0;
                foreach ($student_answer as $value) {
                    $correct = DB::table('answer')->select('id', 'title')->where('question_id', $value['question_id'])->get();
                    $array = json_decode(json_encode($correct), true);
                    $dd = explode(",", $value['answer_content']);

                    $finalArr = array_values(array_filter($dd));
                    $finalArr = array_map('intval', $finalArr);

                    $arr_answer_content = array();
                    if (!empty($finalArr)) {
                        foreach ($array as $k => $vals) {
                            $correct_cnt++;
                            if ($vals['id'] == $finalArr[$k]) {
                                $cnt++;
                            }
                        }

                        foreach ($finalArr as $k => $v) {
                            foreach ($array as $k1 => $v1) {
                                if ($v == $v1['id']) {
                                    array_push($arr_answer_content, $v1['title']);
                                }
                            }
                        }
                    }

                    $answer_content = implode(" ", $arr_answer_content);
                    if (!empty($correct_cnt) && !empty($cnt) && $correct_cnt == $cnt) {
                        $answer_data_correct = array('answer_id' => '', 'answer_title' => $answer_content);
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_correct,
                            'score' => $val['score'],
                            'result' => 1
                        );
                        $FinalScore = $FinalScore + $val['score'];
                    } else {
                        $answer_data_wrong = array('answer_id' => '', 'answer_title' => $answer_content);
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }

                }
            }
            if ($val['type'] == 'single_set' || $val['type'] == 'parent') {
                $answer_data_correct = [];
                $answer_data_wrong = [];
                foreach ($student_answer as $value) {
                    if ($val['parent_id'] != null) {
                        $answer = DB::table('answer')->where('id', $value['answer_content'])->first();
                        if (isset($answer->is_correct)) {
                            if ($answer->is_correct == 1) {
                                $count++;
                            }
                        }
                        if ($correct_count == $count && isset($value['answer_content'])) {
                            $answers = DB::table('answer')->where('id', $value['answer_content'])->first();
                            $answer_data_correct = array('answer_id' => $answers->id, 'answer_title' => $answers->title);
                            $data[] = array(
                                'question_id' => $val['id'],
                                'type' => $val['type'],
                                'question' => $val['title'],
                                'answer' => $answer_data_correct,
                                'score' => $val['score'],
                                'result' => 1
                            );
                            $FinalScore = $FinalScore + $val['score'];
                        } else {
                            $answers = DB::table('answer')->where('id', $value['answer_content'])->first();
                            if (isset($answers)) {
                                $answer_data_wrong = array('answer_id' => $answers->id, 'answer_title' => $answers->title);
                            } else {
                                $answer_data_wrong = array('answer_id' => '', 'answer_title' => 'NOT Attempt');
                            }
                            $data[] = array(
                                'question_id' => $val['id'],
                                'type' => $val['type'],
                                'question' => $val['title'],
                                'answer' => $answer_data_wrong,
                                'score' => 0,
                                'result' => 0
                            );
                        }
                    }
                }
            }
            if ($val['type'] == 'matching' || $val['type'] == 'matching_as_image' || $val['type'] == 'matching_text_image') {
                if ($val['parent_id'] == null) {
                    $count1 = 0;
                    $matching_count = 0;
                    $child_questions = DB::table('question')->select('*')->where('parent_id', $val['id'])->get();
                    $child_questions = json_decode($child_questions, true);
                    foreach ($child_questions as $value) {
                        $answer1 = DB::table('answer')->select('id')->where('question_id', $value['id'])->first();
                        $answer2 = DB::table('student_answer')->select('answer_content')->where([['question_id', $value['id']], ['attempt_id', $attempt_id]])->first();
                        if (isset($answer1) && isset($answer2)) {
                            $matching_count++;
                            if ($answer1->id == $answer2->answer_content) {
                                $count1++;
                            }
                        }
                    }
                    if ($matching_count == $count1) {
                        $answer_data_correct = array('answer_id' => '', 'answer_title' => 'MATCH');
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_correct,
                            'score' => $val['score'],
                            'result' => 1
                        );
                        $FinalScore = $FinalScore + $val['score'];
                    } else {
                        $answer_data_wrong = array('answer_id' => '', 'answer_title' => 'NOT MATCH');
                        $data[] = array(
                            'question_id' => $val['id'],
                            'type' => $val['type'],
                            'question' => $val['title'],
                            'answer' => $answer_data_wrong,
                            'score' => 0,
                            'result' => 0
                        );
                    }
                }
            }

        }
        QuizAttempt::where('quiz_id', $quiz_id)->where('user_id', $student_id)->where('id', $attempt_id)->update(['total_score' => $FinalScore]);

        return response()->json($data);
    }

    public function getQuizResultByStudent($quiz_id, $attempt_id)
    {
        return $this->getQuizResult(auth('api')->user()->id, $quiz_id, $attempt_id);
    }
}
