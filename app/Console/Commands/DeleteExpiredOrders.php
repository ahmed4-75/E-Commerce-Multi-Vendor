<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteExpiredOrders extends Command
{
        /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Order::query()->where('status', 'pending')->where('created_at','<', now()->subDays(2))->with(['products' => fn ($query) => $query->withTrashed()])
        ->chunkById(50, function ($orders) {
            foreach ($orders as $order) {
                DB::transaction(function () use ($order) {
                    foreach ($order->products as $product) {
                        $product->increment('quantity',$product->item->quantity,[]);
                    }
                });
                Order::destroy($order->id);
            }
        });

        $this->info('Expired orders deleted successfully.');
    }
}
