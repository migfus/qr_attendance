<?php

namespace App\Http\Controllers\dashboard;

use Inertia\{Response, Inertia};
use Carbon\Carbon;
use Illuminate\Http\{Request, RedirectResponse};

use App\Models\{Department, Employee, User, GuestQrCode, Permission};
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller {
    public function index(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Dashboard/View/All', 'Dashboard/View/Arta Only'], 'arta-id.index', [], function () {
            $stat_data['registrations'] = Cache::remember('registrations_dashboard', 5 * 60, function () {
                return [
                    'labels' => [
                        Carbon::now()->sub(6, 'days')->format('m/d'),
                        Carbon::now()->sub(5, 'days')->format('m/d'),
                        Carbon::now()->sub(4, 'days')->format('m/d'),
                        Carbon::now()->sub(3, 'days')->format('m/d'),
                        Carbon::now()->sub(2, 'days')->format('m/d'),
                        Carbon::now()->sub(1, 'days')->format('m/d'),
                        Carbon::now()->format('m/d'),
                    ],
                    'datasets' => [
                        [
                            'label' => 'Unverified',
                            'backgroundColor' => '#ef4444', //redish
                            'data' => $this->unverifiedEmployeeRequest()
                        ],
                        [
                            'label' => 'Processing',
                            'backgroundColor' => '#5b715e', //brandish or green
                            'data' => $this->processingEmployeeRequest()
                        ],
                    ]
                ];
            });

            $index_data = Cache::remember('new_employees', 5 * 60, function () {
                return Employee::orderBy('created_at', 'DESC')->with(['extraName'])->limit(10)->get()->map(function ($item) {
                    return [
                        'id' => $item['id'],
                        'last_name' => $item['last_name'],
                        'first_name' => $item['first_name'],
                        'mid_name' => $item['mid_name'],
                        'extra_name' => $item['extraName'],
                    ];
                });
            });

            $new_departments = Cache::remember('new_departments', 5 * 60, function () {
                return Department::withCount('employees')->orderBy('created_at', 'DESC')->limit(10)->get();
            });

            return Inertia::render('dashboard/(Index)', [
                'page_title' => 'Dashboard',
                'sidebar' => true,
                'stat_data' => $stat_data,
                'index_data' => $index_data,
                'storage' => $this->freeSpace(),
                'total_users' => User::count(),
                'total_employees' => Employee::count(),
                'total_qr' => GuestQrCode::count(),
                'new_departments' => $new_departments,
            ]);
        });
    }

    public function unverifiedEmployeeRequest() {
        return [
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(6, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 1); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(5, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 1); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(4, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 1); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(3, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 1); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(2, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 1); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(1, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 1); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))
                ->count()
        ];
    }

    public function processingEmployeeRequest() {
        return [
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(6, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 2); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(5, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 2); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(4, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 2); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(3, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 2); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(2, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 2); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->sub(1, 'days')->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 2); // Assuming 1 is the ID for 'Unverified'
                })
                ->count(),
            Employee::query()
                ->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))
                ->whereHas('latestRequestStatus', function ($query) {
                    $query->where('request_status_type_id', 2); // Assuming 1 is the ID for 'Unverified'
                })
                ->count()
        ];
    }

    public function freeSpace() {
        $path = '/'; // Root path or use getcwd() for current script directoryP

        $total = disk_total_space($path); // total space in bytes
        $free = disk_free_space($path);   // free space in bytes
        $used = $total - $free;

        function formatBytes($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $power = floor(($bytes ? log($bytes) : 0) / log(1024));
            return round($bytes / pow(1024, $power), $precision) . ' ' . $units[$power];
        }

        $out_data['total'] = formatBytes($total);
        $out_data['free'] = formatBytes($free);
        $out_data['used'] = formatBytes($used);

        return $out_data;
    }
}
