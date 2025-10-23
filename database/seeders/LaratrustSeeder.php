<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{Role, Permission, PermissionModule, PermissionType};

class LaratrustSeeder extends Seeder {
    public function run(): void {
        $permission_modules = [
            'account-settings' => PermissionModule::create([
                'name' => 'Account Settings',
            ]),
            'roles-permissions' => PermissionModule::create([
                'name' => 'Roles & Permissions',
            ]),
            'notifications' => PermissionModule::create([
                'name' => 'Notifications'
            ]),
            'gallery' => PermissionModule::create([
                'name' => 'Gallery'
            ]),
            'data-configuration' => PermissionModule::create([
                'name' => 'Data Configuration'
            ]),
            'departments' => PermissionModule::create([
                'name' => 'Departments'
            ]),
            'guest-qr' => PermissionModule::create([
                'name' => 'Guest QR'
            ]),
            'users' => PermissionModule::create([
                'name' => 'Users'
            ]),
            'arta-id' => PermissionModule::create([
                'name' => 'ARTA ID'
            ]),
            'request-status' => PermissionModule::create([
                'name' => 'Request Status'
            ]),
            'dashboard' => PermissionModule::create([
                'name' => 'Dashboard'
            ]),
        ];
        $permission_types = [
            'view' => PermissionType::create([
                'name' => 'View'
            ]),
            'update' => PermissionType::create([
                'name' => 'Update'
            ]),
            'delete' => PermissionType::create([
                'name' => 'Delete'
            ]),
            'create' => PermissionType::create([
                'name' => 'Create'
            ]),
        ];


        $role = [
            'admin' => Role::create([
                'name' => 'admin',
                'icon_name' => 'star_micro',
                'display_name' => 'Administrator (Default)',
                'description' => 'Administrator'
            ]),
            'arta-manager' => Role::create([
                'name' => 'arta-manager',
                'icon_name' => 'identification_micro',
                'display_name' => 'Arta Manager',
                'description' => 'Manager for the Arta ID.'
            ]),

            'inactive' => Role::create([
                'name' => 'inactive',
                'icon_name' => 'identification_micro',
                'display_name' => 'Inactive (Default)',
                'description' => 'Inactive users.',
            ]),
        ];

        // echo "Roles & Permissions Module ID: " . $permission_modules['roles-permissions']->id . PHP_EOL;
        // permissions naming convention
        // All - enable user to see every data
        // Own - only their data
        // Added - only if they're added to another user.

        $perm = [
            // NOTE Account Settings
            'account-settings-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['account-settings']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Account Settings/View/All',
                'display_name' => 'View', // allow user to view their account settings
                'description' => 'All' // module
            ]),
            'account-settings-update-all' => Permission::create([
                'permission_module_id' => $permission_modules['account-settings']->id,
                'permission_type_id' => $permission_types['update']->id,
                'name' => 'Account Settings/Update/All',
                'display_name' => 'Update', // allow user update their settings
                'description' => 'All' // module
            ]),
            'account-settings-delete-all' => Permission::create([
                'permission_module_id' => $permission_modules['account-settings']->id,
                'permission_type_id' => $permission_types['delete']->id,
                'name' => 'Account Settings/Delete/All',
                'display_name' => 'Delete', // allow user to remove their account
                'description' => 'All' // module
            ]),

