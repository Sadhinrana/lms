<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Models\StudentGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $input = $request->input();
            $input['month'] = isset($input['month']) ? $input['month'] : date('m');
            $input['year'] = isset($input['year']) ? $input['year'] : date('Y');

            if (isset($input['start']) && isset($input['end'])) {
                $sql = Attendance::where(['user_id' => $input['user_id'] ?? Auth::user()->id, 'instructor_id' => $input['instructor_id']])->whereDate('date', '>=', $input['start'])->whereDate('date', '<=', $input['end']);
            } else {
                $sql = Attendance::where(['user_id' => $input['user_id'] ?? Auth::user()->id, 'instructor_id' => $input['instructor_id']])->whereMonth('date', $input['month'])->whereYear('date', $input['year']);
            }
            $totalAttendance = Attendance::where(['user_id' => $input['user_id'] ?? Auth::user()->id, 'instructor_id' => $input['instructor_id']])->whereMonth('date', $input['month'])->whereYear('date', $input['year'])->count();
            $data = $sql->get()->toArray();
            $data = array_column($data, 'event');

            return response()->json(['status' => 200, 'data' => $data, 'attendancesRequestedMonth' => $totalAttendance]);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'user_id' => 'required|integer',
            'event' => 'required|string'
        ]);

        try {
            $check = Attendance::where(['user_id' => Auth::user()->id, 'instructor_id' => $request->instructor_id])->whereDate('date', $request->date)->get()->first();
            if ($check == null) {
                $request->user_id = Auth::user()->id;
                Attendance::create($request->except('_token'));
            }
            return response()->json(['status' => 200, 'msg' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentAttendanceReport(Request $request)
    {
        try {
            $date = Carbon::parse($request->date);

            $students = User::with(['attendances' => function ($q) use ($request, $date) {
                $q->whereMonth('date', $date->format('m'))->whereYear('date', $date->format('Y'));
                if ($request->instructor_id) {
                    $q->where('instructor_id', $request->instructor_id);
                }
            }])->leftJoin('student_teacher', 'users.id', '=', 'student_teacher.student_id')
                ->select('users.*', 'student_teacher.*')
                ->where(function ($q) use ($request) {
                if ($request->instructor_id) {
                    $q->where('student_teacher.teacher_id', $request->instructor_id);
                } elseif ($request->company_id) {
                    $q->where('users.company_id', $request->company_id);
                }
            })->where('users.role', 'student')
                ->orderBy('student_teacher.student_group_id', 'asc')
                ->paginate(30);

            foreach ($students as $student) {
                $student->student_group = StudentGroup::find($student->student_group_id);
                $student->instructor = User::find($student->teacher_id);
            }

            return response()->json(['status' => 200, 'data' => $students]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }
}
