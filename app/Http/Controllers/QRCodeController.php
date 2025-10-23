<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, RedirectResponse};
use Inertia\{Response, Inertia};
use Symfony\Component\Uid\Ulid;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Cache, Cookie, DB, Notification};

use App\Models\{Department, Employee, GuestQrCode, QuickResponse, User};

class QRCodeController extends Controller {

    public function index(Request $req): Response {
        $guest_id = $req->cookie('guest_id');
        if (!$guest_id) {
            $guest_id = Ulid::generate();
            Cookie::queue(Cookie::make('guest_id', $guest_id, 60 * 24 * 365));
        }

        $index_data = GuestQrCode::query()
            ->where('guest_id', $guest_id)
            ->with(['department', 'status'])
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return Inertia::render('guest-qr/(Index)', [
            'page_title' => 'Request for QR Code',
            'employee_statuses' => $this->cachedStatuses(),
            'departments' => $this->cachedDepartments(),
            'index_data' => $index_data,
            'guest_id' => $guest_id
        ]);
    }

    public function store(Request $req): RedirectResponse {
        $req->validate([
            'type' => ['required', 'in:new,clear-all']
        ]);

        switch ($req->type) {
            case 'new':
                return $this->storeNew($req);
                break;
            default: // clear-all
                return $this->storeClearAll();
        }
    }

    public function destroy(string $id): RedirectResponse {
        GuestQrCode::findOrFail($id)->delete();

        return to_route('guest-qr-codes.index')
            ->with('success', ['title' => 'Guest QR Code', 'content' => 'QR Code removed']);
    }

    public function redirect(string $id): RedirectResponse {
        $qr = QuickResponse::findOrFail($id);
        $employee = Employee::where('id', $qr['employee_id'])->with(['extraName', 'department', 'employeeStatus'])->first();

        if ($employee) {
            $link = "https://docs.google.com/forms/d/e/1FAIpQLSfEyt4wDRr_M1LNCIznXIDC6bP_EMvNT1yCBfHpMmcaIWB5sQ/viewform?usp=pp_url&entry.1305592044="
                . urlencode($this->completeName($employee)) .
                "&entry.1578594241="
                . urlencode($employee['department']['short_name'] . " - " . $employee['department']['name']) .
                "&entry.560156985="
                . urlencode($employee['employeeStatus']['name']);

            return redirect()->away($link);
        }

        return to_route('guest-qr-codes.index');
    }

    // SECTION: OTHER FUNCTIONS

    private function storeClearAll(): RedirectResponse {
        return to_route('guest-qr-codes.index')
            ->with('success', ['title' => 'Guest QR Code', 'content' => 'QR Code has been cleared.'])
            ->cookie('guest_id', '', 60 * 24 * 365);
    }
    private function storeNew(Request $req): RedirectResponse {
        $req->validate([
            'name' => ['required', 'min:8'],
            'status_id' => ['required'],
            'department_id' => ['required']
        ]);

        $attempts = 0;
        $maxAttempts = 5;

        do {
            try {
                $guest_id = $this->getQRId($req->string('name'));

                DB::transaction(function () use ($req, $guest_id) {
                    $guestQr = GuestQrCode::query()
                        ->create([
                            'id' => $guest_id,
                            'guest_id' => $req->cookie('guest_id'),
                            'name' => $req->name,
                            'status_id' => $req->status_id,
                            'department_id' => $req->department_id,
                        ]);

                    $allStaff = User::query()
                        ->whereHas('roles', function ($q) {
                            $q->whereIn('name', ['admin']);
                        })
                        // ->whereHas('user_settings', function ($q) {
                        //     $q->where('config->notification->email->registered', true);
                        // })
                        ->get();

                    Notification::send($allStaff, new NewUserNotification($guestQr));
                });

                break; // Exit loop if successful
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() !== '23000') { // 23000 is the SQLSTATE for integrity constraint violation
                    throw $e;
                }
                $attempts++;
                if ($attempts >= $maxAttempts) {
                    throw new \Exception('Failed to generate a unique ID after multiple attempts.');
                }
            }
        } while ($attempts < $maxAttempts);

        return to_route('guest-qr-codes.index')
            ->with('success', ['title' => 'Guest QR Code', 'content' => 'New QR code created.']);
    }
    private function departments(): Collection {
        return Cache::remember('departments', 60 * 60, function () {
            return  Department::query()
                ->orderBy('name', 'ASC')
                ->get()
                ->map(function ($row) {
                    return [
                        'id' => $row['id'],
                        'name' =>  $row['name'],
                    ];
                });
        });
    }
    private function completeName($employee): string {
        $mid = $employee['mid_name'] ? $employee['mid_name'] . ". " : '';
        $ext = $employee['extraName']['name'] == 'N/A' ? '' : $employee['extraName']['name'];
        return $employee->last_name . ", " . $employee->first_name . " " . $mid . " " . $ext;
    }
}
