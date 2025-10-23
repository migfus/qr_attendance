<?php

namespace App\Events;

use Illuminate\Broadcasting\{InteractsWithSockets, PrivateChannel};
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MakeGroupTaskEvent implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function broadcastOn(): array {
        return [
            new PrivateChannel('group.', $this->data['id']), // group_id
        ];
    }
}
