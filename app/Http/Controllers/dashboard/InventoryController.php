<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Inventory;

class InventoryController extends Controller {
    protected $search_filter_in = 'Administrator,Manager,Scanner,Member';

    public function index(Request $req) {
        $this->defaultValidationQuery($req, $this->search_filter_in);

        $index_data = Inventory::query()
            ->with(['employees'])
            ->when($req->search_filter, function ($q) use ($req) {
                $q->whereHas('employees', function ($query) use ($req) {
                    $query->where('display_name', $req->search_filter);
                });
            })
            ->paginate(20);


        return Inertia::render('dashboard/galleries/(Index)', [
            'page_title' => 'Galleries',
            'sidebar' => true,
            'index_data' => $index_data
        ]);
    }
}
