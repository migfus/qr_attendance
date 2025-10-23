<?php

namespace App\Http\Controllers;

use App\Notifications\{NewEmployeeEmail, NotifyUserOnArtaIdRequest};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{DB, Notification};
use Inertia\{Inertia, Response};

use App\Models\{
    Department,
    Employee,
    FileType,
    Position,
    RequestStatus,
    RequestStatusType,
    User
};

class ArtaIDController extends Controller {
    // TODO: OPTIMIZE THIS DEPARTMENTS & POSITIONS
    public function index(): Response {
        return Inertia::render('arta-id/index/(Index)', [
            'page_title' => 'Request for QR Code',
            'extra_names' => $this->cachedExtraNames(),
            'departments' => $this->cachedDepartments(),
            'positions' => $this->cachedPositions(),
            'statuses' => $this->cachedStatuses(),
            'claim_types' => $this->cachedClaimTypes(),
        ]);
    }

    public function store(Request $req) {
        $req->validate([
            'last_name' => ['required'],
            'first_name' => ['required'],
            'mid_name' => ['nullable'],
            'ext_name_id' => ['required', 'integer'],
            'email' => ['required', 'email'],
            'department' => ['required', 'string'],
            'position' => ['required', 'string'],
            'status_id' => ['required', 'integer'],
            'claim_id' => ['required'],
            'cropped_image' => ['required', 'file', 'mimes:jpg,jpeg', 'max:5120'],
            'raw_image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:20972'], // restrict 20mb (in case someone crazy who put beyond 20mb), will crop later
        ]);

