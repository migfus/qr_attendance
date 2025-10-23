<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

class ArtaIDVerificationController extends Controller {
    public function store(Request $req) {
        $req->validate([
            'step' => ['required', 'integer']
        ]);

        switch ($req->step) {
            case 1:
                return $this->storeFirstStep($req);
                break;
            case 2:
                return $this->storeSecondStep($req);
                break;
        }
    }
    private function storeFirstStep(Request $req) {
        $req->validate([
            'last_name' =>  ['required', 'string'],
            'first_name' => ['required', 'string'],
            'mid_name' =>   ['nullable', 'string'],
            'ext_name_id' => ['required', 'integer'],
            'email' => ['required', 'email']
        ]);

        return response()->json(true);
    }
    private function storeSecondStep(Request $req) {
        $req->validate([
            'department' =>  ['required', 'string'],
            'position' =>    ['required', 'string'],
            'status_id' =>      ['required', 'integer', 'min:1'],
        ]);

        return response()->json(true);
    }
}
