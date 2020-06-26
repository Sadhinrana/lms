<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Essay;
use App\Models\EssayAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EssayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $request = request();
            $essays = Essay::with('course')
                ->where(function ($q) use ($request) {
                    if ($request->keyword) {
                        $q->where('title', 'like', "%$request->keyword%")
                            ->orWhere('question', 'like', "%$request->keyword%")
                            ->orWhere('type', 'like', "%$request->keyword%")
                            ->orWhereHas('course', function ($query) use ($request) {
                                $query->where('title', 'like', "%$request->keyword%");
                            });
                    }
                })
                ->orderBy('id', 'desc')->paginate(10);
            $courses = Course::all();

            return response()->json(['status' => 200, 'data' => ['essays' => $essays, 'courses' => $courses]]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
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
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'type' => 'required|string|in:unit,midterm_end_course',
            'course_id' => 'required|integer|exists:course,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 200, 'errors' => $validator->getMessageBag()->toarray()]);
        }

        try {
            Essay::create($request->except('_token', 'id'));

            return response()->json(['status' => 200, 'msg' => 'Essay created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        return response()->json(['status' => 200, 'data' => Essay::with(['answers' => function($q) {
            $q->where(['user_id' => auth()->id(), 'is_submitted' => 0])->orderBy('id', 'desc')->first();
        }])->findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'type' => 'required|string|in:unit,midterm_end_course',
            'course_id' => 'required|integer|exists:course,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 200, 'errors' => $validator->getMessageBag()->toarray()]);
        }

        try {
            Essay::findOrFail($id)->update($request->only('title', 'question', 'type', 'course_id'));

            return response()->json(['status' => 200, 'msg' => 'Essay updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Essay::findOrFail($id)->delete();

            return response()->json(['status' => 200, 'msg' => 'Essay deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }
}
