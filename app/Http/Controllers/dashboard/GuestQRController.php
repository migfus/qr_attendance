<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\{Request, RedirectResponse};
use Inertia\{Inertia, Response};

use App\Models\{GuestQrCode};

class GuestQRController extends Controller {
    protected $search_filter_in = "Removed";

    public function index(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Guest QR/View/All'], 'dashboard.index', [], function () use ($req) {

            $this->defaultValidationQuery($req, $this->search_filter_in);

            $index_data = GuestQrCode::query()
                ->withTrashed()
                ->with(['department', 'status'])
                ->when($req->search_filter, function ($q) use ($req) {
                    switch ($req->search_filter) {
                        case 'Removed':
                            $q->whereNotNull('deleted_at');
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
                    $q->where('id', 'LIKE', '%' . $req->search . '%')
                        ->orWhere('name', 'LIKE', '%' . $req->search . '%');
                })
                ->orderBy('created_at', 'DESC')
                ->paginate(20)
                ->through(fn($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'department' => $item->department,
                    // 'status' => $item->status,
                    'created_at' => $item->created_at,
                    'deleted_at' => $item->deleted_at,
                ]);

            return Inertia::render('dashboard/guest-qr/(Index)', [
                'page_title' => 'QR Lists',
                'sidebar' => true,
                'index_data' => $index_data
            ]);
        });
    }

    public function edit(Request $req, string $id): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Guest QR/View/All'], 'dashboard.guest-qr.edit', ['guest_ar' => $id], function () use ($id) {

            $guest_qr_code = GuestQrCode::query()
                ->withTrashed()
                ->where('id', $id)
                ->with(['department:id,name', 'status:id,name'])
                ->first();

            return Inertia::render('dashboard/guest-qr/(Edit)', [
                'page_title' => $guest_qr_code->name,
                'sidebar' => true,
                'show_data' => [
                    'id' => $guest_qr_code->id,
                    'name' => $guest_qr_code->name,
                    'department' => $guest_qr_code->department,
                    'status' => $guest_qr_code->status,
                ],
                'employee_statuses' => $this->cachedStatuses(),
                'departments' =>  $this->cachedDepartments()
            ]);
        });
    }

    public function update(Request $req, string $id): RedirectResponse {
        return $this->checkPermissions($req, ['Guest QR/Update/All'], 'dashboard.guest-qr.index', [], function () use ($req, $id) {
            $req->validate([
                'name' => ['required', 'string'],
                'office_id' => ['required'],
                'status_id' => ['required'],
            ]);

            GuestQrCode::query()
                ->withTrashed()
                ->where('id', $id)
                ->update([
                    'name' => $req->name,
                    'status_id' => $req->status_id,
                    'department_id' => $req->office_id
                ]);


            return to_route('dashboard.guest-qr.edit', ['guest_qr' => $id])
                ->with('success', ['title' => 'Updated', 'content' => 'Information has been updated.']);
        });
    }

    public function destroy(Request $req, string $id): RedirectResponse {
        return $this->checkPermissions($req, ['Guest QR/Delete/All'], 'dashboard.guest-qr.index', [], function () use ($id) {
            GuestQrCode::withTrashed()->find($id)->forceDelete();

            return to_route('dashboard.guest-qr.index', ['qr' => $id])
                ->with('success', ['title' => 'Deleted', 'content' => 'QR Code was deleted permanently.']);
        });
    }

    public function create(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Guest QR/Create/All'], 'dashboard.guest-qr.index', [], function () {
            return Inertia::render('dashboard/guest-qr/(Create)', [
                'page_title' => 'Create QR Code',
                'sidebar' => true,
                'employee_statuses' => $this->cachedStatuses(),
                'departments' => $this->cachedDepartments(),
            ]);
        });
    }

    public function store(Request $req): RedirectResponse {
        return $this->checkPermissions($req, ['Guest QR/Create/All'], 'dashboard.guest-qr.index', [], function () use ($req) {
            $req->validate([
                'name' => ['required', 'string', 'min:6'],
                'office_id' => ['required'],
                'status_id' => ['required'],
                'id' => ['nullable', 'unique:guest_qr_codes,id'],
            ]);

            $guest_id = $req->id ? $req->id : $this->getQRId($req->string('name'));

            GuestQrCode::query()
                ->create([
                    'id' => $guest_id,
                    'guest_id' => $req->user()->id,
                    'name' => $req->name,
                    'status_id' => $req->status_id,
                    'department_id' => $req->office_id,
                ]);

            return to_route('dashboard.guest-qr.index')
                ->with('success', ['title' => 'QR Code', 'content' => 'New QR Code has been created.']);
        });
    }
}
