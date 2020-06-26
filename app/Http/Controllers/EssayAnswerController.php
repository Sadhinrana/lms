<?php

namespace App\Http\Controllers;

use App\Models\EssayAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EssayAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|integer|exists:essay_answers,id',
            'essay_id' => 'required|integer|exists:essays,id',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 200, 'errors' => $validator->getMessageBag()->toarray()]);
        }

        try {
            if ($request->id) {
                $data = $request->except('id', '_token');
                $data['user_id'] = auth()->id();
                EssayAnswer::where('id', $request->id)->update($data);
            } else {
                $data = $request->except('_token');
                $data['user_id'] = auth()->id();
                EssayAnswer::create($data);
            }

            return response()->json(['status' => 200, 'msg' => 'The Essay was submitted. Your teacher will review it soon. You will be notified after your essay is reviewed.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EssayAnswer  $essayAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(EssayAnswer $essayAnswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EssayAnswer  $essayAnswer
     * @return \Illuminate\Http\Response
     */
    public function edit(EssayAnswer $essayAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        try {
            $essayAnswer = EssayAnswer::findOrFail($id);
            $essayAnswer->is_closed = !$essayAnswer->is_closed;
            $essayAnswer->save();

            return response()->json(['status' => 200, 'msg' => $essayAnswer->is_closed ? 'Essay answer closed successfully.' : 'Essay answer recovered successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EssayAnswer  $essayAnswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(EssayAnswer $essayAnswer)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function answers()
    {
        try {
            $request = request();

            if ($request->query('page')) {
                $essayAnswers = auth()->user()->role == 'student' ?
                    auth()->user()->answers()
                        ->with('essay', 'user', 'reviews.user')
                        ->where(function ($q) use($request) {
                            if ($request->keyword) {
                                $q->whereHas('essay', function ($q) use($request) {
                                    $q->where('title', 'like', "%$request->keyword%")
                                        ->orWhere('question', 'like', "%$request->keyword%")
                                        ->orWhere('type', 'like', "%$request->keyword%");
                                });
                            }
                        })
                        ->orderBy('id', 'desc')
                        ->paginate(10) :
                    EssayAnswer::with('essay', 'user', 'reviews.user')
                        ->where(function ($q) {
                            if (auth()->user()->role == 'head_teacher') {
                                $q->whereIn('user_id', auth()->user()->students->pluck('id'));
                            } else {
                                $q->whereIn('user_id', User::where('company_id', auth()->user()->company_id)->pluck('id'));
                            }
                        })
                        ->where(function ($q) use($request) {
                            if ($request->keyword) {
                                $q->whereHas('essay', function ($q) use($request) {
                                    $q->where('title', 'like', "%$request->keyword%")
                                        ->orWhere('question', 'like', "%$request->keyword%")
                                        ->orWhere('type', 'like', "%$request->keyword%");
                                })->orWhereHas('user', function ($q) use($request) {
                                    $q->where(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$request->keyword%")
                                        ->orWhere('email', 'like', "%$request->keyword%");
                                });
                            }
                        })
                        ->where('is_submitted', 1)
                        ->orderBy('id', 'desc')
                        ->paginate(10);
            } else {
                $essayAnswers = auth()->user()->answers()->with('essay', 'user', 'reviews.user')->get();
            }

            return response()->json(['status' => 200, 'data' => $essayAnswers]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function usersClosedAnswers(Request  $request, $id)
    {
        try {
            $essayAnswers = EssayAnswer::with('essay', 'user', 'reviews.user')
                ->where('user_id', $id)
                ->where('is_closed', 1)
                ->where(function ($q) use($request) {
                    if ($request->keyword) {
                        $q->whereHas('essay', function ($q) use($request) {
                            $q->where('title', 'like', "%$request->keyword%")
                                ->orWhere('question', 'like', "%$request->keyword%")
                                ->orWhere('type', 'like', "%$request->keyword%");
                        });
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate(10);

            return response()->json(['status' => 200, 'data' => $essayAnswers]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function answersByTeacher()
    {
        try {
            $essayAnswers = EssayAnswer::where(function ($q) {
                    if (auth()->user()->role == 'head_teacher') {
                        $q->whereIn('user_id', auth()->user()->students->pluck('id'));
                    } else {
                        $q->whereIn('user_id', User::where('company_id', auth()->user()->company_id)->pluck('id'));
                    }
                })
                ->whereHas('essay', function ($q) {
                    if (auth()->user()->role == 'company_head') {
                        $q->where('type', 'midterm_end_course');
                    } else {
                        $q->where('type', 'unit');
                    }
                })
                ->doesntHave('reviews')
                ->count();

            return response()->json(['status' => 200, 'data' => $essayAnswers]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }
}
