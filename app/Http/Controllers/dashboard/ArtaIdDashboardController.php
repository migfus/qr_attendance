<?php

namespace App\Http\Controllers\dashboard;

use App\Notifications\NotifyEmployeeOnRequestStatusUpdate;
use Illuminate\Http\{RedirectResponse, Request};
use Inertia\{Inertia, Response};

use App\Models\{Employee, FileType, RequestStatusType};
use Illuminate\Support\Facades\{DB};

class ArtaIdDashboardController extends Controller {
    protected $search_filter_in = 'all,Unverified,Processing,Completed,Claimed,Rejected,Removed';

    public function index(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['ARTA ID/View/All'], 'dashboard.index', [], function () use ($req) {
            $this->defaultValidationQuery($req, $this->search_filter_in);

            $index_data = Employee::query()
                ->with([
                    'extraName:id,name',
                    'files:fileable_type,fileable_id,thumbnail_url',
                    'latestRequestStatus',
                    'latestRequestStatus.requestStatusType:id,name,hero_icon_id',
                    'latestRequestStatus.requestStatusType.heroIcon:name,content',
                    'department:id,image_url,name'
                ])
                ->select('id', 'last_name', 'first_name', 'mid_name', 'extra_name_id', 'email', 'position_id', 'department_id', 'employee_status_id', 'claim_type_id', 'created_at')
                ->when($req->search_filter, function ($q) use ($req) {
                    $q->whereHas('latestRequestStatus', function ($qr) use ($req) {
                        $qr->where('request_status_type_id', RequestStatusType::where('name', $req->string('search_filter'))->first()->id);
                    });
                })
                ->when($req->start, function ($q) use ($req) {
                    $q->whereDate('created_at', '>=', $req->start);
                })
                ->when($req->end, function ($q) use ($req) {
                    $q->whereDate('created_at', '<=', $req->end);
                })
                ->when($req->search, function ($q) use ($req) {
                    $q->where(DB::raw("CONCAT(last_name, ', ', first_name)"), 'LIKE', '%' . $req->search . '%');
                })
                ->orderBy('created_at', 'DESC')
                ->paginate(20);


            return Inertia::render('dashboard/arta-id/index/(Index)', [
                'page_title' => 'QR Lists',
                'sidebar' => true,
                'index_data' => $index_data,
                'request_status_types' => $this->cachedStatusTypes(),
            ]);
        });
    }

    public function prints(Request $req) {
        return $this->checkPermissions($req, ['ARTA ID/View/All'], 'dashboard.index', [], function () use ($req) {
            $req->validate([
                'search' => ['nullable', 'string'],
                'start' => ['nullable', 'string'],
                'end' => ['nullable', 'string'],
                'search_filter' => ['nullable', 'string'],
            ]);

            $index_data = Employee::query()
                ->with(['extraName', 'files', 'requestStatuses.requestStatusType', 'department', 'position', 'employeeStatus'])
                ->when($req->search_filter, function ($q) use ($req) {
                    $q->whereHas('latestRequestStatus', function ($qr) use ($req) {
                        $statusType = RequestStatusType::where('name', $req->string('search_filter'))->first();
                        if ($statusType) {
                            $qr->where('request_status_type_id', $statusType->id);
                        }
                    });
                })
                ->when($req->start, function ($q) use ($req) {
                    $q->whereDate('created_at', '>=', $req->start);
                })
                ->when($req->end, function ($q) use ($req) {
                    $q->whereDate('created_at', '<=', $req->end);
                })
                ->when($req->search, function ($q) use ($req) {
                    $q->where(DB::raw("CONCAT(last_name, ', ', first_name)"), 'LIKE', '%' . $req->search . '%');
                })
                ->orderBy('created_at', 'DESC')
                ->get();

            $filename = 'arta-id_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $columns = [
                'ID',
                'Last Name',
                'First Name',
                'Mid Name',
                'Extra Name',
                'Email',
                'Position',
                'Department',
                'Status',
                'Claim Type',
                'Files',
                'Current Status',
                'Request Statuses Link',
                'Created At'
            ];

            $callback = function () use ($index_data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($index_data as $row) {
                    fputcsv($file, [
                        $row->id,
                        $row->last_name,
                        $row->first_name,
                        $row->mid_name,
                        $row->extraName['name'],
                        $row->email,
                        $row->position['name'],
                        $row->department['name'],
                        $row->employeeStatus['name'],
                        $row->claimType['name'],
                        implode("\n", $row->files->map(fn($item) => env('APP_URL', 'https://qr.cmuohrm.site') . $item['file_url'])->toArray()),
                        optional($row->requestStatuses->first())->requestStatusType['name'],
                        env('APP_URL', 'https://qr.cmuohrm.site') . "/arta-id/{$row->id}",
                        $row->created_at,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        });
    }

    public function show(Request $req, string $id) {
        return $this->checkPermissions($req, ['ARTA ID/View/All'], 'dashboard.index', [], function () use ($id) {
            $employee = Employee::with([
                'extraName',
                'files.fileType',
                'requestStatuses.requestStatusType.heroIcon',
                'requestStatuses.user',
                'position',
                'department',
                'employeeStatus',
                'claimType',
                'quickResponseCodes',
            ])->findOrFail($id);


            return Inertia::render('dashboard/arta-id/show/(Show)', [
                'page_title' => 'Request Status',
                'sidebar' => true,
                'request_status_types' => $this->cachedStatusTypes(),
                'show_data' => [
                    'id' => $employee['id'],
                    'last_name' => $employee['last_name'],
                    'first_name' => $employee['first_name'],
                    'mid_name' => $employee['mid_name'],
                    'extra_name' => $employee['extraName'],
                    'email' => $employee['email'],
                    'files' => $employee['files'],
                    'request_statuses' => $employee['requestStatuses'],
                    'position' => $employee['position'],
                    'department' => $employee['department'],
                    'employee_status' => $employee['employeeStatus'],
                    'claim_type' => $employee['claimType'],
                    'quick_response_codes' => $employee['quickResponseCodes']
                ],
            ]);
        });
    }

    public function update(Request $req, string $id): RedirectResponse {
        return $this->checkPermissions($req, ['ARTA ID/Update/All'], 'dashboard.arta-id.show', ['arta_id' => $id], function () use ($req, $id) {
            $req->validate([
                'type' => ['required', 'string', 'in:upload-image,delete-image,request-status'],
            ]);

            switch ($req->string('type')) {
                case 'upload-image':
                    $this->updateUploadImage($req, $id);
                    break;
                case 'delete-image':
                    $this->updateDeleteImage($req, $id);
                    break;
                case 'request-status':
                    $this->updateRequestStastus($req, $id);
            }

            return to_route('dashboard.arta-id.show', ['arta_id' => $id])
                ->with('success', ['title' => 'Request Status', 'content' => 'Successfully updated.']);
        });
    }

    private function updateDeleteImage(Request $req, string $id) {
        $req->validate([
            'id' => ['required', 'integer'],
        ]);

        $file = Employee::findOrFail($id)->files()->findOrFail($req->integer('id'));

        if (file_exists(public_path($file->file_url))) {
            unlink(public_path($file->file_url));
        }

        $file->delete();
    }

    private function updateUploadImage(Request $req, string $id) {
        $req->validate([
            'file' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
        ]);

        $path = '/uploads/arta-id/';
        $file = $req->file('file');
        $fileName = 'uploaded' . '_' . $file->getClientOriginalName();
        $location = public_path($path);

        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }

        $file->move($location, $fileName);

        $size = filesize($location . $fileName);
        [$width, $height] = getimagesize($location . $fileName);

        Employee::findOrFail($id)->files()->create([
            'file_url' => $path . $fileName,
            'file_type_id' => FileType::where('name', 'Uploaded By Staff')->first()->id,
            'size' => $size,
            'height' => $height,
            'width' => $width,
            'thumbnail_url' => $this->generateThumbnail($fileName, $path),
        ]);
    }

    private function updateRequestStastus(Request $req, string $id) {
        $req->validate([
            'request_status_type_id' => ['required'],
            'content' => ['required'],
            'notify_email' => ['required', 'boolean'],
        ]);

        $employee = Employee::findOrFail($id);

        $employee->requestStatuses()->create([
            'request_status_type_id' => $req->integer('request_status_type_id'),
            'user_id' => $req->user()->id,
            'content' => $req->string('content')
        ]);

        // TODO: email if email existed for tracking
        if ($req->boolean('notify_email')) {
            $employee->notify(new NotifyEmployeeOnRequestStatusUpdate(
                $employee->id,
                $employee->last_name . ', ' . $employee->first_name,
                $req->content,
                RequestStatusType::find($req->integer('request_status_type_id'))->name,
            ));
        }
    }

    public function edit(Request $req, string $id): Response | RedirectResponse {
        return $this->checkPermissions($req, ['ARTA ID/Update/All'], 'dashboard.arta-id.show', ['arta_id' => $id], function () use ($id) {
            $employee = Employee::with([
                'extraName',
                'files.fileType',
                'requestStatuses.requestStatusType.heroIcon',
                'requestStatuses.user',
                'position',
                'department',
                'employeeStatus',
                'claimType',
                'quickResponseCodes',
            ])->findOrFail($id);

            return Inertia::render('dashboard/arta-id/edit/(Edit)', [
                'page_title' => 'Edit Arta ID',
                'sidebar' => true,
                'request_status_types' => $this->cachedStatusTypes(),
                'show_data' => [
                    'id' => $employee['id'],
                    'last_name' => $employee['last_name'],
                    'first_name' => $employee['first_name'],
                    'mid_name' => $employee['mid_name'],
                    'extra_name' => $employee['extraName'],
                    'files' => $employee['files'],
                    'request_statuses' => $employee['requestStatuses'],
                    'position' => $employee['position'],
                    'department' => $employee['department'],
                    'employee_status' => $employee['employeeStatus'],
                    'claim_type' => $employee['claimType'],
                    'quick_response_codes' => $employee['quickResponseCodes']
                ],
            ]);
        });
    }

    public function destroy($id) {
        Employee::findOrFail($id)->delete();
    }
}
