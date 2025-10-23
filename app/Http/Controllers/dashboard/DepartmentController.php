<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Cache;
use Inertia\{Inertia, Response};

use App\Models\Department;

class DepartmentController extends Controller {
    protected $search_filter_in = "";

    public function index(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Department/View/All'], 'dashboard.index', [], function () use ($req) {
            $this->defaultValidationQuery($req, $this->search_filter_in);

            $index_data = Department::query()
                ->when($req->start, function ($q) use ($req) {
                    $q->whereDate('created_at', '>=', $req->start);
                })
                ->when($req->end, function ($q) use ($req) {
                    $q->whereDate('created_at', '<=', $req->end);
                })
                ->when($req->search, function ($q) use ($req) {
                    $q->where('short_name', 'LIKE', '%' . $req->search . '%')
                        ->orWhere('name', 'LIKE', '%' . $req->search . '%');
                })
                ->orderBy('created_at', 'DESC')
                ->paginate(20)
                ->through(fn($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'short_name' => $item->short_name,
                    'image_url' => $item->image_url,
                    'email' => $item->email,
                    'phone_number' => $item->phone_number,
                    'created_at' => $item->created_at
                ]);

            return Inertia::render('dashboard/departments/(Index)', [
                'page_title' => 'Departments',
                'sidebar' => true,
                'index_data' => $index_data
            ]);
        });
    }

    public function create(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Department/Create/All'], 'dashboard.departments.index', [], function () {
            return Inertia::render('dashboard/departments/(Create)', [
                'page_title' => 'Create Department',
                'sidebar' => true,
            ]);
        });
    }

    public function store(Request $req): RedirectResponse {
        return $this->checkPermissions($req, ['Department/Create/All'], 'dashboard.departments.index', [], function () use ($req) {
            $req->validate([
                'name' => ['required', 'string'],
                'short_name' => ['required', 'string'],
                'email' => ['nullable', 'email'],
                'phone_number' => ['nullable', 'numeric'],
                'image_url' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:1024'],
            ]);

            $image_url = $this->uploadFromCroppie($req->image_url, '/departments', false);

            Department::create([
                'name' => $req->string('name'),
                'short_name' => $req->string("short_name"),
                'email' => $req->string('email')  == '' ? null : $req->string('email'),
                'phone_number' => $req->integer("phone_number") ?? null,
                'image_url' => $image_url['image_url'],
            ]);

            Cache::forget('departments');

            return to_route('dashboard.departments.index')
                ->with('success', ['title' => 'Department', 'content' => 'Successfully created.']);
        });
    }

    public function edit(Request $req, Department $department): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Department/View/All'], 'dashboard.departments.index', [], function () use ($department) {
            return Inertia::render('dashboard/departments/(Edit)', [
                'page_title' => 'Create Department',
                'sidebar' => true,
                'edit_data' => $department
            ]);
        });
    }

    public function update(Request $req, Department $department): RedirectResponse {
        return $this->checkPermissions($req, ['Department/Update/All'], 'dashboard.departments.edit', ['department' => $department->id], function () use ($req, $department) {
            $req->validate([
                'name' => ['required', 'string'],
                'short_name' => ['required', 'string'],
                'email' => ['nullable', 'email'],
                'phone_number' => ['nullable', 'numeric'],
                'image_url' => ['required', 'file', 'mimes:jpg,jpeg', 'max:1024'],
            ]);

            $image_url = null;

            if ($department->image_url == $req->string('image_url')) {
                $image_url = $req->string('image_url');
            } else {
                $image_url = $this->uploadFromCroppie($req->image_url, '/departments', false);
            }


            $department->update([
                'name' => $req->string('name'),
                'short_name' => $req->string("short_name"),
                'email' => $req->string('email')  == '' ? null : $req->string('email'),
                'phone_number' => $req->integer("phone_number") ?? null,
                'image_url' => $image_url['image_url'],
            ]);

            Cache::forget('departments');

            return to_route('dashboard.departments.index')
                ->with('success', ['title' => 'Department', 'content' => 'Successfully updated.']);
        });
    }

    public function destroy(Request $req, Department $department): RedirectResponse {
        return $this->checkPermissions($req, ['Department/Update/All'], 'dashboard.departments.edit', ['department' => $department->id], function () use ($department) {
            $department->delete();

            Cache::forget('departments');

            return to_route('dashboard.departments.index')
                ->with('success', ['title' => 'Department', 'content' => 'Successfully deleted.']);
        });
    }
}
