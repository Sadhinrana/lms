<?php

namespace App\Http\Controllers;

use App\Models\LiveLesson;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LiveLessonController extends Controller
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
            $sessions = auth()->user()->liveLesson()->with('studentGroup')
                ->where(function ($q) use ($request) {
                    if ($request->keyword) {
                        $q->where('week_day', 'like', "%$request->keyword%")
                            ->orWhere('time', 'like', "%$request->keyword%")
                            ->orWhere('study_mode', 'like', "%$request->keyword%")
                            ->orWhere('bbb_room_link', 'like', "%$request->keyword%")
                            ->orWhereHas('studentGroup', function ($query) use ($request) {
                                $query->where('title', 'like', "%$request->keyword%");
                            });
                    }
                })
                ->orderBy('id', 'desc')->paginate(10);

            return response()->json(['status' => 200, 'data' => $sessions]);
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
            'week_day' => 'required|array',
            'students' => 'required|array',
            'week_day.*' => 'required|integer|between:0,6',
            'students.*' => 'required|integer|exists:users,id',
            'time' => 'required|date_format:H:i',
            'student_group_id' => 'nullable|integer',
            'study_mode' => 'required|string|in:One to One,Group Two,Group Three',
            'bbb_room_link' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 200, 'errors' => $validator->getMessageBag()->toarray()]);
        }

        try {
            $data = $request->except('_token', 'week_day', 'students');
            $data['week_day'] = json_encode($request->week_day);
            $data['user_id'] = auth()->id();
            $data['company_id'] = auth()->user()->company_id;

            $liveSessions = LiveLesson::create($data);

            $liveSessions->students()->attach($request->students);

            return response()->json(['status' => 200, 'msg' => 'Session created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LiveLesson  $liveLesson
     * @return \Illuminate\Http\Response
     */
    public function show(LiveLesson $liveLesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        try {
            return response()->json(['status' => 200, 'data' => LiveLesson::with('students')->findOrFail($id)]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            'week_day' => 'required|array',
            'students' => 'required|array',
            'week_day.*' => 'required|integer|between:0,6',
            'students.*' => 'required|integer|exists:users,id',
            'time' => 'required|date_format:H:i',
            'student_group_id' => 'nullable|integer',
            'study_mode' => 'required|string|in:One to One,Group Two,Group Three',
            'bbb_room_link' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 200, 'errors' => $validator->getMessageBag()->toarray()]);
        }

        try {
            $data = $request->except('_token', 'id', '_method', 'week_day', 'week_day_string', 'students');
            $data['week_day'] = json_encode($request->week_day);

            $liveSessions = LiveLesson::find($id);
            LiveLesson::where('id', $id)->update($data);
            $liveSessions->students()->sync($request->students);

            return response()->json(['status' => 200, 'msg' => 'Session updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            LiveLesson::findOrFail($id)->delete();

            return response()->json(['status' => 200, 'msg' => 'Session deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function liveLessonsByTeacher()
    {
        try {
            $events = [];
            $request = request();
            $liveLessons = $request->query('instructor_id') ? User::find($request->instructor_id)->liveLesson : auth()->user()->liveLesson;

            foreach ($liveLessons as $value) {
                $period = CarbonPeriod::create(Carbon::parse($request->start), Carbon::parse($request->end));

                foreach($period as $date)
                {
                    if (in_array($date->dayOfWeek, $value->week_day)) {
                        $events[] = array(
                            'id' => $date->format('Y-m-d') . ' ' . $value->time,
                            'title' => $value->study_mode,
                            'start' => $date->format('Y-m-d') . ' ' . $value->time,
                            'url' => $value->bbb_room_link,
                            "color" => date('Y-m-d', strtotime($request->query('today'))) == $date->format('Y-m-d') && Carbon::parse($request->query('today'))->gt(Carbon::parse($date->format('Y-m-d') . ' ' . $value->time)) ? "orange" : "grey",
                            "textColor" => "#ffffff",
                        );
                    }
                }
            }

            return response()->json(['status' => 200, 'data' => $events]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function liveLessonsByStudent()
    {
        try {
            $request = request();
            $events = [];

            foreach (auth()->user()->liveSessions as $value) {
                $period = CarbonPeriod::create(Carbon::parse($request->start), Carbon::parse($request->end));

                foreach($period as $date)
                {
                    if (in_array($date->dayOfWeek, $value->week_day)) {
                        $events[] = array(
                            'id' => $date->format('Y-m-d') . ' ' . $value->time,
                            'title' => $value->study_mode,
                            'start' => $date->format('Y-m-d') . ' ' . $value->time,
                            'url' => $value->bbb_room_link,
                            "color" => "#3788d8",
                            "textColor" => "#ffffff"
                        );
                    }
                }
            }

            return response()->json(['status' => 200, 'data' => $events]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }
}
