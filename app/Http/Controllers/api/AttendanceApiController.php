<?php
namespace App\Http\Controllers\api;

use Carbon\Carbon;
use Illuminate\Http\{Request, JsonResponse};

use App\Models\Attendance;

class AttendanceApiController extends Controller
{
    // TODO Show Attendance list
    public function index(Request $req) : JsonResponse {
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        $req->validate([
            'search' => [],
            'sort' => ['required'],
        ]);

        // TODO: replace $type with valid type only to avoid database error

        $attendances = Attendance::query()
            ->with([
                'author',
                'attendees' => function($q) {
                    return $q->limit(10);
                }
            ])
            ->withCount(['attendees', 'attendees_participated_only'])
            ->when($req->search, function($q) use($req) {
                $q->where('name', 'LIKE', '%' . $req->search . '%');
            })
            ->where('end_date_time', '>=', Carbon::now()->utc())
            ->where('start_date_time', '<=', Carbon::now()->utc())
            ->orderBy('created_at', $req->sort)
            ->get();

        return response()->json($attendances);
    }

    // TODO Show Attendance Info with attendee
    public function show(Request $req, string $id) : JsonResponse {
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        $attendance = Attendance::where('id', $id)
            ->with([
                'author',
                'attendees' => function($q) {
                    return $q->limit(10);
                }
            ])
            ->withCount(['attendees', 'attendees_participated_only'])
            ->where('end_date_time', '>=', Carbon::now()->utc())
            ->where('start_date_time', '<=', Carbon::now()->utc())
            ->first();

        return response()->json($attendance);
    }

    // TODO Update Info about attendance from [show]
    public function update(Request $req, string $id) : JsonResponse {
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        // TODO CHECK ID if valid
        // TODO validate datas
        // TODO Update attendance database
        // TODO response with update
    }

    // TODO Optional maybe
    public function destroy(Request $req, string $id)   : JsonResponse {
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        Attendance::where('id', $id)->delete();

        return response()->json(['message' => 'success']);
    }
}
