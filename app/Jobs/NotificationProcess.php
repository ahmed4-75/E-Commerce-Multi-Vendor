<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Notifications\NewOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class NotificationProcess implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $type,
        public int $id)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->type == 'newProduct'){

            Log::info('NotificationProcess job started for new Product');
            User::select('id')
                ->chunkById(2, function ($users) {
                    SendNotificationChunk::dispatch($users->pluck('id')->toArray(), $this->id)->onQueue('NewProductNotifications');
                    Log::info('Dispatched SendNotificationChunk job for user IDs: ' . implode(', ', $users->pluck('id')->toArray()));
                }
            );
        }elseif($this->type == 'newOrder'){
            $order = Order::query()->findOrFail($this->id);
            $user = User::query()->findOrFail($order->user_id);
            Log::info('NotificationProcess job started for new Order');
            $user->notify(new NewOrderNotification($order->id));
            Log::info('Notification sent to user ID: ' . $user->id);
        }
    }
}
