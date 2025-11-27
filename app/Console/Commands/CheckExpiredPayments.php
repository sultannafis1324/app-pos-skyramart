<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckExpiredPayments extends Command
{
    protected $signature = 'payments:check-expired';
    protected $description = 'Check and update expired payments';

    public function handle()
    {
        $this->info('Checking for expired payments...');
        
        DB::beginTransaction();
        try {
            $expiredPayments = Payment::expired()->with('sale')->get();
            
            $count = 0;
            foreach ($expiredPayments as $payment) {
                // Update payment to failed
                $payment->update(['status' => 'failed']);
                
                // Update sale to cancelled
                if ($payment->sale && $payment->sale->status === 'pending') {
                    $payment->sale->update(['status' => 'cancelled']);
                }
                
                $count++;
                
                Log::info('Auto-cancelled expired payment', [
                    'payment_id' => $payment->id,
                    'sale_id' => $payment->sale_id,
                    'order_id' => $payment->midtrans_order_id
                ]);
            }
            
            DB::commit();
            
            $this->info("Successfully updated {$count} expired payments.");
            
            return 0;
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Failed to check expired payments: ' . $e->getMessage());
            Log::error('Command failed: ' . $e->getMessage());
            
            return 1;
        }
    }
}