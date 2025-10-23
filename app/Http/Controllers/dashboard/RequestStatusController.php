<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use App\Notifications\NotifyEmployeeOnRequestStatusUpdate;
use Illuminate\Http\Request;

use App\Models\{Employee, RequestStatusType};

class RequestStatusController extends Controller {

    public function index(Request $req) {
        return $this->checkPermissions($req, ['Request Status/View/All'], 'dashboard.index', [], function () use ($req) {
            $req->validate([
                'sort' => ['nullable']
            ]);

            if ($req->sort != 'ASC') {
                $req->sort = 'DESC';
            }

            $employees = [
                'Unverified' => $this->indexGetEmployees('Unverified', $req->sort),
                'Processing' => $this->indexGetEmployees('Processing', $req->sort),
                'Completed' => $this->indexGetEmployees('Completed', $req->sort),
                'Claimed' => $this->indexGetEmployees('Claimed', $req->sort),
                'Rejected' => $this->indexGetEmployees('Rejected', $req->sort),
                'Removed' => $this->indexGetEmployees('Removed', $req->sort),
            ];

            return Inertia::render('dashboard/request-statuses/(Index)', [
                'page_title' => 'Request Status',
                'sidebar' => true,
                'request_status_types' => $this->cachedStatusTypes(),
                'index_data' => $employees,
            ]);
        });
    }
    private function indexGetEmployees(string $request_status_type_name, string $sort): LengthAwarePaginator {
        return Employee::query()
            ->with(['extraName', 'files', 'latestRequestStatus', 'position', 'department', 'employeeStatus'])
            ->whereHas('latestRequestStatus', function ($q) use ($request_status_type_name) {
                $q->where('request_status_type_id', RequestStatusType::where('name', $request_status_type_name)->first()->id);
            })
            ->orderBy('created_at', $sort == 'ASC' ? 'ASC' : 'DESC')
            ->paginate(10)
            ->through(fn($item) => [
                'id' => $item->id,
                'last_name' => $item->last_name,
                'first_name' => $item->first_name,
                'mid_name' => $item->mid_name,
                'extra_name' => $item->extraName,
                'position' => $item->position,
                'department' => $item->department,
                'employee_status' => $item->employeeStatus,
                'latest_request_status' => $item->latestRequestStatus,
                'files' => $item->files,
                'created_at' => $item->created_at,
                'email' => $item->email,
            ]);
    }

    public function update(Request $req, $id) {
        return $this->checkPermissions($req, ['ARTA ID/Update/All'], 'dashboard.request-statuses.index', [], function () use ($req, $id) {
            $req->validate([
                'status_type_id' => ['required'],
                'message' => ['required', 'string'],
                'send_to_email' => ['boolean'],
            ]);

            $status_type = RequestStatusType::findOrFail($req->status_type_id);

            $employee = Employee::findOrFail($id);

            $employee->requestStatuses()->create([
                'request_status_type_id' => $status_type->id,
                'content' => $req->string('message'),
                'user_id' => $req->user()->id,
            ]);

            if ($req->boolean('send_to_email')) {
                $employee->notify(new NotifyEmployeeOnRequestStatusUpdate(
                    $employee->id,
                    $employee->last_name . ', ' . $employee->first_name,
                    $req->string('message'),
                    RequestStatusType::find($req->integer('status_type_id'))->name,
                ));
            }



            return to_route('dashboard.request-statuses.index')
                ->with('success', ['title' => 'ARTA ID Request', 'content' => 'Successfully updated.']);
        });
    }
}
