<?php

namespace App\Jobs;

use App\Models\OrdersProducts;
use App\Models\Products;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckAvailableJob implements ShouldQueue
{
    use Batchable, Queueable;

    private $order_id;

    /**
     * Create a new job instance.
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orderProductsDB = OrdersProducts::query()->where('order_id', $this->order_id)->pluck('product_id');

        $productsDB = Products::query()->with(['stock'])->whereIn('id', $orderProductsDB)->get();

        foreach ($productsDB as $itemProduct) {
            if($itemProduct['stock']['quantity'] == 0){
                $itemProduct->available = 'not';
                $itemProduct->save();
            }
        }
    }
}
