<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['sale', 'user'])->get();
        return response()->json($payments);
    }

    public function create()
    {
        // Return form view for creating payment
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'payment_method' => 'required|in:cash,card,transfer,ewallet,loyalty', // DIPERBAIKI: Tambahkan 'loyalty'
            'amount' => 'required|numeric|min:0',
            'reference_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'payment_date' => 'nullable|date',
            'status' => 'in:pending,completed,failed',
            'user_id' => 'nullable|exists:users,id'
        ]);

        // Set default payment_date if not provided
        if (!isset($validated['payment_date'])) {
            $validated['payment_date'] = now();
        }

        $payment = Payment::create($validated);

        return response()->json([
            'message' => 'Payment created successfully',
            'data' => $payment->load(['sale', 'user'])
        ], 201);
    }

    public function show(Payment $payment)
    {
        return response()->json($payment->load(['sale', 'user']));
    }

    public function edit(Payment $payment)
    {
        // Return edit form view
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card,transfer,ewallet,loyalty',
            'amount' => 'required|numeric|min:0',
            'reference_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'payment_date' => 'nullable|date',
            'status' => 'in:pending,completed,failed'
        ]);

        $payment->update($validated);

        return response()->json([
            'message' => 'Payment updated successfully',
            'data' => $payment->load(['sale', 'user'])
        ]);
    }

    public function destroy(Payment $payment)
    {
        // Only allow deletion of pending or failed payments
        if ($payment->status === 'completed') {
            return response()->json([
                'message' => 'Cannot delete completed payment'
            ], 422);
        }

        $payment->delete();

        return response()->json([
            'message' => 'Payment deleted successfully'
        ]);
    }

    // Additional methods
    public function getBySale($saleId)
    {
        $payments = Payment::where('sale_id', $saleId)
            ->with(['user'])
            ->get();
        
        return response()->json($payments);
    }

    public function getTotalPayments($saleId)
    {
        $total = Payment::where('sale_id', $saleId)
            ->where('status', 'completed')
            ->sum('amount');
        
        return response()->json(['total' => $total]);
    }
}