<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Task;

class TaskStatusChanged
{
    use SerializesModels;

    public $task;
    public $oldStatus;

    public function __construct(Task $task, $oldStatus)
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
    }
}
