<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};

use App\Models\Notification;

class NotificationApiController extends Controller
{

    public function index(Request $req) : JsonResponse {
        if($this->preventMemberRoleAccess($req)) return $this->noPermissionJsonResponse();

        $notifications = Notification::query()
            ->where('user_id', $req->user()->id)
            ->with('notification_type')
            ->paginate(20);

        return response()->json($notifications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
