<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\{Request, RedirectResponse};
use Inertia\{Inertia, Response};

class NotificationController extends Controller {
    protected $search_filter_in = "Read,Unread";

    public function index(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Notifications/View/All'], 'dashboard.index', [], function () use ($req) {
            $this->defaultValidationQuery($req, $this->search_filter_in);

            $notifications = $req
                ->user()
                ->notifications()
                ->when($req->search_filter, function ($q) use ($req) {
                    switch ($req->search_filter) {
                        case 'Read':
                            $q->whereNotNull('read_at');
                            break;
                        case 'Unread':
                            $q->whereNull('read_at');
                            break;
                    }
                })
                ->when($req->start, function ($q) use ($req) {
                    $q->whereDate('created_at', '>=', $req->start);
                })
                ->when($req->end, function ($q) use ($req) {
                    $q->whereDate('created_at', '<=', $req->end);
                })
                ->when($req->search, function ($q) use ($req) {
                    $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.*')) LIKE ?", ["%{$req->search}%"]);
                })
                ->paginate(20)
                ->through(fn($item) => [
                    'id' => $item->id,
                    'data' => $item->data,
                    'read_at' => $item->read_at,
                    'created_at' => $item->created_at
                ]);

            $req->user()->unreadNotifications->markAsRead();

            return Inertia::render('dashboard/notifications/(Index)', [
                'page_title' => 'Notifications',
                'sidebar' => true,
                'index_data' => $notifications
            ]);
        });
    }

    public function store(Request $req): RedirectResponse {
        return $this->checkPermissions($req, ['Notifications/Update/All'], 'dashboard.notifications.index', [], function () use ($req) {
            $req->validate([
                'type' => ['required', 'in:mark-read,mark-unread,clear']
            ]);

            switch ($req->type) {
                case 'mark-read':
                    $this->storeReadAll($req);
                    break;
                case 'mark-unread':
                    $this->storeUnreadAll($req);
                    break;
                case 'clear':
                    $this->storeClear($req);
                    break;
            }

            return to_route('dashboard.notifications.index');
        });
    }
    private function storeReadAll(Request $req) {
        $req->validate([
            'ids' => ['required', 'array']
        ]);

        $notifications = $req->user()->notifications();

        $notifications
            ->whereIn('id', $req->ids)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
    private function storeUnreadAll(Request $req) {
        $req->validate([
            'ids' => ['required', 'array']
        ]);

        $notifications = $req->user()->notifications();

        $notifications
            ->whereIn('id', $req->ids)
            ->whereNotNull('read_at')
            ->update(['read_at' => null]);
    }
    private function storeClear(Request $req) {
        return $this->checkPermissions($req, ['Notifications/Delete/All'], 'dashboard.notifications.index', [], function () use ($req) {
            $req->validate([
                'ids' => ['required', 'array']
            ]);

            $notifications = $req->user()->notifications();

            $notifications
                ->whereIn('id', $req->ids)
                ->delete();
        });
    }
}
