<?php

namespace App\Observers;

use App\Models\Sale;
use App\Models\Customer;

class SaleObserver
{
    public function created(Sale $sale): void
    {
        if ($sale->customer) {
            $sale->customer->increment('loyalty_points', floor($sale->total_price / 100));
        }
    }

    public function updated(Sale $sale): void
    {
        if ($sale->customer) {
            $originalTotal = $sale->getOriginal('total_price');
            $newTotal = $sale->total_price;

            $diff = floor($newTotal / 100) - floor($originalTotal / 100);
            if ($diff !== 0) {
                $sale->customer->increment('loyalty_points', $diff);
            }
        }
    }

    public function deleted(Sale $sale): void
    {
        if ($sale->customer) {
            $sale->customer->decrement('loyalty_points', floor($sale->total_price / 100));
        }
    }
}
