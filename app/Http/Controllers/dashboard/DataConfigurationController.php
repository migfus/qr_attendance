<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\{Request, RedirectResponse};
use Inertia\{Inertia, Response};
use Illuminate\Support\Facades\Cache;

use App\Models\{Department, EmployeeStatus, ExtraName, Position};

class DataConfigurationController extends Controller {
    protected $models = [
        'Employee Status' => EmployeeStatus::class,
        'Extra Name' => ExtraName::class,
        'Position' => Position::class,
    ];

    public function index(Request $req): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Data Configuration/View/All'], 'dashboard.index', [], function () use ($req) {
            $data = collect($this->models)->mapWithKeys(function ($model, $key) {
                if ($key == 'Extra Name' || $key == 'Position') {
                    return [
                        $key => $model::query()
                            ->withCount('employees')
                            ->orderBy('employees_count', 'DESC')
                            ->paginate(5)
                            ->through(fn($item) => [
                                'id' => $item->id,
                                'name' => $item->name,
                                'count' => $item->employees_count
                            ])
                    ];
                }
                return [
                    $key => $model::query()
                        ->withCount('guestQrCode')
                        ->orderBy('guest_qr_code_count', 'DESC')
                        ->paginate(5)
                        ->through(fn($item) => [
                            'id' => $item->id,
                            'name' => $item->name,
                            'count' => $item->guest_qr_code_count
                        ])
                ];
            });

            return Inertia::render('dashboard/data-configurations/(Index)', [
                'page_title' => 'Data Configuration',
                'sidebar' => true,
                'index_data' => $data
            ]);
        });
    }

    public function show(Request $req, string $model_name): Response | RedirectResponse {
        return $this->checkPermissions($req, ['Data Configuration/View/All'], 'dashboard.data-configurations.index', [], function () use ($req, $model_name) {
            if (!isset($this->models[$model_name])) {
                return to_route('dashboard.data-configurations.index')
                    ->with('error', ['title' => 'Data Configuration', 'content' => 'Invalid Link']);
            }

            $model = $this->models[$model_name];
            $data;

            if ($model_name == 'Extra Name'  || $model_name == 'Position') {
                $data = $model::query()
                    ->withCount('employees')
                    ->orderBy('employees_count', 'DESC')
                    ->get()
                    ->map(fn($item) => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'count' => $item->employees_count
                    ]);
            } else {
                $data = $model::query()
                    ->withCount('guestQrCode')
                    ->orderBy('guest_qr_code_count', 'DESC')
                    ->get()
                    ->map(fn($item) => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'count' => $item->guest_qr_code_count
                    ]);
            }

            return Inertia::render('dashboard/data-configurations/(Show)', [
                'page_title' => 'Data Configuration',
                'sidebar' => true,
                'show_data' => $data
            ]);
        });
    }

    public function store(Request $req): RedirectResponse {
        return $this->checkPermissions($req, ['Data Configuration/Create/All'], 'dashboard.data-configurations.show', ['data_configuration' => $req->model_name], function () use ($req) {
            $req->validate([
                'model_name' =>  ['required', 'string', 'in:' . implode(',', array_keys($this->models))],
                'name' =>  ['required', 'string'],
            ]);

            $model = $this->models[$req->model_name];
            $model::create(['name' => $req->name]);

            Cache::forget('extra_names');
            Cache::forget('positions');
            Cache::forget('statuses');

            return to_route('dashboard.data-configurations.show', ['data_configuration' => $req->model_name]);
        });
    }

    public function update(Request $req, string $model_name): RedirectResponse {
        return $this->checkPermissions($req, ['Data Configuration/Update/All'], 'dashboard.data-configurations.show', ['data_configuration' => $model_name], function () use ($req, $model_name) {
            if (!isset($this->models[$model_name])) {
                return to_route('dashboard.data-configurations.index')
                    ->with('error', ['title' => 'Data Configuration', 'content' => 'Invalid Link']);
            }

            $req->validate([
                'id' => ['required', 'exists:' . $this->models[$model_name] . ',id'],
                'name' => ['required', 'string'],
            ]);

            $model = $this->models[$model_name];
            $item = $model::findOrFail($req->id);

            $item->update(['name' => $req->name]);

            Cache::forget('extra_names');
            Cache::forget('positions');
            Cache::forget('statuses');

            return to_route('dashboard.data-configurations.show', ['data_configuration' => $model_name]);
        });
    }

    public function destroy(Request $req, string $model_name): RedirectResponse {
        return $this->checkPermissions($req, ['Data Configuration/Delete/All'], 'dashboard.data-configurations.show', ['data_configuration' => $model_name], function () use ($req, $model_name) {
            if (!isset($this->models[$model_name])) {
                return to_route('dashboard.data-configurations.index')
                    ->with('error', ['title' => 'Data Configuration', 'content' => 'Invalid Link']);
            }

            $req->validate([
                'id' => ['required', 'exists:' . $this->models[$model_name] . ',id'],
            ]);

            $model = $this->models[$model_name];
            $model::destroy($req->id);

            return to_route('dashboard.data-configurations.show', ['data_configuration' => $model_name]);
        });
    }
}
