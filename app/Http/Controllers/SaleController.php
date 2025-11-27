<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleDetail;
use App\Models\StockHistory;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Payment;
use App\Models\PromotionUsage;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function index(Request $request)
{
    try {
        $this->checkAndCancelExpiredPayments();

        $query = Sale::with(['customer', 'user', 'saleDetails.product']);

        // ✅ FILTER BERDASARKAN ROLE USER
        $user = auth()->user();
        
        // Jika bukan admin, hanya tampilkan sales yang dibuat oleh user tersebut
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('customer_name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(20);

        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json($sales);
        }

        return view('sales.index', compact('sales'));
    } catch (\Exception $e) {
        Log::error('Failed to fetch sales: ' . $e->getMessage());
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'message' => 'Failed to fetch sales: ' . $e->getMessage()
            ], 500);
        }
        return view('sales.index', ['sales' => collect()]);
    }
}

    public function apiIndex(Request $request)
{
    try {
        $query = Sale::with(['customer', 'user', 'saleDetails.product']);

        // ✅ FILTER BERDASARKAN ROLE USER
        $user = auth()->user();
        
        // Jika bukan admin, hanya tampilkan sales yang dibuat oleh user tersebut
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Apply filters jika ada
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }

        // Return array langsung, bukan paginated
        $sales = $query->orderBy('created_at', 'desc')->get();

        return response()->json($sales);
    } catch (\Exception $e) {
        Log::error('Failed to fetch sales for API: ' . $e->getMessage());
        return response()->json([
            'error' => true,
            'message' => 'Failed to fetch sales: ' . $e->getMessage()
        ], 500);
    }
}

    public function create()
    {
        try {
            $products = Product::with('category')->where('stock', '>', 0)->where('is_active', true)->get();
            $customers = Customer::active()->get();
            $categories = Category::all();

            return view('sales.create', compact('products', 'customers', 'categories'));
        } catch (\Exception $e) {
            Log::error('Failed to load create page: ' . $e->getMessage());
            return redirect()->route('sales.index')->with('error', 'Failed to load create page: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
{
    Log::info('Sale Store Request with Multi-Promo:', $request->all());

    // VALIDATION
    $rules = [
        'transaction_number' => 'required|string|max:255|unique:sales',
        'sale_date' => 'required|date',
        'customer_id' => 'nullable|exists:customers,id',
        'user_id' => 'required|exists:users,id',
        'subtotal' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'discount_percentage' => 'nullable|numeric|min:0',
        'tax' => 'nullable|numeric|min:0',
        'total_price' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
        'payment_method' => 'required|string|in:cash,card,transfer,ewallet,loyalty,qris',
        'amount_paid' => 'nullable|numeric|min:0',
        'loyalty_points_used' => 'nullable|integer|min:0',
        'sale_details' => 'required|array|min:1',
        'sale_details.*.product_id' => 'required|exists:products,id',
        'sale_details.*.quantity' => 'required|integer|min:1',
        'sale_details.*.original_price' => 'required|numeric|min:0',
        'sale_details.*.unit_price' => 'required|numeric|min:0',
        'sale_details.*.subtotal' => 'required|numeric|min:0',
        'sale_details.*.price_promotion_id' => 'nullable|exists:promotions,id',
        'sale_details.*.price_discount_amount' => 'nullable|numeric|min:0',
        'sale_details.*.price_promotion_type' => 'nullable|string',
        'sale_details.*.quantity_promotion_id' => 'nullable|exists:promotions,id',
        'sale_details.*.free_quantity' => 'nullable|integer|min:0',
        'sale_details.*.quantity_discount_amount' => 'nullable|numeric|min:0',
        'sale_details.*.quantity_promotion_type' => 'nullable|string',
        'sale_details.*.item_discount' => 'nullable|numeric|min:0',
    ];

    $validated = $request->validate($rules);

    DB::beginTransaction();
    try {
        // VALIDATE STOCK
        foreach ($validated['sale_details'] as $detail) {
            $product = Product::find($detail['product_id']);
            
            if (!$product) {
                throw new \Exception("Product not found: ID {$detail['product_id']}");
            }

            $purchasedQty = $detail['quantity'];
            $freeQty = $detail['free_quantity'] ?? 0;
            $totalRequired = $purchasedQty + $freeQty;

            if ($product->stock < $totalRequired) {
                throw new \Exception(
                    "Insufficient stock for {$product->product_name}. " .
                    "Required: {$totalRequired} (Buy {$purchasedQty}" .
                    ($freeQty > 0 ? " + {$freeQty} FREE" : "") . "), " .
                    "Available: {$product->stock}"
                );
            }
        }

        // GET CUSTOMER
        $customer = null;
        if ($validated['customer_id']) {
            $customer = Customer::find($validated['customer_id']);
        }

        $paymentMethod = $validated['payment_method'];
        $loyaltyPointsUsed = (int)($validated['loyalty_points_used'] ?? 0);
        $amountPaid = (float)($validated['amount_paid'] ?? $validated['total_price']);

        // VALIDATE LOYALTY
        if ($paymentMethod === 'loyalty') {
            if (!$customer) {
                throw new \Exception('Customer required for loyalty payment');
            }
            
            $totalBeforeLoyalty = $validated['subtotal'] - ($validated['discount'] ?? 0) + ($validated['tax'] ?? 0);
            
            if ($customer->loyalty_points < $totalBeforeLoyalty) {
                throw new \Exception(
                    "Insufficient loyalty points. Available: {$customer->loyalty_points}, Required: " . ceil($totalBeforeLoyalty)
                );
            }
            
            $loyaltyPointsUsed = (int)$totalBeforeLoyalty;
        }

        // DETERMINE STATUS
        $initialStatus = in_array($paymentMethod, ['cash', 'loyalty']) ? 'completed' : 'pending';

        // CREATE SALE
        $sale = Sale::create([
            'transaction_number' => $validated['transaction_number'],
            'sale_date' => $validated['sale_date'],
            'customer_id' => $validated['customer_id'],
            'user_id' => $validated['user_id'],
            'subtotal' => $validated['subtotal'],
            'discount' => $validated['discount'] ?? 0,
            'discount_percentage' => $validated['discount_percentage'] ?? 0,
            'tax' => $validated['tax'] ?? 0,
            'total_price' => $validated['total_price'],
            'status' => $initialStatus,
            'notes' => $validated['notes'] ?? null
        ]);

        // CREATE SALE DETAILS
        foreach ($validated['sale_details'] as $detail) {
            $product = Product::find($detail['product_id']);
            
            $saleDetail = SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'original_price' => $detail['original_price'],
                'unit_price' => $detail['unit_price'],
                'quantity' => $detail['quantity'],
                'price_promotion_id' => $detail['price_promotion_id'] ?? null,
                'price_discount_amount' => $detail['price_discount_amount'] ?? 0,
                'price_promotion_type' => $detail['price_promotion_type'] ?? null,
                'quantity_promotion_id' => $detail['quantity_promotion_id'] ?? null,
                'free_quantity' => $detail['free_quantity'] ?? 0,
                'quantity_discount_amount' => $detail['quantity_discount_amount'] ?? 0,
                'quantity_promotion_type' => $detail['quantity_promotion_type'] ?? null,
                'item_discount' => $detail['item_discount'] ?? 0,
                'subtotal' => $detail['subtotal']
            ]);
            
            // TRACK PROMOTION USAGE
            if (!empty($detail['price_promotion_id'])) {
                $pricePromotion = Promotion::find($detail['price_promotion_id']);
                
                PromotionUsage::create([
                    'promotion_id' => $detail['price_promotion_id'],
                    'customer_id' => $validated['customer_id'],
                    'sale_id' => $sale->id,
                    'sale_detail_id' => $saleDetail->id,
                    'discount_amount' => $detail['price_discount_amount'] * $detail['quantity']
                ]);
                
                $pricePromotion->increment('current_usage');
            }
            
            if (!empty($detail['quantity_promotion_id'])) {
                $qtyPromotion = Promotion::find($detail['quantity_promotion_id']);
                
                PromotionUsage::create([
                    'promotion_id' => $detail['quantity_promotion_id'],
                    'customer_id' => $validated['customer_id'],
                    'sale_id' => $sale->id,
                    'sale_detail_id' => $saleDetail->id,
                    'discount_amount' => $detail['quantity_discount_amount'] ?? 0
                ]);
                
                $qtyPromotion->increment('current_usage');
            }
        }

        // CREATE PAYMENT
        $paymentData = [
            'sale_id' => $sale->id,
            'payment_method' => $paymentMethod,
            'payment_channel' => null,
            'amount' => $amountPaid,
            'status' => $initialStatus === 'completed' ? 'completed' : 'pending',
            'user_id' => $validated['user_id'],
            'payment_date' => $initialStatus === 'completed' ? now() : null,
        ];

        $snapData = null;

        // HANDLE ONLINE PAYMENT
        if (in_array($paymentMethod, ['card', 'qris', 'transfer', 'ewallet'])) {
            try {
                $finalAmount = (int)$validated['total_price'];
                
                $snapData = $this->createMidtransSnap(
                    $sale->transaction_number,
                    $finalAmount,
                    $paymentMethod,
                    null,
                    $customer,
                    $sale->id
                );

                $paymentData['midtrans_order_id'] = $snapData['order_id'] ?? null;
                $paymentData['midtrans_snap_token'] = $snapData['snap_token'] ?? null;
                $paymentData['midtrans_payment_url'] = $snapData['payment_url'] ?? null;
                $paymentData['expired_at'] = now()->addDay();
                $paymentData['amount'] = $finalAmount;

            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Midtrans Snap creation failed: ' . $e->getMessage());

                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to create payment: ' . $e->getMessage()
                    ], 422);
                }

                return redirect()->back()->withInput()->withErrors([
                    'general' => 'Failed to create payment: ' . $e->getMessage()
                ]);
            }
        } else {
            $paymentData['reference_number'] = $paymentMethod === 'loyalty'
                ? "LP-{$loyaltyPointsUsed}-POINTS"
                : 'CASH-' . time() . '-' . uniqid();
        }

        $payment = Payment::create($paymentData);

        // ✅ COMPLETE SALE (if cash/loyalty)
        if ($initialStatus === 'completed') {
            $sale->completeSale($loyaltyPointsUsed);
            
            // ✅✅✅ SEND WHATSAPP RECEIPT - ONLY HERE ✅✅✅
           // $this->sendWhatsAppReceipt($sale);
        }

        DB::commit();

        // RETURN RESPONSE
        if (request()->expectsJson() || request()->ajax()) {
            $response = [
                'success' => true,
                'message' => $initialStatus === 'completed'
                    ? 'Sale completed successfully'
                    : 'Order created. Please complete payment.',
                'id' => $sale->id,
                'sale_id' => $sale->id,
                'transaction_number' => $sale->transaction_number,
                'status' => $sale->status,
                'total_amount' => $validated['total_price'],
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'payment_method' => $paymentMethod
            ];

            if (isset($snapData) && isset($snapData['snap_token'])) {
                $response['snap_token'] = $snapData['snap_token'];
                $response['payment_url'] = $snapData['payment_url'] ?? null;
                $response['expired_at'] = $paymentData['expired_at']->format('Y-m-d H:i:s');
                $response['order_id'] = $snapData['order_id'] ?? null;
            }

            return response()->json($response, 201);
        }

        if ($initialStatus === 'completed') {
            return redirect()->route('sales.create')
                ->with('sale_success', [
                    'id' => $sale->id,
                    'transaction_number' => $sale->transaction_number
                ]);
        } else {
            $pendingData = [
                'sale_id' => $sale->id,
                'transaction_number' => $sale->transaction_number,
                'payment_url' => $paymentData['midtrans_payment_url'] ?? null,
                'expired_at' => $paymentData['expired_at']->format('Y-m-d H:i:s'),
                'payment_type' => 'snap',
                'order_id' => isset($snapData) ? $snapData['order_id'] : null
            ];

            return redirect()->route('sales.create')
                ->with('payment_pending', $pendingData);
        }

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Sale creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }

        return redirect()->back()->withInput()->withErrors(['general' => $e->getMessage()]);
    }
}

