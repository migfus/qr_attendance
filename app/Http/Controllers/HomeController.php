<?php

namespace App\Http\Controllers;

use Inertia\{Response, Inertia};
use Symfony\Component\Uid\Ulid;

use App\Models\{EmployeeStatus, Department};

class HomeController extends Controller {

    public function index(): Response {
        return Inertia::render('home/(Index)', [
            'page_title' => 'Home',
            'employee_statuses' =>
            EmployeeStatus::query()
                ->orderBy('name', 'ASC')
                ->get()
                ->map(function ($row) {
                    return [
                        'id' => $row['id'],
                        'name' =>  $row['name'],
                    ];
                }),
            'departments' =>
            Department::query()
                ->orderBy('name', 'ASC')
                ->get()
                ->map(function ($row) {
                    return [
                        'id' => $row['id'],
                        'name' =>  $row['name'],
                    ];
                }),
            'guest_id' => Ulid::generate(),
        ]);
    }
}
