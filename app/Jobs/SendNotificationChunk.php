<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Notifications\NewProductNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendNotificationChunk implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $userIds,
        public int $productId)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('SendNotificationChunk job started for user IDs: ' . implode(', ', $this->userIds));
        $product = Product::query()->findOrFail($this->productId);
        $users = User::query()->whereKey($this->userIds)->get();
        foreach ($users as $user) {
            sleep(1);
            $user->notify(new NewProductNotification($product->id));
            Log::info('Notification sent to user ID: ' . $user->id);
        }
    }
}
