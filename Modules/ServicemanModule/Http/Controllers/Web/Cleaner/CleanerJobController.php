<?php

namespace Modules\ServicemanModule\Http\Controllers\Web\Cleaner;

use App\Models\Attendance;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\ServicemanModule\Models\JobChecklist;
use Modules\ServicemanModule\Models\JobChecklistItem;
use Modules\ServicemanModule\Models\JobPhoto;

class CleanerJobController extends Controller
{
    /**
     * Show today's jobs (tasks) assigned to the authenticated cleaner.
     */
    public function index()
    {
        $userId = Auth::id();
        $today  = now()->toDateString();

        $jobs = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $userId)
            ->whereDate('tasks.due_date', $today)
            ->select('tasks.*')
            ->with(['project:id,project_name', 'users:id,name,image'])
            ->orderBy('tasks.due_date')
            ->get();

        return view('servicemanmodule::cleaner.jobs.index', compact('jobs'));
    }

    /**
     * Show a single job card with GPS check-in, photo upload, and checklist.
     */
    public function show(int $taskId)
    {
        $userId = Auth::id();

        $job = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $userId)
            ->where('tasks.id', $taskId)
            ->select('tasks.*')
            ->with(['project:id,project_name'])
            ->firstOrFail();

        $photos     = JobPhoto::where('task_id', $taskId)->orderBy('type')->get();
        $checklists = JobChecklist::where('task_id', $taskId)->with('items')->get();

        return view('servicemanmodule::cleaner.jobs.show', compact('job', 'photos', 'checklists'));
    }

    /**
     * GPS check-in: record lat/lng + timestamp on the task and create an Attendance record.
     */
    public function checkIn(Request $request, int $taskId): RedirectResponse
    {
        $userId = Auth::id();

        $data = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $job = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $userId)
            ->where('tasks.id', $taskId)
            ->select('tasks.*')
            ->firstOrFail();

        // Geofence check if the job has a geofence configured
        if ($job->geofence_lat !== null && $job->geofence_lng !== null) {
            $distance = $this->haversineDistance(
                (float) $data['latitude'],
                (float) $data['longitude'],
                (float) $job->geofence_lat,
                (float) $job->geofence_lng
            );

            $radius = $job->geofence_radius ?? 200;

            if ($distance > $radius) {
                return redirect()->back()->withErrors([
                    'location' => "You must be within {$radius} metres of the job address to check in (current distance: {$distance}m).",
                ]);
            }
        }

        // Update task with GPS check-in
        Task::where('id', $taskId)->update([
            'checkin_lat'   => $data['latitude'],
            'checkin_lng'   => $data['longitude'],
            'checked_in_at' => now(),
        ]);

        // Create / update Attendance record for today
        $today = now()->toDateString();
        $existing = Attendance::where('user_id', $userId)
            ->where('company_id', Auth::user()->company_id)
            ->whereDate('clock_in_time', $today)
            ->latest('clock_in_time')
            ->first();

        if ($existing) {
            $existing->update([
                'latitude'   => $data['latitude'],
                'longitude'  => $data['longitude'],
                'booking_id' => $taskId,
            ]);
        } else {
            Attendance::create([
                'user_id'      => $userId,
                'company_id'   => Auth::user()->company_id,
                'clock_in_time' => now(),
                'clock_in_ip'   => $request->ip(),
                'working_from'  => 'office',
                'late'          => 'no',
                'half_day'      => 'no',
                'latitude'      => $data['latitude'],
                'longitude'     => $data['longitude'],
                'booking_id'    => $taskId,
            ]);
        }

        return redirect()->route('cleaner.jobs.show', $taskId)
            ->with('success', 'Checked in successfully.');
    }

    /**
     * GPS check-out: record lat/lng + timestamp on the task.
     */
    public function checkOut(Request $request, int $taskId): RedirectResponse
    {
        $userId = Auth::id();

        $data = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $userId)
            ->where('tasks.id', $taskId)
            ->select('tasks.*')
            ->firstOrFail();

        Task::where('id', $taskId)->update([
            'checkout_lat'   => $data['latitude'],
            'checkout_lng'   => $data['longitude'],
            'checked_out_at' => now(),
            'status'         => 'completed',
        ]);

        // Update attendance clock-out time
        Attendance::where('user_id', $userId)
            ->where('booking_id', $taskId)
            ->whereNull('clock_out_time')
            ->latest('clock_in_time')
            ->first()
            ?->update([
                'clock_out_time' => now(),
                'clock_out_ip'   => $request->ip(),
            ]);

        return redirect()->route('cleaner.jobs.show', $taskId)
            ->with('success', 'Checked out. Job marked complete.');
    }

    /**
     * Upload a before/after photo for a job.
     */
    public function uploadPhoto(Request $request, int $taskId): RedirectResponse
    {
        $userId = Auth::id();

        $request->validate([
            'photo' => 'required|image|max:10240',
            'type'  => 'required|in:before,after,damage,other',
            'caption' => 'nullable|string|max:255',
        ]);

        // Verify the task belongs to this user
        Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $userId)
            ->where('tasks.id', $taskId)
            ->select('tasks.*')
            ->firstOrFail();

        $path = $request->file('photo')->store("job-photos/{$taskId}", 'public');

        JobPhoto::create([
            'task_id'     => $taskId,
            'uploaded_by' => $userId,
            'type'        => $request->input('type', 'before'),
            'file_path'   => $path,
            'caption'     => $request->input('caption'),
        ]);

        return redirect()->route('cleaner.jobs.show', $taskId)
            ->with('success', 'Photo uploaded.');
    }

    /**
     * Toggle a checklist item completed/incomplete.
     */
    public function toggleChecklistItem(Request $request, int $itemId): RedirectResponse
    {
        $userId = Auth::id();

        $item = JobChecklistItem::findOrFail($itemId);

        // Verify the task belongs to this user
        Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $userId)
            ->where('tasks.id', $item->checklist->task_id)
            ->select('tasks.*')
            ->firstOrFail();

        if ($item->is_completed) {
            $item->update(['is_completed' => false, 'completed_by' => null, 'completed_at' => null]);
        } else {
            $item->update(['is_completed' => true, 'completed_by' => $userId, 'completed_at' => now()]);
        }

        $taskId = $item->checklist->task_id;

        return redirect()->route('cleaner.jobs.show', $taskId)
            ->with('success', 'Checklist item updated.');
    }

    /**
     * Calculate the haversine distance (metres) between two lat/lng points.
     */
    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // metres

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c);
    }
}
