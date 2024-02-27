<?php
 
namespace App\Events;
 
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
 
class SendMails
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
    /**
     * The user instance.
     * The details array.
     * The listeners array which need to fire.
     *
     * @var \App\Models\User
     */
    public $user;
    public $details;
    public $listener;
 
    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User  $user | string $user
     * @param  array  $details
     * @param  array  $listener
     * @return void
     */
    public function __construct($user, array $details, array $listener)
    {
        $this->user = $user;
        $this->details = $details;
        $this->listener = $listener;
    }
}