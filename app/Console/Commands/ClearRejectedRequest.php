<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * @property string $signature
 * @property string description
 *
 * @method void handle()
 */

class ClearRejectedRequest extends Command
{
    protected $signature = 'cron:clear-rejected-request';

    protected $description = 'This command will remove rejected & pending request. It will clear up the database and file uploads every month.';

    public function handle() {
        Log::info('Clearing the requests now');
    }
}
