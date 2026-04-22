<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Department;
use App\Models\ActivityLog;
use App\Models\Shift;
use App\Models\Attendance;
use App\Models\PerformanceReport;

class StaffController extends Controller
{
    /**
     * Staff Listing with Search + Pagination
     */
    public function index(Request $request)
    {
        $query = Staff::with(['role','department']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name','like',"%$search%")
                  ->orWhere('email','like',"%$search%")
                  ->orWhere('phone','like',"%$search%");
            });
        }

        $staff = $query->paginate(10);
        return view('staff.index', compact('staff'));
    }

    /**
     * Show Create Form
     */
    public function create()
    {
        $roles = Role::all();
        $departments = Department::all();
        return view('staff.create', compact('roles','departments'));
    }

    /**
     * Store New Staff
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:staff,email',
            'phone'         => 'nullable|string|max:50',
            'role_id'       => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'status'        => 'required|in:active,inactive',
        ]);

        $staff = Staff::create($request->all());

        ActivityLog::create([
            'staff_id'    => auth()->id(),
            'action'      => 'Created Staff',
            'description' => "Added {$staff->name} to staff records"
        ]);

        return redirect()->route('staff.index')->with('success','Staff member added successfully.');
    }

    /**
     * Show Edit Form
     */
    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        $roles = Role::all();
        $departments = Department::all();
        return view('staff.edit', compact('staff','roles','departments'));
    }

    /**
     * Update Staff
     */
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => "required|email|unique:staff,email,{$staff->id}",
            'phone'         => 'nullable|string|max:50',
            'role_id'       => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'status'        => 'required|in:active,inactive',
        ]);

        $staff->update($request->all());

        ActivityLog::create([
            'staff_id'    => auth()->id(),
            'action'      => 'Updated Staff',
            'description' => "Updated {$staff->name}'s record"
        ]);

        return redirect()->route('staff.index')->with('success','Staff member updated successfully.');
    }

    /**
     * Soft Delete (Deactivate Staff)
     */
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        ActivityLog::create([
            'staff_id'    => auth()->id(),
            'action'      => 'Deactivated Staff',
            'description' => "Deactivated {$staff->name}"
        ]);

        return redirect()->route('staff.index')->with('success','Staff member deactivated.');
    }

    /**
     * View Trashed Staff
     */
    public function trashed()
    {
        $staff = Staff::onlyTrashed()->with(['role','department'])->paginate(10);
        return view('staff.trashed', compact('staff'));
    }

    /**
     * Restore Soft Deleted Staff
     */
    public function restore($id)
    {
        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->restore();

        ActivityLog::create([
            'staff_id'    => auth()->id(),
            'action'      => 'Restored Staff',
            'description' => "Restored {$staff->name}"
        ]);

        return redirect()->route('staff.trashed')->with('success','Staff member restored.');
    }

    /**
     * Force Delete Staff (Permanent)
     */
    public function forceDelete($id)
    {
        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->forceDelete();

        ActivityLog::create([
            'staff_id'    => auth()->id(),
            'action'      => 'Force Deleted Staff',
            'description' => "Permanently deleted {$staff->name}"
        ]);

        return redirect()->route('staff.trashed')->with('success','Staff member permanently deleted.');
    }

    /**
     * Staff Profile
     */
    public function show($id)
    {
        $staff = Staff::with(['role','department','activityLogs'])->findOrFail($id);
        return view('staff.show', compact('staff'));
    }

        /**
     * Display a listing of departments.
     */
    public function departments()
    {
        $departments = Department::all();
        return view('staff.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function createDepartment()
    {
        return view('staff.departments.create');
    }

    /**
     * Store a newly created department in storage.
     */
    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string|max:500',
        ]);

        Department::create($validated);

        return redirect()->route('staff.departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Show the form for editing the specified department.
     */
    public function editDepartment(Department $department)
    {
        return view('staff.departments.edit', compact('department'));
    }

    /**
     * Update the specified department in storage.
     */
    public function updateDepartment(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string|max:500',
        ]);

        $department->update($validated);

        return redirect()->route('staff.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroyDepartment(Department $department)
    {
        $department->delete();

        return redirect()->route('staff.departments.index')
            ->with('success', 'Department deleted successfully.');
    }


    

    /**
     * Activity Logs
     *
     * Show audit trail of staff actions (create, update, delete, restore).
     */
    public function logs()
    {
        // Load logs with related staff user
        $logs = ActivityLog::with('staff')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('staff.logs', compact('logs'));
    }

    /**
     * Store a new activity log entry.
     *
     * Call this whenever a staff action occurs.
     */
    public function storeLog($staffId, $action)
    {
        ActivityLog::create([
            'staff_id'   => $staffId,
            'action'     => $action,        // e.g. "created", "updated", "deleted"
            'ip_address' => request()->ip(), // capture client IP
            'created_at' => now(),
        ]);
    }

    /**
     * Delete an activity log entry (optional).
     */
    public function destroyLog($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return redirect()->route('staff.logs')
            ->with('success', 'Log entry deleted successfully.');
    }

        /**
     * Attendance dashboard: list all records.
     */
    public function attendanceIndex()
    {
        $attendance = Attendance::with(['staff', 'shift'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('staff.attendance.index', compact('attendance'));
    }

    /**
     * Show form to create a new attendance record.
     */
    public function createAttendance()
    {
        $staff = Staff::orderBy('name')->get();
        $shifts = Shift::orderBy('start_time')->get();
        return view('staff.attendance.create', compact('staff', 'shifts'));
    }

    /**
     * Store a new attendance record.
     */
    public function storeAttendance(Request $request)
    {
        $request->validate([
            'staff_id'   => 'required|exists:staff,id',
            'shift_id'   => 'nullable|exists:shifts,id',
            'date'       => 'required|date',
            'clock_in'   => 'nullable|date_format:H:i',
            'clock_out'  => 'nullable|date_format:H:i|after:clock_in',
        ]);

        Attendance::create([
            'staff_id'   => $request->staff_id,
            'shift_id'   => $request->shift_id,
            'date'       => $request->date,
            'clock_in'   => $request->clock_in,
            'clock_out'  => $request->clock_out,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('staff.attendance.index')
            ->with('success', 'Attendance record created successfully.');
    }

    /**
     * List all shifts.
     */
    public function shiftIndex()
    {
        $shifts = Shift::orderBy('start_time')->paginate(20);
        return view('staff.attendance.shifts.index', compact('shifts'));
    }

    /**
     * Show form to create a new shift.
     */
    public function createShift()
    {
        return view('staff.attendance.create-shift');
    }

    /**
     * Store a new shift.
     */
    public function storeShift(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        Shift::create($request->only('name', 'start_time', 'end_time'));

        return redirect()->route('staff.attendance.shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    /**
     * Edit an existing shift.
     */
    public function editShift(Shift $shift)
    {
        return view('staff.attendance.shifts.edit', compact('shift'));
    }

    /**
     * Update a shift.
     */
    public function updateShift(Request $request, Shift $shift)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        $shift->update($request->only('name', 'start_time', 'end_time'));

        return redirect()->route('staff.attendance.shifts.index')
            ->with('success', 'Shift updated successfully.');
    }

    /**
     * Delete a shift.
     */
    public function destroyShift(Shift $shift)
    {
        $shift->delete();

        return redirect()->route('staff.attendance.shifts.index')
            ->with('success', 'Shift deleted successfully.');
    }

    /**
     * Clock In staff.
     */
    public function clockIn($staffId)
    {
        Attendance::create([
            'staff_id'   => $staffId,
            'date'       => now()->toDateString(),
            'clock_in'   => now()->format('H:i:s'),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Clock‑in recorded.');
    }

    /**
     * Clock Out staff.
     */
    public function clockOut($staffId)
    {
        $attendance = Attendance::where('staff_id', $staffId)
            ->whereDate('date', now()->toDateString())
            ->latest()
            ->first();

        if ($attendance && !$attendance->clock_out) {
            $attendance->update([
                'clock_out'  => now()->format('H:i:s'),
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', 'Clock‑out recorded.');
    }

    /**
     * Attendance Reports.
     */
    public function attendanceReports()
    {
        $reports = Attendance::with(['staff', 'shift'])
            ->get()
            ->groupBy('staff_id')
            ->map(function ($records) {
                $staff = $records->first()->staff;
                $daysPresent = $records->count();
                $totalHours = $records->reduce(function ($carry, $record) {
                    if ($record->clock_in && $record->clock_out) {
                        $in  = strtotime($record->clock_in);
                        $out = strtotime($record->clock_out);
                        $carry += ($out - $in) / 3600;
                    }
                    return $carry;
                }, 0);

                return [
                    'staff'       => $staff,
                    'days_present'=> $daysPresent,
                    'total_hours' => $totalHours,
                ];
            });

        return view('staff.attendance.reports', compact('reports'));
    }

        /**
     * Display a listing of performance reports.
     */
    public function performanceReportsIndex()
    {
        // Fetch reports with staff relationship, paginated
        $reports = PerformanceReport::with('staff')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Summary widget: total hours per staff for current month
        $summary = PerformanceReport::whereMonth('period_start', now()->month)
            ->whereYear('period_start', now()->year)
            ->with('staff')
            ->get()
            ->groupBy(fn($report) => $report->staff->name)
            ->map(fn($reports) => $reports->sum('total_hours'));

        return view('staff.reports.index', compact('reports', 'summary'));
    }

    /**
     * Show a single performance report.
     */
    public function showPerformanceReport(PerformanceReport $report)
    {
        // Ensure staff relationship is loaded
        $report->load('staff');

        return view('staff.reports.show', compact('report'));
    }
}
