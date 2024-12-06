<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrdersProducts;
use App\Models\Products;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class AddOrderJob implements ShouldQueue
{
    use Batchable, Queueable;

    public $products;
    public $client_id;


    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->products = $data['products'];
        $this->client_id = $data['client_id'];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            $orderDB = Order::query()->with(['items'])->create(['client_id' => $this->client_id]);

            foreach ($this->products as $itemData) {
                $productDB = Products::query()->findOrFail($itemData['product_id']);

                OrdersProducts::query()->create([
                    'order_id' => $orderDB['id'],
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'price' => $productDB['price'],
                    'total_price' => $itemData['quantity'] * $productDB['price']
                ]);

                $productDB->decrement('quantity', $itemData['quantity']);

                if($productDB['quantity'] == 0) {
                    $productDB->update(['availability' => 'not']);
                }
            }
            $orderDB->update(['total_price' => $orderDB['items']->sum('total_price')]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
        }
    }

}
