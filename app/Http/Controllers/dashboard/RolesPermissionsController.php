<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\{Request, RedirectResponse};
use Inertia\{Inertia, Response};

use App\Models\{Permission, PermissionModule, PermissionType, User, Role};

class RolesPermissionsController extends Controller {
    protected $search_filter_in = 'Users,Permissions';


    public function index(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Roles & Permissions/View/All'], 'dashboard.index', [], function () use ($req) {
            $this->defaultValidationQuery($req, $this->search_filter_in);

            $index_data = Role::query()
                // ->with('users.department')
                ->with(['users', 'permissions.permission_type', 'permissions.permission_module', 'hero_icon'])
                ->when($req->search_filter == '', function ($q) use ($req) {
                    $q->where('name', 'LIKE', "%" . $req->search . "%")
                        ->orWhere('display_name', 'LIKE', "%" . $req->search . "%");
                })
                ->when($req->search_filter == 'Users', function ($q) use ($req) {
                    $q->whereHas('users', function ($q2) use ($req) {
                        $q2->where('name', 'LIKE', '%' . $req->search . '%');
                    });
                })
                ->when($req->search_filter == 'Permissions', function ($q) use ($req) {
                    $q->whereHas('permissions', function ($q2) use ($req) {
                        $q2->where('name', 'LIKE', '%' . $req->search . '%');
                    });
                })
                ->orderBy('created_at', 'DESC')
                ->get()
                ->map(fn($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'display_name' => $item->display_name,
                    'description' => $item->description,
                    'hero_icon' => $item->hero_icon,
                    'users' => $item->users,
                    'permissions' => $item->permissions,
                ]);
            return Inertia::render('dashboard/roles-permissions/(Index)', [
                'page_title' => 'Roles & Permissions',
                'sidebar' => true,
                'index_data' => $index_data,
                'permission_modules' => PermissionModule::with(['permissions.permission_type'])->get(),
                'permission_types' => PermissionType::get(),
            ]);
        });
    }

    public function update(Request $req, $id): RedirectResponse {
        return $this->checkPermissions($req, ['Roles & Permissions/Update/All'], 'dashboard.roles-permissions.index', [], function () use ($req, $id) {
            $req->validate([
                'type' => ['in:updateRole,transferUser,updateDetails']
            ]);

            switch ($req->type) {
                case 'updateRole':
                    $this->updateRole($req, $id);
                    return to_route('dashboard.roles-permissions.index')
                        ->with('success', ['title' => 'Roles & Permission', 'content' => 'Role has been updated']);
                    break;
                case 'updateDetails':
                    $this->updateDetails($req, $id);
                    return to_route('dashboard.roles-permissions.index')
                        ->with('success', ['title' => 'Roles & Permission', 'content' => 'Role has been updated']);
                    break;
                default: // transferUser
                    $this->updateTransferUser($req, $id);
                    return to_route('dashboard.roles-permissions.index')
                        ->with('success', ['title' => 'Roles & Permission', 'content' => 'User has been tranfered.']);
            }
        });
    }
    private function updateTransferUser(Request $req, $user_id) {
        $req->validate([
            'role_id' => ['required']
        ]);

        $user = User::findOrFail($user_id);

        $user_roles = $user->roles->pluck('id');

        $current_role = Role::where('id', $user_roles[0])->withCount('users')->first();
        if ($current_role->name == 'admin') {
            if ($current_role->users_count <= 1) {
                return to_route('dashboard.roles-permissions.index')
                    ->with('error', ['title' => 'Role', 'content' => 'You cannot transfer the last user in the admin role.']);
            }
        }

        $user->syncRoles([Role::findOrFail($req->role_id)]);
    }
    private function updateRole(Request $req, $role_id) {
        $role = Role::findOrFail($role_id);

        if ($role->name == 'admin' || $role->name == 'inactive') {
            return to_route('dashboard.roles-permissions.index')
                ->with('error', ['title' => 'Roles', 'content' => 'You cannot update the default roles. Please create another role for customizable permissions.']);
        }

        $req->validate([
            'from_permission_id' => ['nullable'],
            'to_permission_id' => ['nullable']
        ]);

        // Blank to All
        if ($req->from_permission_id == '') {
            $role->givePermission(Permission::find($req->to_permission_id));
        }
        // All to Blank

        else if ($req->to_permission_id == '') {
            $role->removePermission(Permission::find($req->from_permission_id));
        }
        // All to Own / Own to All
        else {
            $role->removePermission(Permission::find($req->from_permission_id));
            $role->givePermission(Permission::find($req->to_permission_id));
        }
    }
    private function updateDetails(Request $req, $role_id) {
        $req->validate([
            'name' => ['required', 'string', 'unique:roles,name,' . $role_id, 'min:6'],
            'description' => ['nullable', 'string']
        ]);

        $role = Role::findOrFail($role_id);

        if ($role->name == 'admin' || $role->name == 'inactive') {
            return to_route('dashboard.roles-permissions.index')
                ->with('error', ['title' => 'Roles', 'content' => 'Sorry, you cannot modify default role.']);
        }

        $role->update([
            'name' => $req->string('name'),
            'display_name' => $req->string('name'),
            'description' => $req->string('description'),
        ]);
    }

    public function create(Request $req) {
        return $this->checkPermissions($req, ['Roles & Permissions/Update/All'], 'dashboard.roles-permissions.index', [], function () {
            return Inertia::render('dashboard/roles-permissions/(Create)', [
                'page_title' => 'Create Role',
                'sidebar' => true,
            ]);
        });
    }

    public function store(Request $req) {
        return $this->checkPermissions($req, ['Roles & Permissions/Update/All'], 'dashboard.roles-permissions.index', [], function () use ($req) {
            $req->validate([
                'name' => ['required', 'string', 'unique:roles,name', 'min:6'],
                'description' => ['nullable', 'string']
            ]);

            Role::create([
                'name' => strtolower($req->string('name')),
                'display_name' => $req->string('name'),
                'icon_name' => 'star_micro',
                'description' => $req->string('description')
            ]);

            return to_route('dashboard.roles-permissions.index')
                ->with('success', ['title' => 'Role', 'content' => 'New role has been created.']);
        });
    }

    public function edit(Request $req, string $role_id) {
        return $this->checkPermissions($req, ['Roles & Permissions/Update/All'], 'dashboard.roles-permissions.index', [], function () use ($req, $role_id) {
            $role = Role::findOrFail($role_id);

            if ($role->name == 'admin' || $role->name == 'inactive') {
                return to_route('dashboard.roles-permissions.index')
                    ->with('error', ['title' => 'Roles', 'content' => 'Sorry, you cannot modify default role.']);
            }

            return Inertia::render('dashboard/roles-permissions/(Update)', [
                'page_title' => 'Update Role',
                'sidebar' => true,
                'edit_data' => $role
            ]);
        });
    }

    public function destroy(Request $req, string $role_id) {
        $role = Role::findOrFail($role_id);

        if ($role->name == 'admin' || $role->name == 'inactive') {
            return to_route('dashboard.roles-permissions.index')
                ->with('error', ['title' => 'Roles', 'content' => 'Sorry, you cannot delete default role.']);
        }

        $this->migrateUserToInactiveRole($role->users);

        $role->delete();
        return to_route('dashboard.roles-permissions.index')
            ->with('success', ['title' => 'Role', 'content' => 'Role has been deleted and the users has been migrated to [Inactive Role].']);
    }
    private function migrateUserToInactiveRole($users) {
        $role = Role::where('name', 'inactive')->first();

        foreach ($users as $user) {
            $user->syncRoles([$role]);
        }
    }
}
