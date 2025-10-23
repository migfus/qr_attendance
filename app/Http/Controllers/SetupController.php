<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{File, Artisan};
use Inertia\Inertia;
use Inertia\Response;

class SetupController extends Controller {
    public function show($id): Response {
        switch ($id) {
            case 2:
                return $this->secondShow();
            case 3:
                return $this->thirdShow();
            case 4:
                return $this->fourthShow();
            case 5:
                return $this->fifthShow();
            default:
                return $this->firstShow();
        }
    }
    private function firstShow(): Response {
        return Inertia::render('setup/FirstStepPage', ['page_title' => 'Env Setup | Step 1', 'setup' => true,]);
    }
    private function secondShow(): Response {
        return Inertia::render('setup/SecondStepPage', ['page_title' => 'Setup the Look | Step 2', 'setup' => true]);
    }
    private function thirdShow(): Response {
        return Inertia::render('setup/ThirdStepPage', ['page_title' => 'Check Requirements | Step 3', 'setup' => true, 'php_requirements' => $this->checkPhpRequirements()]);
    }
    private function fourthShow(): Response {
        return Inertia::render('setup/FourthStepPage', ['page_title' => 'Select  | Step 4', 'setup' => true]);
    }
    private function fifthShow(): Response {
        return Inertia::render('setup/FifthStepPage', ['page_title' => 'Create form for new users | Step 5', 'setup' => true]);
    }

    public function store(Request $req) {
        $req->validate(['req_type' => ['required', 'in:verify_database,store_env_file']]);

        switch ($req->req_type) {
            case 'store_env_file':
                $this->storeEnvFile($req);
                break;
            default:
                $this->verifyDatabase($req);
        }
    }
    private function verifyDatabase(Request $req) {
        $req->validate([
            'host' => ['required', 'string'],
            'port' => ['required', 'integer'],
            'database' => ['required', 'string'],
            'username' => ['required', 'string'],
            'password' => ['nullable', 'string'],
        ]);

        // Attempt to connect to the database using the provided credentials
        try {
            $connection = new \PDO("mysql:host={$req->host};port={$req->port};dbname={$req->database}", $req->username, $req->password);
            return to_route('setup.show', 1)
                ->with('success', ['title' => 'Connection Successful', 'content' => 'Database Connection Successful.']);
        } catch (\PDOException $e) {
            return to_route('setup.show', 1)
                ->with(
                    'error',
                    ['title' => 'Connection Failed', 'content' => 'Database Connection Failed: ' . $e->getMessage()],
                );
        }
    }
    private function storeEnvFile(Request $req) {
        // Validate the request data
        $req->validate([
            'app_name' => ['required', 'string'],
            'app_url' => ['required', 'url'],
            'mail_mailer' => ['required', 'string'],
            'mail_host' => ['required', 'string'],
            'mail_port' => ['required', 'integer'],
            'mail_username' => ['required', 'string'],
            'mail_encryption' => ['nullable', 'string'],
            'mail_password' => ['nullable', 'string'],
            'mail_from_address' => ['required', 'email'],
            'host' => ['required', 'string'],
            'port' => ['required', 'integer'],
            'database' => ['required', 'string'],
            'username' => ['required', 'string'],
            'password' => ['nullable', 'string'],
        ]);

        $env_content = $this->buildEnvFile($req);
        $env_path = base_path('.env');

        if (File::exists($env_path)) {
            $backup_path = base_path('.env.old');
            File::copy($env_path, $backup_path);
        }

        File::put($env_path, $env_content);

        Artisan::call('key:generate', ['--force' => true]);

        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        try {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
        } catch (\Exception $e) {
            return to_route('setup.show', 1)
                ->with('error', ['title' => 'Migration Failed', 'content' => 'Running migrations/seeders failed: ' . $e->getMessage()]);
        }

        return to_route('setup.show', 2)
            ->with('success', ['title' => '.env Created', 'content' => '.env file has been created and database migrated/seeded successfully.']);
    }