        try {
            return DB::transaction(function () use ($req) {
                // Create Employee
                $employee = Employee::create([
                    'last_name' => $req->string('last_name'),
                    'first_name' => $req->first_name,
                    'mid_name' => $req->mid_name,
                    'extra_name_id' => $req->ext_name_id,
                    'email' => $req->email ?? null,
                    'department_id' => Department::firstOrCreate(
                        [
                            'name' => $req->string('department')
                        ],
                        [
                            'short_name' => $this->makeAbbreviation($req->string('department')),
                            'image_url' => '/images/cmu.png',
                        ]
                    )->id,
                    'position_id' => Position::firstOrCreate([
                        'name' => $req->string('position')
                    ])->id,
                    'employee_status_id' => $req->status_id,
                    'claim_type_id' => $req->claim_id,
                    'password' => str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) // generate pin (XXXX)
                ]);

                // Create QR Code for Employee
                $employee->quickResponseCodes()->create();

                // Upload and save cropped image
                $uploaded_cropped_file = $this->uploadFromCroppie($req->cropped_image, '/arta-id');

                $employee->files()->create([
                    'file_url' => $uploaded_cropped_file['image_url'],
                    'fileable_id' => $employee->id,
                    'file_type_id' => FileType::where('name', 'Cropped')->first()->id,
                    'size' => $uploaded_cropped_file['size'],
                    'width' => $uploaded_cropped_file['width'],
                    'height' => $uploaded_cropped_file['height'],
                    'thumbnail_url' => $uploaded_cropped_file['thumbnail_url']
                ]);

                // Upload and save raw image
                $file = $this->uploadFromRawImage($req->raw_image, '/arta-id', 1280);

                $employee->files()->create([
                    'file_url' => $file['image_url'],
                    'fileable_id' => $employee->id,
                    'file_type_id' => FileType::where('name', 'Raw')->first()->id,
                    'size' => $file['size'],
                    'width' => $file['width'],
                    'height' => $file['height'],
                    'thumbnail_url' => $file['thumbnail_url']
                ]);

                // Create new request status
                $employee->requestStatuses()->create([
                    'request_status_type_id' => RequestStatusType::where('name', 'Unverified')->first()->id,
                    'content' => '
                <li>You created this request.</li>
                <li>Please review the details and update any errors using the PIN from your email.</li>
                <li>Once processed, you cannot edit or delete it.</li>
                <li>Wait for verification.</li>
                <li>NOTICE: Payment will be thru upon receiving the ID from CMUEAI.</li>'
                ]);

                // NOTIFY STAFF
                $allStaff = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['admin', 'arta-manager']);
                })->get();
                Notification::send($allStaff, new NotifyUserOnArtaIdRequest($employee->id, $employee->last_name . ', ' . $employee->first_name));

                // NOTIFY EMPLOYEE
                $employee->notify(new NewEmployeeEmail($employee->id, $req->string('email'), $employee->password, $employee->last_name, $employee->first_name, 'Unverified'));

                return to_route('arta-id.show', ['arta_id' => $employee->id])
                    ->with('success', ['title' => 'ARTA ID Request', 'content' => 'Successfully requested.']);
            });
        } catch (\Throwable $e) {
            return to_route('arta-id.index')
                ->with('error', [
                    'title' => 'ARTA ID Request',
                    'content' => 'Server Error: ' . $e->getMessage()
                ]);
        }
    }

    public function show(string $id): Response | RedirectResponse {
        $employee = Employee::query()
            ->where('id', $id)
            ->with(['latestRequestStatus', 'quickResponseCodes', 'extraName', 'files', 'position', 'department', 'employeeStatus', 'claimType', 'requestStatuses.requestStatusType.heroIcon', 'requestStatuses.user',])
            ->whereHas('latestRequestStatus', function ($q) {
                $q->whereNot('request_status_type_id', RequestStatusType::where('name', 'Removed')->first()->id);
            })
            ->first();

        if (!$employee) {
            return to_route('arta-id.index')
                ->with('error', ['title' => 'Status Request', 'content' => 'Status request no longer exist.']);
        }

        return Inertia::render('arta-id/show/(Show)', [
            'page_title' => "Tracker",
            'show_data' => $employee->makeHidden(['password', 'created_at', 'updated_at'])
        ]);
    }

    public function edit(Request $req, string $id): Response | RedirectResponse {
        try {
            $req->validate([
                'pin' => ['required', 'integer']
            ]);

            $requestStatus = RequestStatus::where('employee_id', $id)->orderBy('id', 'DESC')->first();

            // TODO RESTRICT DATA
            $employee = Employee::query()
                ->where('id', $id)
                ->where('password', $req->pin)
                ->with(['quickResponseCodes', 'extraName', 'files', 'position', 'department', 'employeeStatus', 'claimType', 'requestStatuses.requestStatusType'])
                ->whereHas('requestStatuses', function ($q) {
                    $q->orderByDesc('id')
                        ->limit(1)
                        ->whereIn('request_status_type_id', [
                            RequestStatusType::where('name', 'Rejected')->first()->id,
                            RequestStatusType::where('name', 'Unverified')->first()->id
                        ]);
                })
                ->first();

            if (!$employee || $requestStatus->request_status_type_id == 6) {
                return to_route('arta-id.show', ['arta_id' => $id])
                    ->with('error', ['title' => 'PIN Code', 'content' => 'Invalid pin code']);
            }

            return Inertia::render('arta-id/edit/(Edit)', [
                'page_title' => "Edit Request",
                'extra_names' => $this->cachedExtraNames(),
                'departments' => $this->cachedDepartments(),
                'positions' => $this->cachedPositions(),
                'statuses' => $this->cachedStatuses(),
                'claim_types' => $this->cachedClaimTypes(),
                'edit_data' => $employee->makeHidden(['password', 'created_at', 'updated_at'])
            ]);
        } catch (\Exception $err) {
            return to_route('arta-id.show', ['arta_id' => $id])
                ->with('error', ['title' => 'PIN Code', 'content' => 'Invalid pin code']);
        }
    }

    // TODO: prevent update on request_status_type_id != 1
    public function update(Request $req, string $id): RedirectResponse {
        $req->validate([
            'last_name' => ['required'],
            'first_name' => ['required'],
            'mid_name' => ['nullable'],
            'ext_name_id' => ['required', 'integer'],
            'email' => ['required', 'email'],
            'department' => ['required', 'string'],
            'position' => ['required', 'string'],
            'status_id' => ['required', 'integer'],
            'claim_id' => ['required'],
            'cropped_image' => ['nullable', 'file', 'mimes:jpg,jpeg', 'max:5120'],
            'raw_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:20972'],
            'reason' => ['nullable']
        ]);

        $employee = Employee::query()
            ->with(['latestRequestStatus'])
            ->where('id', $id)
            ->whereHas('latestRequestStatus', function ($q) {
                $q->where('request_status_type_id', RequestStatusType::where('name', 'Unverified')->first()->id);
            })
            ->update([
                'last_name' => $req->string('last_name'),
                'first_name' => $req->first_name,
                'mid_name' => $req->mid_name,
                'extra_name_id' => $req->ext_name_id,
                'email' => $req->email ?? null,
                'department_id' => Department::firstOrCreate(
                    ['name' => $req->string('department')],
                    [
                        'short_name' => $this->makeAbbreviation($req->string('department')),
                    ]
                )->id,
                'position_id' => Position::firstOrCreate(
                    ['name' => $req->string('position')],
                )->id,
                'employee_status_id' => $req->status_id,
                'claim_type_id' => $req->claim_id,
            ]);

        $employee = Employee::findOrFail($id);

        if ($req->cropped_image) {
            $uploaded_cropped_file = $this->uploadFromCroppie($req->cropped_image, '/arta-id');

            $employee->files()->create([
                'file_url' => $uploaded_cropped_file['image_url'],
                'fileable_id' => $employee->id,
                'file_type_id' => FileType::where('name', 'Cropped')->first()->id,
                'size' => $uploaded_cropped_file['size'],
                'width' => $uploaded_cropped_file['width'],
                'height' => $uploaded_cropped_file['height'],
                'thumbnail_url' => $uploaded_cropped_file['thumbnail_url']
            ]);

            $file = $this->uploadFromRawImage($req->raw_image, '/arta-id', 1280);

            $employee->files()->create([
                'file_url' => $file['image_url'],
                'fileable_id' => $employee->id,
                'file_type_id' => FileType::where('name', 'Raw')->first()->id,
                'size' => $file['size'],
                'width' => $file['width'],
                'height' => $file['height'],
                'thumbnail_url' => $file['thumbnail_url']
            ]);
        }


        $employee->requestStatuses()->create([
            'request_status_type_id' => RequestStatusType::where('name', 'Unverified')->first()->id,
            'content' => '
                <li>You updated the information.</li>
                <li>Wait for verification.</li>
                <li>Reason: ' . $req->string('reason') . '</li>'
        ]);

        return to_route('arta-id.show', ['arta_id' => $id])
            ->with('success', ['title' => 'ARTA ID Request', 'content' => 'Successfully updated.']);
    }

    public function destroy(Request $req, string $id): RedirectResponse {
        $req->validate([
            'pin' => ['required'],
            'reason' => ['nullable']
        ]);

        $employee = Employee::query()
            ->with(['latestRequestStatus'])
            ->where('id', $id)
            ->where('password', $req->pin)
            ->whereHas('latestRequestStatus', function ($q) {
                $q->orderByDesc('id')
                    ->limit(1)
                    ->whereIn('request_status_type_id', [
                        RequestStatusType::where('name', 'Rejected')->first()->id,
                        RequestStatusType::where('name', 'Unverified')->first()->id
                    ]);
            })
            ->first();

        if ($employee) {
            RequestStatus::create([
                'employee_id' => $employee->id,
                'request_status_type_id' => RequestStatusType::where('name', 'Removed')->first()->id,
                'content' => '<li>You removed this request</li><li>Reason: ' . $req->string('reason') . '</li>'
            ]);
            return to_route('arta-id.index')
                ->with('success', ['title' => 'ARTA ID', 'content' => 'Successfully deleted.']);
        }

        return to_route('arta-id.show', ['arta_id' => $id])
            ->with('error', ['title' => 'PIN Code', 'content' => 'Invalid pin code']);
    }

    function makeAbbreviation($name) {
        return strtoupper(collect(explode(' ', $name))->map(fn($word) => $word[0] ?? '')->implode(''));
    }
}
