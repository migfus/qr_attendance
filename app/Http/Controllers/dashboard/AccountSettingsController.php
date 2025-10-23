<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Inertia\{Inertia, Response};
use App\Http\Requests\dashboard\AccountSettings\store\StatusMessagesRequest;

use App\Models\UserSettings;
use Illuminate\Support\Facades\{DB, Hash};

class AccountSettingsController extends Controller {

    // NOTE: from [index] to [show], I want to remember the url (technicaly creating history)
    public function show(Request $req, $id): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Account Settings/View/All'], 'dashboard.index', [], function () use ($req) {
            $req->validate([
                'search' => ['nullable', 'string']
            ]);

            $index_data = UserSettings::where('user_id', $req->user()->id)->first();

            if ($index_data && is_string($index_data->config)) {
                $index_data->config = json_decode($index_data->config, true);
            }

            return Inertia::render('dashboard/account-settings/(Index)', [
                'page_title' => 'QR Lists',
                'sidebar' => true,
                'user_settings' => $index_data->config,
                'sessions' => DB::table('sessions')->where('user_id', $req->user()->id)->get()->map(fn($item) => [
                    'id' => $item->id,
                    'ip_address' => $item->ip_address,
                    'user_agent' => $item->user_agent,
                    'last_activity' => $item->last_activity
                ])
            ]);
        });
    }

    public function store(Request $req): RedirectResponse | JsonResponse {
        $req->validate([
            'route_id' => ['required', 'integer'],
            'type' => ['required', 'in:update-password,notification-config,themes,card-show-description,remove-devices,remove-device,upload-avatar,update-profile,request-status-messages']
        ]);

        return $this->checkPermissions($req, ['Account Settings/Update/All'], 'dashboard.account-settings.show', ['account_setting' => $req->integer('route_id')], function () use ($req) {
            $message = ['title' => 'Notifications', 'content' => 'Notification preference has been changed.'];

            switch ($req->type) {
                case 'update-password':
                    $this->storeUpdatePassword($req);
                    $message = ['title' => 'Password', 'content' => 'Password has been changed.'];
                    break;
                case 'notification-config':
                    $this->storeNotifications($req);
                    $message = ['title' => 'Notifications', 'content' => 'Notification preference has been changed.'];
                    break;
                case 'themes':
                    $this->storeThemes($req);
                    $message = ['title' => 'Theme', 'content' => 'Theme has been changed.'];
                    break;
                case 'card-show-description':
                    $this->storeShowDescription($req);
                    $message = ['title' => 'Description', 'content' => 'Card description has been changed'];
                    break;
                case  'remove-devices':
                    $this->storeRemoveDevices($req);
                    $message = ['title' => 'Remove Device', 'content' => 'The selected device has been removed.'];
                    break;
                case  'remove-device':
                    $this->storeRemoveDevice($req);
                    $message = ['title' => 'Remove Device', 'content' => 'The selected device has been removed.'];
                    break;
                case 'upload-avatar':
                    $this->storeUploadAvatar($req);
                    $message = ['title' => 'Upload Avatar', 'content' => 'Successfully updated your avatar.'];
                    break;
                case 'update-profile':
                    $this->storeUpdateProfile($req);
                    $message = ['title' => 'Updated Profile', 'content' => 'Successfully updated your profile.'];
                case 'request-status-messages':
                    $this->storeRequestStatusMessages($req);
                    $message = ['title' => 'Updated Request Messages', 'content' => 'Successfully updated your profile.'];
            }

            return to_route('dashboard.account-settings.show', ['account_setting' => $req->route_id])
                ->with('success', $message);
        });
    }
    private function storeUpdateProfile(Request $req): void {
        $req->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,' . $req->user()->id . ',id'],
        ]);

        $req->user()->update([
            'name' => $req->name,
            'email' => $req->email,
        ]);
    }
    private function storeUploadAvatar(Request $req): void {
        $req->validate([
            'avatar' => ['required', 'file', 'mimes:jpg,jpeg', 'max:1024'],
        ]);

        ['image_url' => $avatar_url] = $this->uploadFromCroppie($req->avatar, '/avatars', false);

        $req->user()->update([
            'avatar_url' => $avatar_url
        ]);
    }
    private function storeUpdatePassword(Request $req): void {
        $req->validate([
            'password' => ['required', 'min:6', 'confirmed'],
            'current_password' => ['required', function ($att, $val, $fail) use ($req) {
                if (!Hash::check($val, $req->user()->password)) {
                    $fail('The old password is incorrect.');
                }
            }],
        ]);

        $req->user()->update([
            'password' => Hash::make($req->password)
        ]);
    }
    private function storeNotifications(Request $req): void {
        $req->validate([
            'notifications_config' => ['required', 'array'],
            'notifications_config.email.registered' => ['required', 'boolean'],
            'notifications_config.push.registered' => ['required', 'boolean'],
            'notifications_config.sms.registered' => ['required', 'boolean'],
        ]);

        UserSettings::where('user_id', $req->user()->id)->update([
            'config->notification->email->registered' => $req->notifications_config['email']['registered'],
            'config->notification->push->registered' => $req->notifications_config['push']['registered'],
            'config->notification->sms->registered' => $req->notifications_config['sms']['registered'],
        ]);
    }
    private function storeThemes(Request $req): void {
        $req->validate([
            'mode' => ['required', 'string'],
        ]);

        UserSettings::where('user_id', $req->user()->id)->update([
            'config->themes' => $req->mode,

        ]);
    }
    private function storeShowDescription(Request $req): void {
        $req->validate([
            'show_description' => ['required', 'boolean'],
        ]);

        UserSettings::where('user_id', $req->user()->id)->update([
            'config->card->show_description' => $req->show_description,
        ]);
    }
    private function storeRemoveDevices(Request $req): void {
        DB::table('sessions')->where('user_id', $req->user()->id)->delete();
    }
    private function storeRemoveDevice(Request $req): void {
        $req->validate([
            'id' => ['required']
        ]);

        DB::table('sessions')->where('user_id', $req->user()->id)->where('id', $req->id)->delete();
    }
    private function storeRequestStatusMessages(StatusMessagesRequest $req): void {
        $req_data = $req->validated();

        // dd($req->request_status_messages['Unverified']);

        UserSettings::where('user_id', $req->user()->id)->update([
            'config->request_status_messages->Unverified' => $req->request_status_messages['Unverified'],
            'config->request_status_messages->Processing' => $req->request_status_messages['Processing'],
            'config->request_status_messages->Completed' => $req->request_status_messages['Completed'],
            'config->request_status_messages->Claimed' => $req->request_status_messages['Claimed'],
            'config->request_status_messages->Rejected' => $req->request_status_messages['Rejected'],
            'config->request_status_messages->Removed' => $req->request_status_messages['Removed'],
        ]);
    }
}