            // NOTE Roles & Permissions
            'roles-permissions-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['roles-permissions']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Roles & Permissions/View/All',
                'display_name' => 'View', // view roles & permissions
                'description' => 'All'
            ]),
            'roles-permissions-update-all' => Permission::create([
                'permission_module_id' => $permission_modules['roles-permissions']->id,
                'permission_type_id' => $permission_types['update']->id,
                'name' => 'Roles & Permissions/Update/All',
                'display_name' => 'Update', // allow transfer users to another roles
                'description' => 'All'
            ]),
            'roles-permissions-create-all' => Permission::create([
                'permission_module_id' => $permission_modules['roles-permissions']->id,
                'permission_type_id' => $permission_types['create']->id,
                'name' => 'Roles & Permissions/Create/All',
                'display_name' => 'Create', // allow transfer users to another roles
                'description' => 'All'
            ]),

            // NOTE Roles & Permissions
            'notifications-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['notifications']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Notifications/View/All',
                'display_name' => 'View', // allow to view their notifications
                'description' => 'All'
            ]),
            'notifications-update-all' => Permission::create([
                'permission_module_id' => $permission_modules['notifications']->id,
                'permission_type_id' => $permission_types['update']->id,
                'name' => 'Notifications/Update/All',
                'display_name' => 'Update', // mark [read | unread]
                'description' => 'All'
            ]),
            'notifications-delete-all' => Permission::create([
                'permission_module_id' => $permission_modules['notifications']->id,
                'permission_type_id' => $permission_types['delete']->id,
                'name' => 'Notifications/Delete/All',
                'display_name' => 'Delete', // remove all notifications
                'description' => 'All'
            ]),

            // NOTE Roles & Permissions
            'gallery-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['gallery']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Gallery/View/All',
                'display_name' => 'Gallery View', // view uploaded images
                'description' => 'All'
            ]),

            // NOTE Data Configuration
            'data-configuration-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['data-configuration']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Data Configuration/View/All',
                'display_name' => 'View', // view data configuration
                'description' => 'All'
            ]),
            'data-configuration-update-all' => Permission::create([
                'permission_module_id' => $permission_modules['data-configuration']->id,
                'permission_type_id' => $permission_types['update']->id,
                'name' => 'Data Configuration/Update/All',
                'display_name' => 'Update', // update all data configuration
                'description' => 'All'
            ]),
            'data-configuration-update-owner' => Permission::create([
                'permission_module_id' => $permission_modules['data-configuration']->id,
                'permission_type_id' => $permission_types['update']->id,
                'name' => 'Data Configuration/Update/Owner',
                'display_name' => 'Update', // update only their created data
                'description' => 'Owner'
            ]),
            'data-configuration-create-all' => Permission::create([
                'permission_module_id' => $permission_modules['data-configuration']->id,
                'permission_type_id' => $permission_types['create']->id,
                'name' => 'Data Configuration/Create/All',
                'display_name' => 'Create', // create new data
                'description' => 'All'
            ]),
            'data-configuration-delete-all' => Permission::create([
                'permission_module_id' => $permission_modules['data-configuration']->id,
                'permission_type_id' => $permission_types['delete']->id,
                'name' => 'Data Configuration/Delete/All',
                'display_name' => 'Delete', // allows to delete all data
                'description' => 'All'
            ]),
            'data-configuration-delete-owner' => Permission::create([
                'permission_module_id' => $permission_modules['data-configuration']->id,
                'permission_type_id' => $permission_types['delete']->id,
                'name' => 'Data Configuration/Delete/Owner',
                'display_name' => 'Delete', // allows to delete his created data
                'description' => 'Owner'
            ]),

            // NOTE Departments
            'department-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['departments']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Department/View/All',
                'display_name' => 'View', // view data configuration
                'description' => 'All'
            ]),
            'department-update-all' => Permission::create([
                'permission_module_id' => $permission_modules['departments']->id,
                'permission_type_id' => $permission_types['update']->id,
                'name' => 'Department/Update/All',
                'display_name' => 'Update', // update all data configuration
                'description' => 'All'
            ]),
            'department-update-owner' => Permission::create([
                'permission_module_id' => $permission_modules['departments']->id,
                'permission_type_id' => $permission_types['update']->id,
                'name' => 'Department/Update/Owner',
                'display_name' => 'Update', // update only their created data
                'description' => 'Owner'
            ]),
            'department-create-all' => Permission::create([
                'permission_module_id' => $permission_modules['departments']->id,
                'permission_type_id' => $permission_types['create']->id,
                'name' => 'Department/Create/All',
                'display_name' => 'Create', // create new data
                'description' => 'All'
            ]),
            'department-delete-all' => Permission::create([
                'permission_module_id' => $permission_modules['departments']->id,
                'permission_type_id' => $permission_types['delete']->id,
                'name' => 'Department/Delete/All',
                'display_name' => 'Delete', // allows to delete all data
                'description' => 'All'
            ]),
            'department-delete-owner' => Permission::create([
                'permission_module_id' => $permission_modules['departments']->id,
                'permission_type_id' => $permission_types['delete']->id,
                'name' => 'Department/Delete/Owner',
                'display_name' => 'Delete', // allows to delete his created data
                'description' => 'Owner'
            ]),

            // NOTE Guest QR
            'guest-qr-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['guest-qr']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Guest QR/View/All',
                'display_name' => 'View', // view [guest qr], show [guest qr], print [guest qr]
                'description' => 'All'
            ]),
            'guest-qr-update-all' => Permission::create([
                'permission_module_id' => $permission_modules['guest-qr']->id,
                'permission_type_id' => $permission_types['update']->id,
                'name' => 'Guest QR/Update/All',
                'display_name' => 'Update', // update all [guest qr]
                'description' => 'All'
            ]),
            'guest-qr-create-all' => Permission::create([
                'permission_module_id' => $permission_modules['guest-qr']->id,
                'permission_type_id' => $permission_types['create']->id,
                'name' => 'Guest QR/Create/All',
                'display_name' => 'Create', // create new [guest qr]
                'description' => 'All'
            ]),
            'guest-qr-delete-all' => Permission::create([
                'permission_module_id' => $permission_modules['guest-qr']->id,
                'permission_type_id' => $permission_types['delete']->id,
                'name' => 'Guest QR/Delete/All',
                'display_name' => 'Delete', // allows to delete all [guest qr]
                'description' => 'All'
            ]),

            // NOTE Users
            'users-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['users']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Users/View/All',
                'display_name' => 'View', // view [users], show [user], print [users]
                'description' => 'All'
            ]),
            'users-update-all' => Permission::create([
                'permission_module_id' => $permission_modules['users']->id,
                'permission_type_id' => $permission_types['update']->id,
                'name' => 'Users/Update/All',
                'display_name' => 'Update', // update all [users]
                'description' => 'All'
            ]),
            'users-create-all' => Permission::create([
                'permission_module_id' => $permission_modules['users']->id,
                'permission_type_id' => $permission_types['create']->id,
                'name' => 'Users/Create/All',
                'display_name' => 'Create', // create new [user]
                'description' => 'All'
            ]),
            'users-delete-all' => Permission::create([
                'permission_module_id' => $permission_modules['users']->id,
                'permission_type_id' => $permission_types['delete']->id,
                'name' => 'Users/Delete/All',
                'display_name' => 'Delete', // allows to delete all [users]
                'description' => 'All'
            ]),

            // NOTE ARTA ID
            'arta-id-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['arta-id']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'ARTA ID/View/All',
                'display_name' => 'View', // view [arta id], show [arta id], print [arta id]
                'description' => 'All'
            ]),
            'arta-id-update-all' => Permission::create([
                'permission_module_id' => $permission_modules['arta-id']->id,
                'permission_type_id' => $permission_types['update']->id,
                'name' => 'ARTA ID/Update/All',
                'display_name' => 'Update', // update all [arta id] (request status, edit image, upload image)
                'description' => 'All'
            ]),
            'arta-id-delete-all' => Permission::create([
                'permission_module_id' => $permission_modules['arta-id']->id,
                'permission_type_id' => $permission_types['delete']->id,
                'name' => 'ARTA ID/Delete/All',
                'display_name' => 'Delete', // allows to delete all [arta id]
                'description' => 'All'
            ]),

            // NOTE Request Status
            'request-status-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['request-status']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Request Status/View/All',
                'display_name' => 'View', // view [arta id], show [arta id], print [arta id]
                'description' => 'All'
            ]),


            // NOTE Request Status
            'dashboard-view-all' => Permission::create([
                'permission_module_id' => $permission_modules['dashboard']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Dashboard/View/All',
                'display_name' => 'View', // view [system status, arta-id status & summary, guest-qr status & summary]
                'description' => 'All'
            ]),
            'dashboard-view-arta-only' => Permission::create([
                'permission_module_id' => $permission_modules['dashboard']->id,
                'permission_type_id' => $permission_types['view']->id,
                'name' => 'Dashboard/View/Arta Only',
                'display_name' => 'View', // view [system status, arta-id status & summary]
                'description' => ' Arta Only'
            ]),
        ];

        $role['admin']->givePermissions([...$perm]);

        $role['arta-manager']->givePermissions([
            // NOTE: Allows to access their [Account Settings]
            $perm['account-settings-view-all'],
            $perm['account-settings-update-all'],
            $perm['account-settings-delete-all'],

            // NOTE: Allows to access their [Notifications]
            $perm['notifications-view-all'],
            $perm['notifications-update-all'],
            $perm['notifications-delete-all'],

            // NOTE: Allows to access gallery [Gallery]
            // NOTE: There's no other functions.
            $perm['gallery-view-all'],

            // NOTE: Allows to do basic crud but only themselves [Department]
            $perm['department-view-all'],
            $perm['department-update-owner'],
            $perm['department-create-all'],
            $perm['department-delete-owner'],

            // NOTE: Allows to access all functions
            $perm['arta-id-view-all'],
            $perm['arta-id-update-all'],
            $perm['arta-id-delete-all'],

            // NOTE: Allow them to access all functions
            $perm['request-status-view-all'],

            // NOTE: Allow to access arta-id
            $perm['dashboard-view-arta-only'],
        ]);

        // NOTE: DOUBLE CHECK THE TABLE DATA IF THERE'S AN [USER_ID] ATTACHED BEFORE WE DEPLOY
    }
}
