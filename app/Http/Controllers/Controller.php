<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use App\Models\StudentAnswer;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Response;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getQuizResult($student_id, $quiz_id, $attempt_id){
        $answers = StudentAnswer::where('student_id',$student_id)
                    ->where('quiz_id',$quiz_id)
                    ->where('attempt_id',$attempt_id)
                    ->with('question.child_questions')
                    ->with('answer')
                    ->with('attempt')
                    ->get()->groupBy('question_id');

        foreach ($answers as $group_answer) {
          $question_id = $group_answer[0]->question->id;

          //----If this question has child questions:
          if (sizeof($group_answer[0]->question->child_questions) > 0
              && ($group_answer[0]->question->type != 'matching'
                  || $group_answer[0]->question->type != 'matching_as_image')){

            $user_answers = StudentAnswer::where('student_id',$student_id)
                        ->where('quiz_id',$quiz_id)
                        //->where('attempt_id',$attempt_id)
                        ->with('question')
                        ->with('answer')
                        ->whereHas('question', function($q) use ($group_answer){
                            $q->where('parent_id', $group_answer[0]->question->id);
                        })
                        ->get()->groupBy('question_id');
            $corrected = 0;

            foreach($user_answers as $child_question){

              switch($child_question[0]->question->type){
                case 'single_choice':
                case 'multiple_choice':
                case 'yes_no':
                case 'true_false':
                  $system_correct_answers = Answer::where('question_id',$child_question[0]->question->id)
                      ->where('is_correct',1)->get()->count();
                  $user_correct_answers = $child_question->count();

                  //-------Multiple Type Checking----------//
                  //If number of system_correct_answer != number of user_answers, then this answers for this question is wrong :

                  if($user_correct_answers != $system_correct_answers){
                    $child_question['result'] = 0;
                  }else {
                    $is_correct = 1;
                    //If $user_correct_answers == $system_correct_answers, we have to check if user's answers is all corrected :
                    foreach($child_question as $answer){
                        if($answer->answer != null){
                            if(!$answer->answer->is_correct)
                                $is_correct = 0;
                        }
                    }
                    $child_question['result'] = $is_correct;
                  }
                  break;

                case "sorting_horizontal":
                  $explode_array = explode(",",$child_question[0]->answer_content);
                  array_pop($explode_array);
                  $child_question['result'] = 0;
                  if($this->checkConsec($explode_array)){
                    $child_question['result'] = 1;
                  }
                  break;

                case "fill_in_blank":
                case "drag_drop":
                case "dropdown":
                  $corrected = 0;
                  foreach($child_question as $answer){
                      if($answer->answer !== null){
                          if(strcasecmp($answer->answer_content, $answer->answer->title) === 0){
                              $corrected++;
                          }
                      }
                  }
                  $child_question['result'] = $corrected/sizeof($group_answer);
                  break;

                case "matching":
                case "matching_as_image":
                  $user_answers = StudentAnswer::where('student_id',$student_id)
                              ->where('quiz_id',$quiz_id)
                              ->with('question')
                              ->with('answer')
                              ->whereHas('question', function($q) use ($child_question){
                                  $q->where('parent_id', $child_question[0]->question->id);
                              })
                              ->get();
                  $corrected = 0;
                  foreach($user_answers as $child){
                    if($child->question->id === $child->answer->question_id)
                      $corrected++;
                  }
                  //return Question::find($group_answer[0]->question->id)->child_questions->count();
                  $child_question['result'] = $corrected/Question::find($group_answer[0]->question->id)->child_questions->count();
                  break;
              }

            }
          }else{
            switch($group_answer[0]->question->type){
              case 'single_choice':
              case 'multiple_choice':
              case 'yes_no':
              case 'true_false':
                $system_correct_answers = Answer::where('question_id',$question_id)->where('is_correct',1)->get()->count();
                $user_correct_answers = $group_answer->count();

                //-------Multiple Type Checking----------//
                //If number of system_correct_answer != number of user_answers, then this answers for this question is wrong :
                if($user_correct_answers != $system_correct_answers){
                  $group_answer['result'] = 0;
                }else {
                  $is_correct = 1;
                  //If $user_correct_answers == $system_correct_answers, we have to check if user's answers is all corrected :
                  foreach($group_answer as $answer){
                    if(!isset($answer->answer->is_correct))
					{$is_correct = 0;}
                  }
                  $group_answer['result'] = $is_correct;
                }
                break;

              case "sorting_horizontal":
              case "summary":
                $explode_array = explode(",",$group_answer[0]->answer_content);
                array_pop($explode_array);
                $group_answer['result'] = 0;
                if($this->checkConsec($explode_array)){
                  $group_answer['result'] = 1;
                }
                break;

              case "fill_in_blank":
              case "drag_drop":
                $corrected = 0;
                foreach($group_answer as $answer){
                  if(strcasecmp($answer->answer_content, $answer->answer->title) === 0){
                      $corrected++;
                  }
                }
                $group_answer['result'] = $corrected/sizeof($group_answer);
                break;
              case "dropdown":
                $corrected = 0;
                foreach($group_answer as $answer){
                  $correct_answer = substr($answer->answer->title, 1, strpos($answer->answer->title,'|')-1);
                  if(strcasecmp($answer->answer_content, $correct_answer) === 0){
                      $corrected++;
                  }
                }
                $group_answer['result'] = $corrected/sizeof($group_answer);
                break;

              case "matching":
              case "matching_as_image":
              if($group_answer[0]->question->parent_id == null){
                $user_answers = StudentAnswer::where('student_id',$student_id)
                            ->where('quiz_id',$quiz_id)
                            ->with('question')
                            ->with('answer')
                            ->whereHas('question', function($q) use ($group_answer){
                                $q->where('parent_id', $group_answer[0]->question->id);
                            })
                            ->get();
                $corrected = 0;
                foreach($user_answers as $child_question){

                  if($child_question->question->id === $child_question->answer->question_id)
                    $corrected++;
                }
                //print_r(Question::find($group_answer[0]->question->id)->child_questions->count());
                $group_answer['result'] = $corrected/Question::find($group_answer[0]->question->id)->child_questions->count();
                break;
              }
            }
          }
        }
        return array_values($answers->toArray());
      }

      protected function checkConsec($d) {
        for($i=0;$i<count($d);$i++) {
            if(isset($d[$i+1]) && $d[$i] > $d[$i+1]) {
                return false;
            }
        }
        return true;
    }
}
