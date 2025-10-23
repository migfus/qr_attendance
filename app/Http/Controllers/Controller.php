<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Symfony\Component\Uid\Ulid;

use App\Models\{
    ClaimType,
    Department,
    EmployeeStatus,
    ExtraName,
    Position,
    RequestStatusType,
    Role
};

abstract class Controller {
    protected $base_upload_path = '/uploads';

    public function uploadFromCroppie($image_file, $relative_path = '/arta-id', $generate_thumbnail = true) {


        $file_extension = $image_file->getClientOriginalExtension();
        $image_name = 'cropped_' . Ulid::generate(now()) . '.' . $file_extension;
        $location = $this->base_upload_path . $relative_path;
        if (!file_exists(public_path($location))) {
            mkdir(public_path($location), 0777, true);
        }
        $image_file->move(public_path($location), $image_name);

        $image_saved_path = public_path("$location/$image_name");
        [$width, $height] = getimagesize($image_saved_path);

        $out = [
            'thumbnail_url' => $generate_thumbnail ? $this->generateThumbnail($image_name, $location) : null,
            'image_url' => "$location/$image_name",
            'size' => filesize($image_saved_path),
            'width' => $width,
            'height' => $height,
        ];

        return $out;
    }

    public function uploadFromRawImage($image_file, $relative_path = '/arta-id', $max_dimension_pixel_size = 1280) {
        $manager = new ImageManager(new GdDriver());

        $file_extension = $image_file->getClientOriginalExtension();
        $image_name = 'raw_' . Ulid::generate(now()) . '.' . $file_extension;
        // NOTE: Checks location if exists
        $location = $this->base_upload_path . $relative_path;
        if (!file_exists(public_path($location))) {
            mkdir(public_path($location), 0777, true);
        }

        $image_read = $manager->read($image_file->getRealPath());

        $width = $image_read->width();
        $height = $image_read->height();
        if ($width > $max_dimension_pixel_size || $height > $max_dimension_pixel_size) {
            if ($width >= $height) {
                $image_read->resize($max_dimension_pixel_size, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $image_read->resize(null, $max_dimension_pixel_size, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        $image_read->save(public_path("$location/$image_name"));
        $saved_file_size = filesize(public_path("$location/$image_name"));
        $width = $image_read->width();
        $height = $image_read->height();

        $out = [
            'thumbnail_url' => $this->generateThumbnail($image_name, $location),
            'image_url' => "$location/$image_name",
            'size' => $saved_file_size,
            'width' => $width,
            'height' => $height,
        ];

        return $out;
    }

    public function checkPermissions(Request $req, $permissions, $to_route = 'dashboard.index', $params = [], $callback) {
        $restrict = true;
        $user = $req->user();
        foreach ($permissions as $perm) {
            if ($user->hasPermission($perm)) {
                $restrict = false;
            }
        }

        if ($restrict) {
            return to_route($to_route, $params)
                ->with(['error' => ['title' => 'Permission', 'content' => 'You don\'t have a permission to access this function or page.']]);
        }
        return $callback();
    }

    public function defaultValidationQuery(Request $req, $search_filters = 'all') {
        $req->validate([
            'search' => ['nullable', 'string'],
            'start' => ['nullable', 'string'],
            'end' => ['nullable', 'string'],
            'search_filter' => ['nullable', 'string', "in:{$search_filters}"],
        ]);
    }

    // LINK: https://stackoverflow.com/questions/4356289/php-random-string-generator
    public function generateRandomString($length = 5): String {
        // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function generateThumbnail(string $image_name, string $location) {
        $manager = new ImageManager(new GdDriver());

        $thumbnail_name = 'thumb_' . Ulid::generate(now()) . '.jpg';

        $manager->read(public_path("$location/$image_name"))
            ->cover(300, 300) // Crop + resize to 300x300
            ->save(public_path("$location/$thumbnail_name"));

        return "/uploads/arta-id/{$thumbnail_name}";
    }

    public function getQRId(string $name, int $max_chars = 3): String {
        return collect(explode(' ', $name))
            ->take($max_chars) // Limit to the first 3 parts of the name
            ->map(fn($part) => strtoupper(substr($part, 0, 1))) // Take only the first character of each part
            ->join('') . '-' . Str::random(8);

        return $id;
    }

    // SECTION CACHED
    public function cachedRoles($ttl_minutes = 60) {
        return Cache::remember('roles', $ttl_minutes * 60, function () {
            return Role::get()->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->display_name,
                ];
            });
        });
    }
    public function cachedDepartments($ttl_minutes = 1) {
        return Cache::remember('departments', $ttl_minutes * 60, function () {
            return Department::orderBy('short_name', 'ASC')->get()->map(fn($item) => [
                'id' => $item['id'],
                'name' => $item['name'],
                'short_name' => $item['short_name'],
                'image_url' => $item['image_url']
            ]);
        });
    }
    public function cachedClaimTypes($ttl_minutes = 60) {
        return Cache::remember('claim_types', $ttl_minutes  * 60, function () {
            return ClaimType::get()->map(fn($item) => [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'image_url' => $item['image_url']
            ]);
        });
    }
    public function cachedStatuses($ttl_minutes = 60) {
        return Cache::remember('statuses', $ttl_minutes * 60, function () {
            return EmployeeStatus::get()->map(fn($item) => [
                'id' => $item['id'],
                'name' => $item['name']
            ]);
        });
    }
    public function cachedPositions($ttl_minutes = 1) {
        return Cache::remember('positions', $ttl_minutes * 60, function () {
            return Position::get()->map(fn($item) => [
                'id' => $item['id'],
                'name' => $item['name']
            ]);
        });
    }
    public function cachedExtraNames($ttl_minutes = 60) {
        return Cache::remember('extra_names', $ttl_minutes * 60, function () {
            return ExtraName::get()->map(fn($item) => [
                'id' => $item['id'],
                'name' => $item['name']
            ]);
        });
    }
    public function cachedStatusTypes($ttl_minutes = 60) {
        return Cache::remember('request_status_types', $ttl_minutes * 60, function () {
            return RequestStatusType::with(['heroIcon'])->get()->map(fn($item) => [
                'id' => $item['id'],
                'name' => $item['name'],
                'hero_icon' => $item['heroIcon']
            ]);
        });
    }
}
