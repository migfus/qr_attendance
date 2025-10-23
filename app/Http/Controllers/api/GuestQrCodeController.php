<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\{Request, RedirectResponse, JsonResponse};

use App\Models\GuestQrCode;

class GuestQrCodeController extends Controller {
    public function index(Request $req): JsonResponse {
        if ($req->guest_id) {
            $guest = GuestQrCode::where('guest_id', $req->guest_id)->with(['department', 'status'])->orderBy('created_at', 'DESC')->get();
            return response()->json($guest);
        }
        return response()->json(false);
    }

    public function store(Request $req): JsonResponse {
        $req->validate([
            'guest_id' => ['required'],
            'name' => ['required'],
            'status_id' => ['required'],
            'department_id' => ['required'],
        ]);

        $newQrguest = GuestQrCode::firstOrCreate([
            'guest_id' => $req->guest_id,
            'name' => strtoupper($req->name),
            'status_id' => $req->status_id,
            'department_id' => $req->department_id
        ]);

        $guest = GuestQrCode::where('id', $newQrguest->id)->with(['department', 'status'])->first();

        return response()->json($guest);
    }

    public function destroy($id): JsonResponse {
        GuestQrCode::find($id)->delete();

        return response()->json(true);
    }

    public function show($name): RedirectResponse {
        $guest = GuestQrCode::withTrashed()->where('id', $name)->with(['department', 'status'])->first();

        if ($guest) {
            $link = "https://docs.google.com/forms/d/e/1FAIpQLSfEyt4wDRr_M1LNCIznXIDC6bP_EMvNT1yCBfHpMmcaIWB5sQ/viewform?usp=pp_url&entry.1305592044="
                . urlencode($guest['name']) .
                "&entry.1578594241="
                . urlencode($guest['department']['short_name'] . " - " . $guest['department']['name']) .
                "&entry.560156985="
                . urlencode($guest['status']['name']);

            return redirect()->away($link);
        }

        return to_route('index');
    }
}
