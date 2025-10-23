<?php

namespace App\Events;

use Illuminate\Broadcasting\{InteractsWithSockets, Channel};
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateGroupPostsEvent implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function broadcastOn(): array {
        return [
            new Channel('group-posts-update.' . $this->data['groupId']),
        ];
    }
}
