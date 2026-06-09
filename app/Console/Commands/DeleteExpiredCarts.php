<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carts:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired carts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Cart::query()->where('created_at','<', now()->subDays(2))->with(['products' => fn ($query) => $query->withTrashed()])
        ->chunkById(50, function ($carts) {
            foreach ($carts as $cart) {
                DB::transaction(function () use ($cart) {
                    foreach ($cart->products as $product) {
                        $product->increment('quantity',$product->item->quantity,[]);
                    }
                });
                Cart::destroy($cart->id);
            }
        });

        $this->info('Expired carts deleted successfully.');
    }
}
