<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\{Request, JsonResponse};

use App\Models\{Attendance, Attendee};

class DashboardDataApiContoller extends Controller
{
    public function index(Request $req) : JsonResponse {
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        $attendee_count_monthly = Attendee::query()
            ->where('scanner_user_id', $req->user()->id)
            ->where('scanned_date_time', '>=', Carbon::now()->startOfMonth()->format('Y-m-d H:i'))
            ->where('scanned_date_time', '<=', Carbon::now()->endOfMonth()->format('Y-m-d H:i'))
            ->count();

        $attendance_count_monthly = Attendance::query()
            ->where('created_at', '>=', Carbon::now()->startOfMonth()->format('Y-m-d H:i'))
            ->where('created_at', '<=', Carbon::now()->endOfMonth()->format('Y-m-d H:i'))
            ->count();

        $monthData= [];

        for($i = 4; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthData[substr($month->format('M'), 0, 1)] =
                Attendee::query()
                    ->where('scanned_date_time', '>=', $month->startOfMonth()->format('Y-m-d H:i'))
                    ->where('scanned_date_time', '<=', $month->endOfMonth()->format('Y-m-d H:i'))
                    ->count();
        }

        return response()->json([
            'attendee_count_monthly' => $attendee_count_monthly,
            'attendance_count_monthly' => $attendance_count_monthly,
            'scan_count_monthly' => $monthData,
        ]);
    }
}
