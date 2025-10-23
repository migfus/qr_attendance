<?php

namespace App\Events;

use Illuminate\Broadcasting\{Channel, InteractsWithSockets};
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostsEvent implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $postId;
    public $data;
    public $type;

    public function __construct($type, $data) {
        $this->data = $data;
        $this->type = $type;
    }

    public function broadcastOn(): array {
        return [
            new Channel('posts'),
        ];
    }
}
