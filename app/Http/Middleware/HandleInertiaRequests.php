<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\{Auth, Cache, DB};

use App\Models\{
    Employee,
    RequestStatusType,
    Session,
    SystemSettings,
    User,
    UserSettings
};

class HandleInertiaRequests extends Middleware {
    protected $rootView = 'app';

    public function version(Request $req): ?string {
        return parent::version($req);
    }

    public function share(Request $req): array {
        return array_merge(parent::share($req), [
            // NOTE: SYSTEM
            'title' => SystemSettings::where('name', 'System Name (Title)')->first()->value,
            'base_url' => env('APP_URL', 'https://qr.cmuohrm.site'),
            'version' => env('APP_VER', '1.0.0'),

            'sidebar' => false, // Enable Sidebar Layout [default = false]
            //   'logo' => [
            //     'lg' => SystemSettings::where('name', 'System Logo')->first()->value,
            //     'sm' => SystemSettings::where('name', 'System Small Logo (for page title)')->first()->value,
            //   ],
            'flash' => [
                'error' => fn() => $req->session()->get('error'),
                'success' => fn() => $req->session()->get('success'),
            ],
            // NOTE: AUTH
            'auth' => function () {
                if (Auth::check()) {
                    $user = User::query()
                        ->where('id', Auth::user()->id)
                        ->select('id', 'name', 'email', 'avatar_url')
                        ->with(['role'])
                        ->first();

                    return [
                        ...collect($user),
                        'user_settings' => json_decode(UserSettings::where('user_id', $user->id)->first()->config),
                        'notifications' => $this->notifications($user),
                        'request_status_count' => [
                            Employee::query()
                                ->with(['latestRequestStatus'])
                                ->whereHas('latestRequestStatus', function ($q) {
                                    $q->where('request_status_type_id', RequestStatusType::where('name', 'Unverified')->first()->id);
                                })
                                ->count(),
                            Employee::query()
                                ->with(['latestRequestStatus'])
                                ->whereHas('latestRequestStatus', function ($q) {
                                    $q->where('request_status_type_id', RequestStatusType::where('name', 'Processing')->first()->id);
                                })
                                ->count(),
                        ],
                        'active_users' => $this->activeUsers($user),
                        'permissions' => $user->allPermissions()->map(fn($item) => $item['name'])

                    ];
                } else
                    return null;
            },
        ]);
    }
    private function notifications($user) {
        return Cache::remember('notifications', 10, function () use ($user) {
            return $user->notifications()->whereNull('read_at')->paginate(10);
        });
    }

    private function activeUsers($user) {
        return Cache::remember('active_users', 10, function () use ($user) {
            return Session::query()
                ->select('user_id', DB::raw('COUNT(*) as session_count'), DB::raw('MAX(last_activity) as last_seen'))
                ->groupBy('user_id')
                ->orderByDesc('last_seen')
                ->whereNot('user_id', $user->id)
                ->whereNotNull('user_id')
                // ->where('last_activity', '>=', Carbon::now()->subHour()->timestamp)
                ->with('user')
                ->limit(5)
                ->get();
        });
    }
}
