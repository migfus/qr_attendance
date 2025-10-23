<?php
namespace App\Http\Controllers\api;

use Carbon\Carbon;
use Illuminate\Http\{Request, JsonResponse};

use App\Models\{Attendee, QuickResponse};

class AttendeeApiController extends Controller
{
    // TODO Show scanned item limited on user itself
    public function index(Request $req) : JsonResponse {
        // sleep(5);
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        $req->validate([
            'search' => [''],
            'sort' => ['required'],
            'page' => ['required']
        ]);

        $attendees = Attendee::query()
            ->with(['user', 'attendance', 'scanner'])
            ->when($req->search, function ($q) use($req){
                $q->whereHas('user', function($qr) use($req) {
                    $qr->where('name', 'LIKE', '%'.$req->search.'%');
                });
            })
            ->where('scanner_user_id', $req->user()->id)
            ->orderBy('id', 'DESC')
            ->paginate(20);

        return response()->json($attendees);
    }

    // TODO Scanned data from scanner
    public function store(Request $req) : JsonResponse {
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        $req->validate([
            'quick_response_id' => ['required'],
            'attendance_id' => ['required', 'ulid']
        ]);

        // TODO if QR ID is invalid make sure find a way to be backward compatible
        $qr = QuickResponse::where('id', $req->quick_response_id)->first();
        if(!$qr) {
            return response()->json(['error' => 'Invalid QR', 'details' => 'QR code is not registered to this system.'], 422);
        }

        $attendee = Attendee::where('attendance_id', $req->attendance_id)->where('user_id', $qr->user_id)->first();
        if(!$attendee) {
            return response()->json(['error' => 'Attendee is unregistered.', 'details' => 'Attendee is not registered to this attendance.'], 422);
        }

        // NOTE Checks if attendee already scanned
        if($attendee->scanned_date_time)
            return response()->json(['error' => 'Already scanned', 'details' => 'The member is already scanned', 'scanned_date_time' => $attendee->scanned_date_time], 422);

        // NOTE Scanned
        $attendee->update([
            'scanned_date_time' => Carbon::now(),
            'scanner_user_id' => $req->user()->id,
        ]);

        $attendee = Attendee::where('id', $attendee->id)->with(['attendance', 'user', 'scanner'])->first();

        return response()->json($attendee);
    }

    // TODO Add Information of Scanned person/people
    public function show(Request $req, string $id) : JsonResponse {
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        $attendee = Attendee::query()
            ->where('id', $id)
            ->with(['user', 'attendance'])
            ->first();

        if(!$attendee)
            return response()->json(['error' => 'Invalid ID or ID not found'], 422);

        return response()->json($attendee);
    }

    public function destroy(Request $req, string $id) : JsonResponse {
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        $attendee = Attendee::where('id', $id)->first();
        if(!$attendee)
            return response()->json(['error' => 'Invalid ID']);

        $attendee->update(['scanned_date_time' => null, 'scanner_user_id' => null]);
        return response()->json($attendee);
    }
}
