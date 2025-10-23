<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Hash;
use Inertia\{Inertia, Response};

use App\Models\{
    Role,
    UserSettings,
    User
};

class UserController extends Controller {
    protected $search_filter_in = "Administrator,Manager,Scanner,Member";

    public function prints(Request $req) {
        return $this->checkPermissions($req, ['Users/View/All'], 'dashboard.index', [], function () use ($req) {

            $this->defaultValidationQuery($req, $this->search_filter_in);

            $print_data = User::query()
                ->with(['roles', 'department'])
                ->when($req->search_filter, function ($q) use ($req) {
                    $q->whereHas('roles', function ($query) use ($req) {
                        $query->where('display_name', $req->search_filter);
                    });
                })
                ->when($req->start, function ($q) use ($req) {
                    $q->whereDate('created_at', '>=', $req->start);
                })
                ->when($req->end, function ($q) use ($req) {
                    $q->whereDate('created_at', '<=', $req->end);
                })
                ->when($req->search, function ($q) use ($req) {
                    $q->where('name', 'LIKE', '%' . $req->search . '%')
                        ->orWhere('email', 'LIKE', '%' . $req->search . '%');
                })
                ->orderBy('created_at', 'DESC')
                ->get();

            $filename = 'users_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $columns = [
                'ID',
                'Name',
                'Department',
                'Email',
                'Avatar Link',
                'Created At'
            ];

            $callback = function () use ($print_data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($print_data as $row) {
                    fputcsv($file, [
                        $row->id,
                        $row->name,
                        $row->department['name'],
                        $row->email,
                        env('APP_URL', 'https://qr.cmuohrm.site') . $row->avatar_url,
                        $row->created_at,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        });
    }

    public function index(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Users/View/All'], 'dashboard.index', [], function () use ($req) {
            $this->defaultValidationQuery($req, $this->search_filter_in);

            $index_data = User::query()
                ->with(['roles', 'department'])
                ->when($req->search_filter, function ($q) use ($req) {
                    $q->whereHas('roles', function ($query) use ($req) {
                        $query->where('display_name', $req->search_filter);
                    });
                })
                ->when($req->start, function ($q) use ($req) {
                    $q->whereDate('created_at', '>=', $req->start);
                })
                ->when($req->end, function ($q) use ($req) {
                    $q->whereDate('created_at', '<=', $req->end);
                })
                ->when($req->search, function ($q) use ($req) {
                    $q->where('name', 'LIKE', '%' . $req->search . '%')
                        ->orWhere('email', 'LIKE', '%' . $req->search . '%');
                })
                ->orderBy('created_at', 'DESC')
                ->paginate(20)
                ->through(fn($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'email' => $item->email,
                    'avatar_url' => $item->avatar_url,
                    'roles' => $item->roles,
                    'department' => $item->department,
                ]);


            return Inertia::render('dashboard/users/(Index)', [
                'page_title' => 'Users',
                'sidebar' => true,
                'index_data' => $index_data
            ]);
        });
    }

    public function create(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Users/Create/All'], 'dashboard.users.index', [], function () {
            return Inertia::render('dashboard/users/(Create)', [
                'page_title' => 'Create User',
                'sidebar' => true,
                'roles' => $this->cachedRoles(),
                'departments' => $this->cachedDepartments()
            ]);
        });
    }

    public function edit(Request $req, string $id): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Users/View/All'], 'dashboard.users.index', [], function () use ($id) {
            $user = User::with(['roles', 'department'])->findOrFail($id);

            return Inertia::render('dashboard/users/(Edit)', [
                'page_title' => $user->name,
                'sidebar' => true,
                'roles' => $this->cachedRoles(),
                'departments' => $this->cachedDepartments(),
                'show_data' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'avatar_url' => $user['avatar_url'],
                    'roles' => $user['roles'],
                    'department' => $user['department']
                ],
            ]);
        });
    }

    public function store(Request $req): RedirectResponse {
        return $this->checkPermissions($req, ['Users/Create/All'], 'dashboard.users.index', [], function () use ($req) {
            $req->validate([
                'name' => ['required', 'min:5'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'min:6'],
                'role_id' => ['required', 'string'],
                'department_id' => ['required', 'integer'],
                'avatar_file' => ['required', 'file', 'mimes:jpg,jpeg', 'max:1024'],
            ]);

            ['image_url' => $avatar_url] = $this->uploadFromCroppie($req->avatar_file, '/avatars', false);

            $user = User::create([
                'name' => $req->string('name'),
                'email' => $req->string('email'),
                'password' => Hash::make($req->string('password')),
                'avatar_url' => $avatar_url,
                'department_id' => $req->integer('department_id'),
            ])
                ->addRole(Role::where('id', $req->string('role_id'))->first());
            UserSettings::create(['user_id' => $user->id, 'config' => $this->configDefaultValue()]);

            return to_route('dashboard.users.index')
                ->with('success', ['title' => 'User', 'content' => 'Successfully created.']);
        });
    }

    public function update(Request $req, User $user): RedirectResponse {
        return $this->checkPermissions($req, ['Users/Update/All'], 'dashboard.users.index', [], function () use ($req, $user) {

            $req->validate([
                'name' => ['required', 'min:5'],
                'email' => ['required', 'email', 'unique:users,email,' . $user->id],
                'password' => ['nullable', 'min:6'],
                'role_id' => ['required', 'string'],
                'department_id' => ['required', 'integer'],
                'avatar_file' => ['required', 'file', 'mimes:jpg,jpeg', 'max:1024'],

            ]);

            ['image_url' => $avatar_url] = $this->uploadFromCroppie($req->avatar_file, '/avatars', false);

            $user->update([
                'name' => $req->string('name'),
                'email' => $req->string('email'),
                'avatar_url' => $avatar_url,
                'department_id' => $req->integer('department_id'),
            ]);
            if ($req->string('password')) {
                $user->update([
                    'password' => Hash::make($req->string('password')),
                ]);
            }

            $user->syncRoles([Role::where('id', $req->string('role_id'))->first()]);

            return to_route('dashboard.users.index')
                ->with('success', ['title' => 'User', 'content' => 'Successfully updated.']);
        });
    }

    public function destroy(Request $req, User $user): RedirectResponse {
        return $this->checkPermissions($req, ['Users/Delete/All'], 'dashboard.users.index', [], function () use ($user) {
            if (Role::where('name', 'admin')->withCount('users')->first()->users_count <= 1) {
                return to_route('dashboard.users.edit', ['user' => $user->id])
                    ->with('error', ['title' => 'User', 'content' => 'You cannot delete the last admin.']);
            }

            $user->delete();

            return to_route('dashboard.users.index')
                ->with('success', ['title' => 'User', 'content' => 'Successfully deleted.']);
        });
    }

    private function configDefaultValue() {
        return json_encode([
            'themes' => 'Light',
            'card' => [
                'show_description' => true
            ],
            'notification' => [
                'push' => [
                    'registered' => true,
                ],
                'email' => [
                    'registered' => false,
                ],
                'sms' => [
                    'registered' => true,
                ],
            ],
            'request_status_messages' => [
                'Unverified' => 'Your request has been unverified and pending.',
                'Processing' => 'Your request approved and currently processing.',
                'Completed' => 'Your request is completed, you can claim the Arta ID now.',
                'Claimed' => 'Your Arta ID is now claimed.',
                'Rejected' => 'Unfortunately your request was rejected due to incorrect info.',
                'Removed' => 'Your request is now removed. It will be deleted in 30 days.',
            ],
        ]);
    }
}
