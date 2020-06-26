<?php

namespace App\Http\Controllers;

use App\Models\EssayReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EssayReviewController extends Controller
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
            'review' => 'required|string',
            'is_student' => 'required|boolean',
            'essay_answer_id' => 'required|integer|exists:essay_answers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 200, 'errors' => $validator->getMessageBag()->toarray()]);
        }

        try {
            $data = $request->except('_token');
            $data['user_id'] = auth()->id();
            $review = EssayReview::create($data);
            $review = EssayReview::with('user')->find($review->id);

            return response()->json(['status' => 200, 'msg' => auth()->user()->role == 'student' ? 'Thanks for the replay. Your teacher will review and let you know.' : 'Thanks for the feedback. Please ask your student to see your reviews.', 'data' => $review]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EssayReview  $essayReview
     * @return \Illuminate\Http\Response
     */
    public function show(EssayReview $essayReview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EssayReview  $essayReview
     * @return \Illuminate\Http\Response
     */
    public function edit(EssayReview $essayReview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:essay_reviews,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 200, 'errors' => $validator->getMessageBag()->toarray()]);
        }

        try {
            EssayReview::whereHas('user', function ($q) {
                if (auth()->user()->role != 'student') {
                    $q->where('role', 'student');
                }
            })
                ->whereIn('id', $request->ids)
                ->where('user_id', '!=', auth()->id())
                ->update(['seen_at' => now()]);

            return response()->json(['status' => 200, 'msg' => 'Essay seen successfully.', 'data' => EssayReview::with('user')->find($request->ids)]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EssayReview  $essayReview
     * @return \Illuminate\Http\Response
     */
    public function destroy(EssayReview $essayReview)
    {
        //
    }
}
