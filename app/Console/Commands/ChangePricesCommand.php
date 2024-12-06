<?php

namespace App\Console\Commands;

use App\Models\Products;
use Illuminate\Console\Command;

class ChangePricesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-prices-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productsDB = Products::query()->get();
        $percentage = 1;

        foreach ($productsDB as $itemProduct) {
            $itemProduct->price = $percentage / 100 * $itemProduct->price + $itemProduct->price;
            $itemProduct->save();
        }
    }
}
