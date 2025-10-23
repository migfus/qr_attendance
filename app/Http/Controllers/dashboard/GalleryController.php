<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\{File, Employee};

class GalleryController extends Controller {
    protected $search_filter_in = 'All,Cropped,Raw,Saved from Editor,Uploaded By Staff';

    public function index(Request $req) {
        return $this->checkPermissions($req, ['Gallery/View/All'], 'dashboard.index', [], function () use ($req) {
            $this->defaultValidationQuery($req, $this->search_filter_in);

            $search = $req->string('search', null);

            $file = File::query()
                ->when($req->search_filter, function ($q) use ($req) {
                    $q->whereHas('fileType', function ($q) use ($req) {
                        $q->where('name', $req->search_filter);
                    });
                })
                ->with([
                    'fileType',
                    'fileable' => function ($morphTo) {
                        $morphTo->morphWith([
                            Employee::class => ['extraName'],
                        ]);
                    }
                ]);

            if ($search) {
                $file->whereHasMorph(
                    'fileable',
                    [Employee::class],
                    function ($q) use ($search) {
                        $q->where(function ($sub) use ($search) {
                            $sub->whereRaw("CONCAT(last_name, ', ', first_name) LIKE ?", ["%{$search}%"]);
                        });
                    }
                );
            }


            return Inertia::render('dashboard/galleries/(Index)', [
                'page_title' => 'Galleries',
                'sidebar' => true,
                'index_data' => $file->orderBy('created_at', 'DESC')->paginate(20)->through(fn($item) => [
                    'id' => $item->id,
                    'file_url' => $item->file_url,
                    'size' => $item->size,
                    'width' => $item->width,
                    'height' => $item->height,
                    'thumbnail_url' => $item->thumbnail_url,
                    'created_at' => $item->created_at,
                    'file_type' => $item->fileType,
                    'fileable' => $item->fileable,
                ])
            ]);
        });
    }
}
