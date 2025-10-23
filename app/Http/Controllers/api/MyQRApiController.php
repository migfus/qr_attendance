<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\{Request, JsonResponse};

use App\Models\QuickResponse;

class MyQRApiController extends Controller
{
    public function index(Request $req) : JsonResponse {
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        $req->validate([
            'search' => []
        ]);

        $qrs = QuickResponse::query()
            ->where('user_id', $req->user()->id)
            ->when($req->search, function($q) use($req) {
                $q->where('id', 'LIKE', '%'.$req->search.'%');
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json($qrs);
    }
}