/**
 * ✅✅✅ SEND WHATSAPP RECEIPT METHOD - FIXED ✅✅✅
 */
protected function sendWhatsAppReceipt($sale)
{
    try {
        // ✅ Check if customer has phone number
        if (!$sale->customer || !$sale->customer->phone_number) {
            Log::info('⏭️ Skipping WhatsApp receipt - No customer phone number', [
                'sale_id' => $sale->id,
                'transaction_number' => $sale->transaction_number
            ]);
            return;
        }

        Log::info('🚀 Starting WhatsApp receipt process', [
            'sale_id' => $sale->id,
            'transaction_number' => $sale->transaction_number,
            'customer' => $sale->customer->customer_name,
            'phone' => $sale->customer->phone_number
        ]);

        // ✅ Generate PDF
        $pdfService = new \App\Services\ReceiptPdfService();
        $pdfData = $pdfService->generate($sale);

        if (!$pdfData) {
            Log::error('❌ Failed to generate PDF receipt', ['sale_id' => $sale->id]);
            return; // Don't throw - transaction should still succeed
        }

        Log::info('✅ PDF generated successfully', [
            'sale_id' => $sale->id,
            'path' => $pdfData['path'],
            'filename' => $pdfData['filename'],
            'size_kb' => round($pdfData['size'] / 1024, 2)
        ]);

        // ✅ Build message
        $message = $this->buildReceiptMessage($sale);

        // ✅ Send via WhatsApp - PASS SALE ID FOR DOWNLOAD LINK!
        $whatsappService = new \App\Services\WhatsAppService();
        $result = $whatsappService->sendReceipt(
            $sale->customer->phone_number,
            $message,
            $pdfData['path'],
            $pdfData['filename'],
            $sale->id  // ✅ CRITICAL: Pass sale ID for download link fallback
        );

        if ($result) {
            Log::info('✅ WhatsApp receipt process completed', [
                'sale_id' => $sale->id,
                'customer' => $sale->customer->customer_name,
                'phone' => $sale->customer->phone_number
            ]);
        } else {
            Log::warning('⚠️ WhatsApp receipt send returned false', [
                'sale_id' => $sale->id
            ]);
        }

        // ✅ PDF cleanup is handled in WhatsAppService finally block

    } catch (\Exception $e) {
        Log::error('❌ Failed to send WhatsApp receipt', [
            'sale_id' => $sale->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        // Don't throw - transaction should still succeed even if WhatsApp fails
    }
}

/**
 * ✅ Build professional WhatsApp message
 */
protected function buildReceiptMessage($sale)
{
    $customerName = $sale->customer->customer_name;
    $totalItems = $sale->saleDetails->sum('quantity');
    $payment = $sale->payments->first();
    
    $message = "🛒 *SkyraMart - Digital Receipt*\n\n";
    $message .= "Dear *{$customerName}*,\n";
    $message .= "Thank you for shopping with us! 💚\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "📋 *Transaction Details*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "🧾 Receipt No: *{$sale->transaction_number}*\n";
    $message .= "📅 Date: {$sale->sale_date->format('d M Y, H:i')}\n";
    $message .= "👤 Cashier: {$sale->user->name}\n\n";
    
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "🛍️ *Order Summary*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Items
    foreach ($sale->saleDetails as $index => $detail) {
        $itemNum = $index + 1;
        $message .= "{$itemNum}. {$detail->product_name}\n";
        $message .= "   {$detail->quantity} x Rp" . number_format($detail->unit_price, 0, ',', '.') . " = Rp" . number_format($detail->subtotal, 0, ',', '.') . "\n";
        
        // Show promotions if any
        if ($detail->price_promotion_id || $detail->quantity_promotion_id) {
            if ($detail->price_promotion_id) {
                $message .= "   💰 " . ($detail->pricePromotion->badge_text ?? 'Discount') . ": -Rp" . number_format($detail->price_discount_amount, 0, ',', '.') . "\n";
            }
            if ($detail->quantity_promotion_id && $detail->free_quantity > 0) {
                $message .= "   🎁 Bonus: +{$detail->free_quantity} FREE\n";
            }
        }
        $message .= "\n";
    }
    
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "💰 *Payment Details*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "Subtotal: Rp" . number_format($sale->subtotal, 0, ',', '.') . "\n";
    
    if ($sale->discount > 0) {
        $message .= "Discount ({$sale->discount_percentage}%): -Rp" . number_format($sale->discount, 0, ',', '.') . "\n";
    }
    
    if ($sale->tax > 0) {
        $message .= "Tax: Rp" . number_format($sale->tax, 0, ',', '.') . "\n";
    }
    
    $message .= "*TOTAL: Rp" . number_format($sale->total_price, 0, ',', '.') . "*\n\n";
    
    if ($payment) {
        $paymentMethod = strtoupper($payment->payment_method);
        $message .= "Payment Method: *{$paymentMethod}*\n";
        
        if ($payment->payment_channel) {
            $message .= "Channel: {$payment->payment_channel}\n";
        }
        
        if ($payment->payment_method === 'cash') {
            $totalPaid = $payment->amount;
            $change = $totalPaid - $sale->total_price;
            $message .= "Cash Paid: Rp" . number_format($totalPaid, 0, ',', '.') . "\n";
            $message .= "Change: Rp" . number_format($change, 0, ',', '.') . "\n";
        }
    }
    
    $message .= "\n━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "📄 *Your receipt will be sent as PDF*\n";
    $message .= "_If PDF fails to send, you'll receive a download link_\n\n";
    $message .= "Need help? Contact us:\n";
    $message .= "📞 WA: 0889-2114-416\n";
    $message .= "📍 Jl. Masjid Daruttaqwa No. 123, Depok\n\n";
    $message .= "Thank you for your trust! 🙏\n";
    $message .= "_Please save this receipt for your records_\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "💚 *SkyraMart* - Your Trusted Store";
    
    return $message;
}

/**
 * ✅ Download receipt PDF (for fallback link)
 */
public function downloadReceipt(Sale $sale)
{
    try {
        Log::info('📥 Receipt download requested', [
            'sale_id' => $sale->id,
            'transaction_number' => $sale->transaction_number
        ]);

        $sale->load(['customer', 'user', 'saleDetails.product', 'payments']);
        
        $pdfService = new \App\Services\ReceiptPdfService();
        $pdfData = $pdfService->generate($sale);
        
        if (!$pdfData) {
            Log::error('Failed to generate receipt for download', ['sale_id' => $sale->id]);
            return response()->json([
                'error' => 'Failed to generate receipt'
            ], 500);
        }
        
        Log::info('✅ Receipt download successful', [
            'sale_id' => $sale->id,
            'filename' => $pdfData['filename']
        ]);

        return response()->download(
            $pdfData['path'],
            $pdfData['filename'],
            ['Content-Type' => 'application/pdf']
        )->deleteFileAfterSend(true);
        
    } catch (\Exception $e) {
        Log::error('Failed to download receipt', [
            'error' => $e->getMessage(),
            'sale_id' => $sale->id
        ]);
        
        return response()->json([
            'error' => 'Failed to generate receipt: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * ✅ Create Midtrans Snap with Multi-Promo Item Details
 */
private function createMidtransSnap($transactionNumber, $amount, $paymentMethod, $paymentChannel, $customer = null, $saleId = null)
{
    try {
        Log::info('Creating Midtrans Snap', [
            'transaction' => $transactionNumber,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'is_production' => config('services.midtrans.is_production')
        ]);

        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = strtoupper($paymentMethod) . '-' . $transactionNumber . '-' . time();
        $isProduction = config('services.midtrans.is_production');

        // ✅ ENABLED PAYMENTS
        $enabledPayments = [];
        if ($paymentMethod === 'card') {
            $enabledPayments = ['credit_card'];
        } 
        elseif ($paymentMethod === 'qris') {
            $enabledPayments = $isProduction ? ['qris'] : ['gopay'];
        } 
        elseif ($paymentMethod === 'transfer') {
            $enabledPayments = ['bca_va', 'bni_va', 'bri_va', 'echannel', 'permata_va', 'other_va'];
        } 
        elseif ($paymentMethod === 'ewallet') {
            if ($isProduction) {
                $enabledPayments = ['gopay', 'shopeepay', 'qris', 'dana', 'linkaja', 'ovo'];
            } else {
                $enabledPayments = ['gopay', 'shopeepay', 'dana', 'linkaja', 'ovo'];
            }
        }

        // ✅ GET SALE DETAILS WITH MULTI-PROMO INFO
        $sale = Sale::with('saleDetails.product', 'saleDetails.pricePromotion', 'saleDetails.quantityPromotion')
            ->find($saleId);
        
        $itemDetails = [];
        $itemsTotal = 0;

        if ($sale && $sale->saleDetails->count() > 0) {
            foreach ($sale->saleDetails as $detail) {
                $itemPrice = (int)$detail->unit_price;
                $itemQuantity = $detail->quantity;
                $itemSubtotal = $itemPrice * $itemQuantity;
                
                // ✅ Build item name with promotion info
                $itemName = $detail->product_name;
                
                // Add price promo info
                if ($detail->price_promotion_id && $detail->pricePromotion) {
                    $priceDiscount = (int)$detail->price_discount_amount;
                    $itemName .= " [{$detail->pricePromotion->badge_text} -Rp" . number_format($priceDiscount, 0, ',', '.') . "]";
                }
                
                // Add quantity promo info
                if ($detail->quantity_promotion_id && $detail->quantityPromotion) {
                    $itemName .= " [{$detail->quantityPromotion->badge_text}]";
                }
                
                $itemDetails[] = [
                    'id' => $detail->product_id,
                    'price' => $itemPrice,
                    'quantity' => $itemQuantity,
                    'name' => substr($itemName, 0, 50), // Midtrans limit
                ];
                
                $itemsTotal += $itemSubtotal;
            }

            // Add auto discount if exists
            if ($sale->discount > 0) {
                $discountAmount = (int)$sale->discount;
                $itemDetails[] = [
                    'id' => 'AUTO_DISCOUNT',
                    'price' => -$discountAmount,
                    'quantity' => 1,
                    'name' => 'Auto Discount (' . number_format($sale->discount_percentage, 1) . '%)',
                ];
                $itemsTotal -= $discountAmount;
            }

            // Add tax if exists
            if ($sale->tax > 0) {
                $taxAmount = (int)$sale->tax;
                $itemDetails[] = [
                    'id' => 'TAX',
                    'price' => $taxAmount,
                    'quantity' => 1,
                    'name' => 'Tax',
                ];
                $itemsTotal += $taxAmount;
            }
        }

        // Validate amount
        if (abs($itemsTotal - $amount) > 1) {
            $amount = $itemsTotal;
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'enabled_payments' => $enabledPayments,
        ];

        if (!empty($itemDetails)) {
            $params['item_details'] = $itemDetails;
        }

        // Add custom fields for multi-promo
        if ($sale && ($sale->discount > 0 || $sale->saleDetails->where('price_promotion_id', '!=', null)->count() > 0)) {
            $params['custom_field1'] = 'Multi Promo Active';
            $params['custom_field2'] = 'Discount: Rp ' . number_format($sale->discount, 0, ',', '.');
        }

        $params['expiry'] = [
            'duration' => $paymentMethod === 'qris' ? 15 : 24,
            'unit' => $paymentMethod === 'qris' ? 'minutes' : 'hours'
        ];

        $params['callbacks'] = [
            'finish' => route('sales.payment-return', ['sale_id' => $saleId])
        ];

        // Customer details
        if (!$customer || !isset($customer->id) || empty($customer->id)) {
            $params['customer_details'] = [
                'first_name' => 'Walk-in Customer',
                'phone' => '082100000000',
                'email' => 'customer@freshmart.com',
            ];
        } else {
            $params['customer_details'] = [
                'first_name' => $customer->customer_name ?? 'Walk-in Customer',
                'phone' => $customer->phone_number ?? '082100000000',
                'email' => $customer->email ?? 'customer@freshmart.com',
            ];
        }

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $paymentUrl = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v2/vtweb/' . $snapToken
            : 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken;

        Log::info("Midtrans Snap created successfully", [
            'order_id' => $orderId,
            'snap_token' => substr($snapToken, 0, 20) . '...',
            'payment_method' => $paymentMethod,
            'enabled_payments' => $enabledPayments,
            'has_multi_promo' => isset($params['custom_field1'])
        ]);

        return [
            'order_id' => $orderId,
            'snap_token' => $snapToken,
            'payment_url' => $paymentUrl,
        ];
    } catch (\Exception $e) {
        Log::error('Midtrans Snap error: ' . $e->getMessage());
        throw new \Exception('Failed to create payment: ' . $e->getMessage());
    }
}

    /**
 * ✅ UPDATED: Payment Return Handler with Bank Detection
 */
public function paymentReturn(Request $request, $sale_id)
{
    try {
        Log::info('Payment return accessed', [
            'sale_id' => $sale_id,
            'query_params' => $request->all()
        ]);

        $sale = Sale::findOrFail($sale_id);

        // Handle success parameter from frontend
        if ($request->has('payment_success')) {
            $successData = json_decode($request->get('payment_success'), true);
            return redirect()->route('sales.create')
                ->with('sale_success', [
                    'id' => $successData['id'] ?? $sale->id,
                    'transaction_number' => $successData['transaction_number'] ?? $sale->transaction_number
                ]);
        }

        $payment = $sale->payments()->latest()->first();

        if (!$payment) {
            Log::error('Payment not found', ['sale_id' => $sale_id]);
            return redirect()->route('sales.create')
                ->with('error', 'Payment not found');
        }

        // Get order_id from Midtrans
        $midtransOrderId = $request->query('order_id');
        $transactionStatus = $request->query('transaction_status');

        Log::info('Midtrans callback params', [
            'midtrans_order_id' => $midtransOrderId,
            'transaction_status' => $transactionStatus
        ]);

        // Update order_id if different
        if ($midtransOrderId && $midtransOrderId !== $payment->midtrans_order_id) {
            $payment->update(['midtrans_order_id' => $midtransOrderId]);
        }

        // Check status with Midtrans API
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        try {
            $orderIdToCheck = $midtransOrderId ?? $payment->midtrans_order_id;

            if (!$orderIdToCheck) {
                throw new \Exception('No order_id available to check status');
            }

            $status = \Midtrans\Transaction::status($orderIdToCheck);

            $apiTransactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status ?? 'accept';
            $paymentType = $status->payment_type ?? null;

            // ✅ EXTRACT BOTH payment_channel AND bank_name
            $detectedChannel = $this->extractPaymentChannel($paymentType, $status);
            $bankName = $this->extractBankName($paymentType, $status);

            Log::info('Payment status from Midtrans API', [
                'order_id_checked' => $orderIdToCheck,
                'transaction_status' => $apiTransactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
                'detected_channel' => $detectedChannel,
                'bank_name' => $bankName,
                'va_numbers' => $status->va_numbers ?? null, // Debug info
            ]);

            // ✅ SUCCESS - Payment completed
            if (in_array($apiTransactionStatus, ['capture', 'settlement']) && $fraudStatus == 'accept') {
                if ($payment->status !== 'completed') {
                    DB::beginTransaction();
                    try {
                        // ✅ Update payment with detected channel AND bank_name
                        $payment->update([
                            'status' => 'completed',
                            'reference_number' => $status->transaction_id ?? $orderIdToCheck,
                            'payment_date' => now(),
                            'midtrans_order_id' => $orderIdToCheck,
                            'payment_channel' => $detectedChannel, // Full description
                            'bank_name' => $bankName // Bank code only (BCA, BNI, etc.)
                        ]);

                        $loyaltyPointsUsed = 0;
                        $sale->completeSale($loyaltyPointsUsed);

                        DB::commit();

                        Log::info('Payment completed successfully via return', [
                            'payment_channel' => $detectedChannel,
                            'bank_name' => $bankName
                        ]);
                    } catch (\Exception $e) {
                        DB::rollback();
                        Log::error('Failed to complete sale: ' . $e->getMessage());
                    }
                }

                // SUCCESS - Redirect with success modal
                return redirect()->route('sales.create')
                    ->with('sale_success', [
                        'id' => $sale->id,
                        'transaction_number' => $sale->transaction_number
                    ]);
            } 
            // PENDING
            elseif ($apiTransactionStatus == 'pending') {
                Log::info('Payment pending - showing cancel/change modal');
                
                return redirect()->route('sales.create')
                    ->with('show_cancel_modal', [
                        'sale_id' => $sale->id,
                        'transaction_number' => $sale->transaction_number,
                        'total_amount' => $sale->total_price
                    ]);
            } 
            // FAILED
            else {
                Log::warning('Payment failed/expired via return', [
                    'status' => $apiTransactionStatus,
                    'sale_id' => $sale->id
                ]);

                return redirect()->route('sales.create')
                    ->with('error', 'Payment ' . $apiTransactionStatus . '. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to check Midtrans status in return: ' . $e->getMessage());

            return redirect()->route('sales.create')
                ->with('show_cancel_modal', [
                    'sale_id' => $sale->id,
                    'transaction_number' => $sale->transaction_number,
                    'total_amount' => $sale->total_price
                ]);
        }
    } catch (\Exception $e) {
        Log::error('Payment return error: ' . $e->getMessage());

        return redirect()->route('sales.index')
            ->with('error', 'Failed to process payment return');
    }
}

/**
 * ✅ FIXED: Extract Payment Channel from Midtrans Response
 */
private function extractPaymentChannel($paymentType, $status)
{
    if (!$paymentType) return 'unknown';

    // ✅ CRITICAL FIX: Check acquirer/issuer FIRST for e-wallets
    $acquirer = strtolower($status->acquirer ?? '');
    $issuer = strtolower($status->issuer_bank ?? '');
    
    // ✅ E-Wallet Detection (HIGHEST PRIORITY)
    if ($acquirer === 'gopay' || $paymentType === 'gopay') {
        return 'GoPay';
    }
    
    if ($acquirer === 'shopeepay' || $paymentType === 'shopeepay') {
        return 'ShopeePay';
    }
    
    // ✅ CRITICAL FIX: DANA Detection - Check multiple fields
    if ($acquirer === 'dana' || 
        $paymentType === 'dana' || 
        $issuer === 'dana' ||
        (isset($status->payment_code) && stripos($status->payment_code, 'dana') !== false) ||
        (isset($status->issuer) && stripos($status->issuer, 'dana') !== false)) {
        return 'DANA';
    }
    
    // ✅ OVO Detection
    if ($acquirer === 'ovo' || 
        $paymentType === 'ovo' ||
        $issuer === 'ovo' ||
        (isset($status->payment_code) && stripos($status->payment_code, 'ovo') !== false)) {
        return 'OVO';
    }
    
    // ✅ LinkAja Detection
    if ($acquirer === 'linkaja' || 
        $paymentType === 'linkaja' ||
        $issuer === 'linkaja' ||
        (isset($status->payment_code) && stripos($status->payment_code, 'linkaja') !== false)) {
        return 'LinkAja';
    }

    // Map payment_type to friendly channel name
    $channelMap = [
        'credit_card' => 'Credit Card',
        'bank_transfer' => 'Bank Transfer',
        'bca_va' => 'BCA Virtual Account',
        'bni_va' => 'BNI Virtual Account',
        'bri_va' => 'BRI Virtual Account',
        'echannel' => 'Mandiri Bill Payment',
        'permata_va' => 'Permata Virtual Account',
        'other_va' => 'Other Bank VA',
        'qris' => 'QRIS',
        'cstore' => 'Convenience Store',
    ];

    $channel = $channelMap[$paymentType] ?? ucwords(str_replace('_', ' ', $paymentType));

    // For bank_transfer, extract SPECIFIC bank from va_numbers
    if ($paymentType === 'bank_transfer' && isset($status->va_numbers) && is_array($status->va_numbers) && count($status->va_numbers) > 0) {
        $bankCode = strtolower($status->va_numbers[0]->bank ?? '');
        
        $bankNames = [
            'bca' => 'BCA Virtual Account',
            'bni' => 'BNI Virtual Account', 
            'bri' => 'BRI Virtual Account',
            'mandiri' => 'Mandiri Bill Payment',
            'permata' => 'Permata Virtual Account',
            'cimb' => 'CIMB Niaga Virtual Account',
            'danamon' => 'Danamon Virtual Account',
        ];
        
        $channel = $bankNames[$bankCode] ?? ucwords($bankCode) . ' Virtual Account';
    }

    return $channel;
}

/**
 * ✅ FIXED: Extract Bank Name Only (for bank_name field)
 */
private function extractBankName($paymentType, $status)
{
    if (!$paymentType) return null;

    // ✅ CRITICAL FIX: CHECK ACQUIRER & ISSUER FIRST
    $acquirer = strtoupper($status->acquirer ?? '');
    $issuer = strtoupper($status->issuer_bank ?? '');
    $issuerName = strtoupper($status->issuer ?? '');
    
    // ✅ E-Wallet Priority Detection
    if (in_array($acquirer, ['GOPAY', 'SHOPEEPAY', 'DANA', 'OVO', 'LINKAJA'])) {
        return $acquirer;
    }
    
    if (in_array($issuer, ['GOPAY', 'SHOPEEPAY', 'DANA', 'OVO', 'LINKAJA'])) {
        return $issuer;
    }
    
    if (in_array($issuerName, ['GOPAY', 'SHOPEEPAY', 'DANA', 'OVO', 'LINKAJA'])) {
        return $issuerName;
    }
    
    // ✅ Check payment_code for e-wallets
    if (isset($status->payment_code)) {
        $paymentCode = strtoupper($status->payment_code);
        if (stripos($paymentCode, 'DANA') !== false) return 'DANA';
        if (stripos($paymentCode, 'OVO') !== false) return 'OVO';
        if (stripos($paymentCode, 'LINKAJA') !== false) return 'LINKAJA';
        if (stripos($paymentCode, 'GOPAY') !== false) return 'GOPAY';
        if (stripos($paymentCode, 'SHOPEEPAY') !== false) return 'SHOPEEPAY';
    }

    // For bank_transfer, extract bank name from va_numbers
    if ($paymentType === 'bank_transfer' && isset($status->va_numbers) && is_array($status->va_numbers) && count($status->va_numbers) > 0) {
        $bankCode = strtoupper($status->va_numbers[0]->bank ?? '');
        return $bankCode; // Returns: BCA, BNI, BRI, MANDIRI, PERMATA, etc.
    }
    
    // For direct VA methods
    $directVAMap = [
        'bca_va' => 'BCA',
        'bni_va' => 'BNI',
        'bri_va' => 'BRI',
        'echannel' => 'MANDIRI',
        'permata_va' => 'PERMATA',
    ];
    
    if (isset($directVAMap[$paymentType])) {
        return $directVAMap[$paymentType];
    }
    
    // ✅ E-Wallet detection from payment_type (fallback)
    $ewalletMap = [
        'gopay' => 'GOPAY',
        'shopeepay' => 'SHOPEEPAY',
        'dana' => 'DANA',
        'ovo' => 'OVO',
        'linkaja' => 'LINKAJA',
        'qris' => 'QRIS',
    ];
    
    if (isset($ewalletMap[$paymentType])) {
        return $ewalletMap[$paymentType];
    }
    
    return null;
}

    /**
 * ✅ UPDATED: Check Payment Status with Bank Detection
 */
public function checkPaymentStatus(Sale $sale)
{
    try {
        Log::info('Checking payment status', ['sale_id' => $sale->id]);

        $payment = $sale->payments()->latest()->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'No payment found'
            ], 404);
        }

        // Already completed
        if ($payment->status === 'completed' && $sale->status === 'completed') {
            return response()->json([
                'success' => true,
                'status' => 'completed',
                'message' => 'Payment already completed',
                'redirect_url' => route('sales.create'),
                'sale_data' => [
                    'id' => $sale->id,
                    'transaction_number' => $sale->transaction_number,
                    'status' => $sale->status,
                    'total_price' => $sale->total_price,
                    'payment_channel' => $payment->payment_channel,
                    'bank_name' => $payment->bank_name
                ]
            ]);
        }

        // No Midtrans order
        if (!$payment->midtrans_order_id) {
            return response()->json([
                'success' => true,
                'status' => $payment->status,
                'message' => 'Payment method does not use Midtrans',
                'sale_data' => [
                    'id' => $sale->id,
                    'transaction_number' => $sale->transaction_number,
                    'status' => $sale->status,
                    'total_price' => $sale->total_price
                ]
            ]);
        }

        // Check with Midtrans API
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        try {
            $status = \Midtrans\Transaction::status($payment->midtrans_order_id);

            $transactionStatus = strtolower($status->transaction_status);
            $fraudStatus = isset($status->fraud_status) ? strtolower($status->fraud_status) : 'accept';
            $paymentType = $status->payment_type ?? null;

            // ✅ Extract both channel and bank
            $detectedChannel = $this->extractPaymentChannel($paymentType, $status);
            $bankName = $this->extractBankName($paymentType, $status);

            Log::info('Payment status from Midtrans', [
                'order_id' => $payment->midtrans_order_id,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
                'detected_channel' => $detectedChannel,
                'bank_name' => $bankName,
                'va_numbers' => $status->va_numbers ?? null,
            ]);

            // SUCCESS
            if (($transactionStatus === 'capture' && $fraudStatus === 'accept') || $transactionStatus === 'settlement') {

                if ($payment->status !== 'completed') {
                    DB::beginTransaction();
                    try {
                        // ✅ Update payment with channel AND bank_name
                        $payment->update([
                            'status' => 'completed',
                            'reference_number' => $status->transaction_id ?? $payment->midtrans_order_id,
                            'payment_date' => now(),
                            'payment_channel' => $detectedChannel,
                            'bank_name' => $bankName
                        ]);

                        $loyaltyPointsUsed = 0;
                        $sale->completeSale($loyaltyPointsUsed);

                        DB::commit();

                        Log::info('Payment completed via check', [
                            'sale_id' => $sale->id,
                            'payment_channel' => $detectedChannel,
                            'bank_name' => $bankName
                        ]);

                        return response()->json([
                            'success' => true,
                            'status' => 'completed',
                            'message' => 'Payment confirmed! Transaction completed.',
                            'redirect_url' => route('sales.create'),
                            'sale_data' => [
                                'id' => $sale->id,
                                'transaction_number' => $sale->transaction_number,
                                'status' => 'completed',
                                'total_price' => $sale->total_price,
                                'payment_channel' => $detectedChannel,
                                'bank_name' => $bankName
                            ]
                        ]);
                    } catch (\Exception $e) {
                        DB::rollback();
                        Log::error('Failed to complete sale: ' . $e->getMessage());

                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to process payment: ' . $e->getMessage()
                        ], 500);
                    }
                }

                // Already completed
                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'message' => 'Payment already completed',
                    'redirect_url' => route('sales.create'),
                    'sale_data' => [
                        'id' => $sale->id,
                        'transaction_number' => $sale->transaction_number,
                        'status' => $sale->status,
                        'total_price' => $sale->total_price,
                        'payment_channel' => $payment->payment_channel,
                        'bank_name' => $payment->bank_name
                    ]
                ]);
            } 
            // FAILED
            elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {

                $payment->update(['status' => 'failed']);
                $sale->update(['status' => 'cancelled']);

                return response()->json([
                    'success' => false,
                    'status' => 'failed',
                    'message' => 'Payment ' . $transactionStatus,
                    'sale_data' => [
                        'id' => $sale->id,
                        'transaction_number' => $sale->transaction_number,
                        'status' => 'cancelled',
                        'total_price' => $sale->total_price
                    ]
                ]);
            } 
            // PENDING
            else {
                return response()->json([
                    'success' => true,
                    'status' => $transactionStatus,
                    'message' => 'Payment status: ' . $transactionStatus,
                    'payment_url' => $payment->midtrans_payment_url,
                    'expired_at' => $payment->expired_at ? $payment->expired_at->format('Y-m-d H:i:s') : null,
                    'sale_data' => [
                        'id' => $sale->id,
                        'transaction_number' => $sale->transaction_number,
                        'status' => $sale->status,
                        'total_price' => $sale->total_price
                    ]
                ]);
            }
        } catch (\Midtrans\Exceptions\ApiException $e) {
            $errorMessage = $e->getMessage();
            Log::error('Midtrans API Exception in check', [
                'error' => $errorMessage,
                'order_id' => $payment->midtrans_order_id
            ]);

            if (strpos($errorMessage, '404') !== false || strpos($errorMessage, "doesn't exist") !== false) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Payment order not found in Midtrans. Transaction may have expired.'
                ]);
            }

            throw $e;
        }
    } catch (\Exception $e) {
        Log::error('Failed to check payment status', [
            'error' => $e->getMessage(),
            'sale_id' => $sale->id
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to check payment status: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Cancel Sale via API
 */
public function cancelSaleApi(Sale $sale)
    {
        DB::beginTransaction();
        try {
            Log::info('Cancelling sale via API', ['sale_id' => $sale->id]);
            
            $sale->cancelSale();
            
            $payment = $sale->payments()->latest()->first();
            if ($payment && $payment->midtrans_order_id) {
                try {
                    \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                    \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
                    
                    \Midtrans\Transaction::cancel($payment->midtrans_order_id);
                    
                    Log::info('Midtrans transaction cancelled', [
                        'order_id' => $payment->midtrans_order_id
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to cancel Midtrans transaction: ' . $e->getMessage());
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Sale cancelled successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to cancel sale: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel sale: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
 * ✅ FIXED: Change Payment Method - Update existing payment
 */
public function changePaymentMethod(Request $request, Sale $sale)
{
    $validated = $request->validate([
        'new_payment_method' => 'required|string|in:cash,card,qris,transfer,ewallet',
    ]);
    
    DB::beginTransaction();
    try {
        Log::info('Changing payment method', [
            'sale_id' => $sale->id,
            'old_method' => $sale->payments()->latest()->first()?->payment_method,
            'new_method' => $validated['new_payment_method']
        ]);
        
        $newPaymentMethod = $validated['new_payment_method'];
        
        // ✅ Get existing payment (don't create new one)
        $currentPayment = $sale->payments()->latest()->first();
        
        if (!$currentPayment) {
            throw new \Exception('No payment found for this sale');
        }
        
        // Cancel old Midtrans transaction if exists
        if ($currentPayment->midtrans_order_id) {
            try {
                \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
                
                \Midtrans\Transaction::cancel($currentPayment->midtrans_order_id);
                
                Log::info('Old Midtrans transaction cancelled', [
                    'order_id' => $currentPayment->midtrans_order_id
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to cancel old Midtrans transaction: ' . $e->getMessage());
            }
        }
        
        // ✅ UPDATE existing payment (don't create new)
        $currentPayment->payment_method = $newPaymentMethod;
        $currentPayment->payment_channel = null; // Will be filled by Snap callback
        $currentPayment->midtrans_order_id = null;
        $currentPayment->midtrans_payment_url = null;
        $currentPayment->midtrans_snap_token = null;
        $currentPayment->midtrans_qr_string = null;
        $currentPayment->reference_number = null;
        
        $newStatus = $newPaymentMethod === 'cash' ? 'completed' : 'pending';
        $currentPayment->status = $newStatus;
        
        $response = [
            'success' => true,
            'message' => 'Payment method changed successfully',
            'sale_id' => $sale->id,
            'transaction_number' => $sale->transaction_number,
            'new_payment_method' => $newPaymentMethod,
            'payment_id' => $currentPayment->id // For debugging
        ];
        
        // If cash, complete immediately
        if ($newPaymentMethod === 'cash') {
            $currentPayment->payment_date = now();
            $currentPayment->reference_number = 'CASH-' . time() . '-' . uniqid();
            $currentPayment->save();
            
            // Complete sale if not already completed
            if ($sale->status !== 'completed') {
                $sale->completeSale(0);
            }
            
            Log::info('Payment changed to cash, sale completed');
        } 
        // If online payment, create new Snap
        else if (in_array($newPaymentMethod, ['card', 'qris', 'transfer', 'ewallet'])) {
            // Save first to update method
            $currentPayment->save();
            
            try {
                $finalAmount = (int)$sale->total_price;
                
                $snapData = $this->createMidtransSnap(
                    $sale->transaction_number,
                    $finalAmount,
                    $newPaymentMethod,
                    null,
                    $sale->customer,
                    $sale->id
                );
                
                // ✅ Update same payment record with new Snap data
                $currentPayment->midtrans_order_id = $snapData['order_id'] ?? null;
                $currentPayment->midtrans_snap_token = $snapData['snap_token'] ?? null;
                $currentPayment->midtrans_payment_url = $snapData['payment_url'] ?? null;
                $currentPayment->expired_at = now()->addDay();
                
                $currentPayment->save();
                
                $response['snap_token'] = $snapData['snap_token'];
                $response['payment_url'] = $snapData['payment_url'] ?? null;
                
                Log::info('New Midtrans Snap created for payment method change', [
                    'payment_id' => $currentPayment->id,
                    'order_id' => $snapData['order_id'],
                    'snap_token' => $snapData['snap_token']
                ]);
                
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Failed to create new Midtrans transaction: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create new payment: ' . $e->getMessage()
                ], 422);
            }
        }
        
        DB::commit();
        
        return response()->json($response);
        
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Failed to change payment method: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to change payment method: ' . $e->getMessage()
        ], 500);
    }
}

// Tambahkan method ini di SaleController.php

/**
 * Check and update expired payments
 */
public function checkExpiredPayments()
{
    DB::beginTransaction();
    try {
        // Get all pending sales with expired payments
        $expiredPayments = Payment::where('status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->with('sale')
            ->get();

        $updatedCount = 0;
        foreach ($expiredPayments as $payment) {
            // Update payment status to failed
            $payment->update(['status' => 'failed']);
            
            // Update sale status to cancelled
            if ($payment->sale) {
                $payment->sale->update(['status' => 'cancelled']);
            }
            
            $updatedCount++;
            
            Log::info('Expired payment auto-cancelled', [
                'payment_id' => $payment->id,
                'sale_id' => $payment->sale_id,
                'order_id' => $payment->midtrans_order_id
            ]);
        }

        DB::commit();
        
        return response()->json([
            'success' => true,
            'updated_count' => $updatedCount,
            'message' => "Updated {$updatedCount} expired payments"
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Failed to check expired payments: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to check expired payments: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Get sale with payment info for modal
 */
public function getSaleWithPayment(Sale $sale)
{
    try {
        $sale->load(['customer', 'payments' => function($query) {
            $query->latest();
        }]);

        $payment = $sale->payments->first();
        
        // Check if payment is expired
        $isExpired = false;
        if ($payment && $payment->expired_at && $payment->expired_at < now() && $payment->status === 'pending') {
            $isExpired = true;
            
            // Auto-update to failed/cancelled
            DB::beginTransaction();
            try {
                $payment->update(['status' => 'failed']);
                $sale->update(['status' => 'cancelled']);
                DB::commit();
                
                // Refresh data
                $sale->refresh();
                $payment->refresh();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Failed to auto-cancel expired payment: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'sale' => $sale,
            'payment' => $payment,
            'is_expired' => $isExpired
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to get sale with payment: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to load sale data: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Check and cancel expired payments
 */
private function checkAndCancelExpiredPayments()
{
    DB::beginTransaction();
    try {
        $expiredPayments = Payment::expired()->with('sale')->get();
        
        foreach ($expiredPayments as $payment) {
            $payment->update(['status' => 'failed']);
            
            if ($payment->sale && $payment->sale->status === 'pending') {
                $payment->sale->update(['status' => 'cancelled']);
            }
            
            Log::info('Auto-cancelled expired payment', [
                'payment_id' => $payment->id,
                'sale_id' => $payment->sale_id
            ]);
        }
        
        DB::commit();
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Failed to check expired payments: ' . $e->getMessage());
    }
}

    public function show(Sale $sale)
    {
        try {
            $sale->load(['customer', 'user', 'saleDetails.product', 'payments']);

            $amountPaid = $sale->payments->sum('amount');
            $change = $amountPaid - $sale->total_price;

            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json($sale);
            }

            return view('sales.show', compact('sale', 'amountPaid', 'change'));
        } catch (\Exception $e) {
            Log::error('Failed to fetch sale: ' . $e->getMessage());

            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json([
                    'message' => 'Failed to fetch sale: ' . $e->getMessage()
                ], 500);
            }

            // redirect lebih aman daripada maksa buka view dengan variabel kosong
            return redirect()->route('sales.index')
                ->with('error', 'Failed to load sale details.');
        }
    }

    /**
     * Show form for editing sale status
     */
    public function editStatus(Sale $sale)
    {
        try {
            // Load relationships
            $sale->load(['customer', 'payments']);

            // Define available status transitions based on current status
            $availableStatuses = $this->getAvailableStatusTransitions($sale->status);

            if (request()->expectsJson()) {
                return response()->json([
                    'sale' => $sale,
                    'available_statuses' => $availableStatuses
                ]);
            }

            return view('sales.edit-status', compact('sale', 'availableStatuses'));
        } catch (\Exception $e) {
            Log::error('Failed to load edit status page: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load edit status page: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('sales.index')
                ->with('error', 'Failed to load edit status page: ' . $e->getMessage());
        }
    }

    /**
     * Update sale status and sync with payment
     */
    public function updateStatus(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $sale->status;
            $newStatus = $validated['status'];

            // Prevent invalid transitions
            if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
                throw new \Exception("Cannot change status from {$oldStatus} to {$newStatus}");
            }

            // Handle status change logic
            switch ($newStatus) {
                case 'completed':
                    if ($oldStatus === 'pending') {
                        // Complete the sale (reduce stock, deduct loyalty, add purchase)
                        $loyaltyPointsUsed = 0; // Get from payment if exists
                        $payment = $sale->payments()->latest()->first();

                        if ($payment && $payment->payment_method === 'loyalty') {
                            // Calculate loyalty points from payment amount
                            $loyaltyPointsUsed = (int)$payment->amount;
                        }

                        $sale->completeSale($loyaltyPointsUsed);

                        // Update all payments to completed
                        $sale->payments()->update([
                            'status' => 'completed',
                            'payment_date' => now()
                        ]);
                    } elseif ($oldStatus === 'cancelled') {
                        // Reactivate cancelled sale
                        $sale->update(['status' => 'completed']);

                        // Reduce stock again
                        $sale->processStockReduction();

                        // Add purchase to customer
                        if ($sale->customer_id) {
                            $customer = Customer::find($sale->customer_id);
                            $customer->addPurchase($sale->total_price);
                        }

                        // Update payments
                        $sale->payments()->update(['status' => 'completed']);
                    }
                    break;

                case 'pending':
                    if ($oldStatus === 'completed') {
                        // Restore stock when reverting to pending
                        foreach ($sale->saleDetails as $detail) {
                            $product = Product::lockForUpdate()->find($detail->product_id);
                            if ($product) {
                                $oldStock = $product->stock;
                                $product->increment('stock', $detail->quantity);

                                StockHistory::create([
                                    'product_id' => $product->id,
                                    'type' => 'in',
                                    'quantity' => $detail->quantity,
                                    'stock_before' => $oldStock,
                                    'stock_after' => $product->fresh()->stock,
                                    'reference_type' => 'sales_status_change',
                                    'reference_id' => $sale->id,
                                    'description' => "Sale status changed to pending: {$sale->transaction_number}",
                                    'user_id' => auth()->id() ?? $sale->user_id
                                ]);
                            }
                        }

                        // Remove purchase from customer
                        if ($sale->customer_id) {
                            $customer = Customer::find($sale->customer_id);
                            $customer->decrement('total_purchase', $sale->total_price);
                            $customer->syncLoyaltyPoints();
                        }

                        $sale->update(['status' => 'pending']);

                        // Update payments to pending
                        $sale->payments()->update([
                            'status' => 'pending',
                            'stock_reduced' => false,
                            'loyalty_deducted' => false
                        ]);
                    } elseif ($oldStatus === 'cancelled') {
                        $sale->update(['status' => 'pending']);
                        $sale->payments()->update(['status' => 'pending']);
                    }
                    break;

                case 'cancelled':
                    // Cancel sale using existing method
                    $sale->cancelSale();

                    // Update all payments to failed
                    $sale->payments()->update(['status' => 'failed']);
                    break;
            }

            // Update notes if provided
            if (isset($validated['notes'])) {
                $sale->update(['notes' => $validated['notes']]);
            }

            DB::commit();

            Log::info("Sale status updated: {$sale->transaction_number} from {$oldStatus} to {$newStatus}");

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sale status updated successfully',
                    'data' => $sale->fresh()->load(['customer', 'saleDetails.product', 'payments'])
                ]);
            }

            return redirect()->route('sales.index')
                ->with('success', "Sale status updated from {$oldStatus} to {$newStatus}");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update sale status: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update status: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Get available status transitions based on current status
     */
    private function getAvailableStatusTransitions($currentStatus)
    {
        $transitions = [
            'pending' => [
                'completed' => 'Mark as Completed',
                'cancelled' => 'Cancel Sale'
            ],
            'completed' => [
                'pending' => 'Revert to Pending',
                'cancelled' => 'Cancel Sale'
            ],
            'cancelled' => [
                'pending' => 'Reactivate as Pending',
                'completed' => 'Reactivate as Completed'
            ]
        ];

        return $transitions[$currentStatus] ?? [];
    }

    /**
     * Check if status transition is valid
     */
    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        // Same status is not allowed
        if ($oldStatus === $newStatus) {
            return false;
        }

        // All transitions are allowed in this system
        // You can add more restrictions here if needed
        return true;
    }
    
    public function destroy(Sale $sale)
    {
        if ($sale->status === 'completed') {
            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json(['message' => 'Cannot delete completed sale'], 422);
            }
            return redirect()->back()->with('error', 'Cannot delete completed sale');
        }

        DB::beginTransaction();
        try {
            // If sale was completed, remove from customer's total purchase
            if ($sale->status === 'completed' && $sale->customer_id) {
                $customer = Customer::find($sale->customer_id);
                $customer->decrement('total_purchase', $sale->total_price);
                $customer->syncLoyaltyPoints();
            }

            // Restore stock if sale was pending
            if ($sale->status === 'pending') {
                foreach ($sale->saleDetails as $detail) {
                    $product = Product::find($detail->product_id);
                    if ($product) {
                        $oldStock = $product->stock;
                        $newStock = $oldStock + $detail->quantity;
                        $product->update(['stock' => $newStock]);

                        // Create stock history
                        StockHistory::create([
                            'product_id' => $product->id,
                            'type' => 'in',
                            'quantity' => $detail->quantity,
                            'stock_before' => $oldStock,
                            'stock_after' => $newStock,
                            'reference_type' => 'sales_return',
                            'reference_id' => $sale->id,
                            'description' => "Sale deletion restoration: {$sale->transaction_number}",
                            'user_id' => auth()->id ?? $sale->user_id
                        ]);
                    }
                }
            }

            $sale->delete();
            DB::commit();

            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json(['message' => 'Sale deleted successfully']);
            }

            return redirect()->route('sales.index')->with('success', 'Sale deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete sale: ' . $e->getMessage());

            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json([
                    'message' => 'Failed to delete sale: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Failed to delete sale: ' . $e->getMessage());
        }
    }

    // Search customers with enhanced information
    public function searchCustomers(Request $request)
    {
        $search = $request->get('q', '');
        $customers = Customer::active()
            ->where(function ($query) use ($search) {
                $query->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                $customer->photo_url = $customer->photo_profile ? asset('storage/' . $customer->photo_profile) : null;
                return $customer;
            });

        return response()->json($customers);
    }

    public function checkProductStock(Product $product)
    {
        return response()->json([
            'stock' => $product->stock,
            'available' => $product->stock > 0,
            'low_stock' => $product->stock <= 5
        ]);
    }

    // Enhanced product search with category filter
    public function searchProducts(Request $request)
    {
        $search = $request->get('q', '');
        $category = $request->get('category', '');

        $query = Product::with('category')
            ->where('is_active', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        $products = $query->orderByRaw('stock > 0 DESC, stock DESC')->limit(20)->get();

        return response()->json($products);
    }

    // Get sales statistics
    public function getStatistics(Request $request)
{
    try {
        $dateFrom = $request->get('date_from', now()->startOfMonth());
        $dateTo = $request->get('date_to', now()->endOfMonth());

        // ✅ FILTER BERDASARKAN ROLE USER
        $user = auth()->user();
        
        $baseQuery = Sale::whereBetween('sale_date', [$dateFrom, $dateTo]);
        
        // Jika bukan admin, filter berdasarkan user_id
        if (!$user->isAdmin()) {
            $baseQuery->where('user_id', $user->id);
        }

        $stats = [
            'total_sales' => (clone $baseQuery)->where('status', 'completed')->count(),
            'total_revenue' => (clone $baseQuery)->where('status', 'completed')->sum('total_price'),
            'pending_sales' => (clone $baseQuery)->where('status', 'pending')->count(),
            'cancelled_sales' => (clone $baseQuery)->where('status', 'cancelled')->count(),
            'top_products' => SaleDetail::join('sales', 'sale_details.sale_id', '=', 'sales.id')
                ->join('products', 'sale_details.product_id', '=', 'products.id')
                ->whereBetween('sales.sale_date', [$dateFrom, $dateTo])
                ->where('sales.status', 'completed')
                ->when(!$user->isAdmin(), function($query) use ($user) {
                    return $query->where('sales.user_id', $user->id);
                })
                ->select('products.product_name', DB::raw('SUM(sale_details.quantity) as total_sold'))
                ->groupBy('products.id', 'products.product_name')
                ->orderBy('total_sold', 'desc')
                ->limit(10)
                ->get(),
            'daily_sales' => (clone $baseQuery)
                ->where('status', 'completed')
                ->select(
                    DB::raw('DATE(sale_date) as date'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(total_price) as revenue')
                )
                ->groupBy(DB::raw('DATE(sale_date)'))
                ->orderBy('date')
                ->get()
        ];

        return response()->json($stats);
    } catch (\Exception $e) {
        Log::error('Failed to get sales statistics: ' . $e->getMessage());
        return response()->json([
            'message' => 'Failed to get statistics: ' . $e->getMessage()
        ], 500);
    }
}

    // Print receipt
    public function printReceipt(Sale $sale)
    {
        try {
            $sale->load(['customer', 'user', 'saleDetails.product', 'payments']);

            return view('sales.receipt', compact('sale'));
        } catch (\Exception $e) {
            Log::error('Failed to generate receipt: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate receipt');
        }
    }

    // Bulk operations
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'sale_ids' => 'required|array',
            'sale_ids.*' => 'exists:sales,id',
            'action' => 'required|in:complete,cancel,pending',
        ]);

        DB::beginTransaction();
        try {
            $updatedCount = 0;

            foreach ($validated['sale_ids'] as $saleId) {
                $sale = Sale::find($saleId);

                // Skip if sale is already completed
                if ($sale->status === 'completed') {
                    continue;
                }

                $sale->update(['status' => $validated['action']]);

                // Handle customer purchase updates
                if ($validated['action'] === 'completed' && $sale->customer_id) {
                    $customer = Customer::find($sale->customer_id);
                    $customer->addPurchase($sale->total_price);
                }

                $updatedCount++;
            }

            DB::commit();

            return response()->json([
                'message' => "Successfully updated {$updatedCount} sales",
                'updated_count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to bulk update sales: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to bulk update sales: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
 * Search product by barcode for scanner
 */
public function searchByBarcode(Request $request)
{
    try {
        $barcode = $request->input('barcode');
        
        if (!$barcode) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode is required'
            ], 400);
        }

        $product = Product::where('barcode', $barcode)
            ->where('is_active', true)
            ->with(['category', 'batches' => function($query) {
                $query->available()->notExpired()->FEFO();
            }])
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found with barcode: ' . $barcode
            ], 404);
        }

        if ($product->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Product out of stock'
            ], 400);
        }

        // Get promotion data
        $promotions = $product->getActivePromotions();
        $pricePromotion = $promotions['price_promotion'];
        $quantityPromotion = $promotions['quantity_promotion'];
        
        $priceData = $product->getPriceWithMultiPromotions(1);
        
        // Get effective expiry info
        $nearestAvailableBatch = $product->batches()
            ->available()
            ->notExpired()
            ->FEFO()
            ->first();
        
        $productData = [
            'id' => $product->id,
            'product_name' => $product->product_name,
            'product_code' => $product->product_code,
            'barcode' => $product->barcode,
            'stock' => $product->stock,
            'minimum_stock' => $product->minimum_stock,
            'selling_price' => $product->selling_price,
            'final_price' => $priceData['final_price_per_unit'],
            'original_price' => $priceData['original_price'],
            'image' => $product->image,
            
            // Price promotion
            'price_promo_id' => $pricePromotion ? $pricePromotion->id : null,
            'price_discount' => $priceData['price_discount_per_unit'],
            'price_promo_type' => $pricePromotion ? $pricePromotion->type : null,
            
            // Quantity promotion
            'qty_promo_id' => $quantityPromotion ? $quantityPromotion->id : null,
            'buy_qty' => $quantityPromotion ? $quantityPromotion->buy_quantity : 0,
            'get_qty' => $quantityPromotion ? $quantityPromotion->get_quantity : 0,
            'qty_promo_type' => $quantityPromotion ? $quantityPromotion->type : null,
            
            // Expiry info
            'has_expiry' => $product->has_expiry,
            'expiry_date' => $nearestAvailableBatch ? $nearestAvailableBatch->expiry_date->format('Y-m-d') : null,
            'batch_number' => $nearestAvailableBatch ? $nearestAvailableBatch->batch_number : null,
        ];

        return response()->json([
            'success' => true,
            'product' => $productData
        ]);

    } catch (\Exception $e) {
        \Log::error('Barcode search error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error searching product: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * ✅ Print receipt via thermal printer
 */
public function printThermal(Sale $sale)
{
    try {
        // ✅ Load relationships first
        $sale->load(['customer', 'user', 'saleDetails.product', 'saleDetails.pricePromotion', 'saleDetails.quantityPromotion', 'payments']);
        
        $printService = new \App\Services\ThermalPrintService();
        $result = $printService->printReceipt($sale);

        // ✅ ALWAYS return JSON
        return response()->json($result);

    } catch (\Exception $e) {
        Log::error('Print thermal error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Print failed: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * ✅ Get available printer ports
 */
public function getPrinterPorts()
{
    $printService = new \App\Services\ThermalPrintService();
    $result = $printService->getAvailablePorts();

    return response()->json($result);
}

/**
 * ✅ Test thermal printer
 */
public function testThermalPrinter(Request $request)
{
    $printService = new \App\Services\ThermalPrintService();
    $result = $printService->testPrint($request->input('port'));

    return response()->json($result);
}

// Tambahkan di dalam SaleController, setelah method create()

public function storeCustomerFromSales(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'phone_number' => 'nullable|string|max:15',
        'email' => 'nullable|email|max:255|unique:customers,email',
        'gender' => 'nullable|in:M,F',
        'birth_date' => 'nullable|date',
    ]);

    try {
        $validated['is_active'] = true;
        $validated['total_purchase'] = 0;
        $validated['loyalty_points'] = 0;

        $customer = Customer::create($validated);

        // ✅ TAMBAHKAN INI: Send Welcome Notifications
        try {
            if ($customer->email) {
                \Illuminate\Support\Facades\Mail::to($customer->email)
                    ->send(new \App\Mail\CustomerWelcomeMail($customer));
            }

            if ($customer->phone_number) {
                $customerController = new CustomerController();
                $customerController->sendWelcomeWhatsApp($customer);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send welcome notifications from sales', [
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'customer' => [
                'id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'phone_number' => $customer->phone_number,
                'email' => $customer->email,
                'loyalty_points' => $customer->loyalty_points,
                'photo_profile' => null
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to create customer from sales: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to create customer: ' . $e->getMessage()
        ], 422);
    }
}

}