    // FIXME: Don't forget to set APP_DEBUG to false in production
    private function buildEnvFile(Request $req) {

        $appKey = 'base64:' . base64_encode(random_bytes(32));

        $content = <<<ENV
            APP_NAME="QR Attendance"
            APP_ENV=local
            APP_KEY={$appKey}
            APP_DEBUG=true
            APP_TIMEZONE=UTC
            APP_URL={$req->app_url}
            VITE_URL={$req->app_url}
            APP_VER=2.0.0

            APP_LOCALE=en
            APP_FALLBACK_LOCALE=en
            APP_FAKER_LOCALE=en_US

            APP_MAINTENANCE_DRIVER=file
            APP_MAINTENANCE_STORE=database

            BCRYPT_ROUNDS=12

            LOG_CHANNEL=nightwatch
            LOG_STACK=single
            LOG_DEPRECATIONS_CHANNEL=null
            LOG_LEVEL=debug

            DB_CONNECTION=mysql
            DB_HOST={$req->host}
            DB_PORT={$req->port}
            DB_DATABASE={$req->database}
            DB_USERNAME={$req->username}
            DB_PASSWORD="{$req->password}"

            SESSION_DRIVER=file
            SESSION_LIFETIME=43200
            SESSION_EXPIRE_ON_CLOSE=false
            SESSION_ENCRYPT=false
            SESSION_PATH=/
            SESSION_DOMAIN=null

            BROADCAST_CONNECTION=reverb
            FILESYSTEM_DISK=local
            QUEUE_CONNECTION=database

            CACHE_STORE=file
            CACHE_PREFIX=

            MEMCACHED_HOST=127.0.0.1

            REDIS_CLIENT=phpredis
            REDIS_HOST=127.0.0.1
            REDIS_PASSWORD=null
            REDIS_PORT=6379

            MAIL_MAILER={$req->mail_mailer}
            MAIL_HOST={$req->mail_host}
            MAIL_PORT={$req->mail_port}
            MAIL_USERNAME={$req->mail_username}
            MAIL_PASSWORD="{$req->mail_password}"
            MAIL_ENCRYPTION={$req->mail_encryption}
            MAIL_FROM_ADDRESS={$req->mail_from_address}
            MAIL_FROM_NAME="\${APP_NAME}"

            AWS_ACCESS_KEY_ID=
            AWS_SECRET_ACCESS_KEY=
            AWS_DEFAULT_REGION=us-east-1
            AWS_BUCKET=
            AWS_USE_PATH_STYLE_ENDPOINT=false

            VITE_APP_NAME="\${APP_NAME}"

            REVERB_APP_ID=
            REVERB_APP_KEY=
            REVERB_APP_SECRET=
            # REVERB_HOST="localhost"
            REVERB_HOST="127.0.0.1"
            REVERB_PORT=8080
            REVERB_SCHEME=http

            VITE_REVERB_APP_KEY="\${REVERB_APP_KEY}"
            VITE_REVERB_HOST="\${REVERB_HOST}"
            VITE_REVERB_PORT="\${REVERB_PORT}"
            VITE_REVERB_SCHEME="\${REVERB_SCHEME}"

            FFMPEG="C:/ProgramData/chocolatey/bin/ffmpeg.exe"
            FFPROBE="C:/ProgramData/chocolatey/bin/ffprobe.exe"

            NIGHTWATCH_TOKEN=
            NIGHTWATCH_REQUEST_SAMPLE_RATE=0.1

            GEMINI_API=
        ENV;

        return $content;
    }

    private function checkPhpRequirements() {
        $requirements = [
            'bcmath',
            'ctype',
            'fileinfo',
            'json',
            'mbstring',
            'openssl',
            'pdo',
            'tokenizer',
            'xml',
            'curl',    // Added curl
            'dom',     // Added dom
            'filter',  // Added filter
            'hash',    // Added hash
            'session', // Added session,
            'gd',
            'sodium',  // Added sodium for encryption
        ];

        $results = [];
        $minPhpVersion = '8.1.0';

        foreach ($requirements as $ext) {
            $results[] = [
                'name' => $ext,
                'loaded' => extension_loaded($ext),
            ];
        }

        return [
            'php_version' => PHP_VERSION,
            'extensions' => $results,
            'php_version_ok' => version_compare(PHP_VERSION, $minPhpVersion, '>='),
            'min_php_version' => $minPhpVersion,
        ];
    }
}
