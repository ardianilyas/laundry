<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use Xendit\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Xendit\Invoice\InvoiceApi;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }

    public function createInvoice(Order $order) {
        // dd($order->orderDetail->amount);
        $payment = $this->paymentService->createPaymentData($order);

        $create_invoice_request = $this->paymentService->createInvoiceRequest($payment); 

        $apiInstance = new InvoiceApi();

        $for_user_id = null;

        $result = $apiInstance->createInvoice($create_invoice_request, $for_user_id);

        $order->orderDetail()->update([
            'invoice_url' => $result->getInvoiceUrl(),
        ]);

        return back();
    }

    public function handleWebhook(Request $request) {
        Log::info('Webhook received: ', [$request->all()]);
        $callbackToken = $request->header('x-callback-token');
        $xenditCallbackToken = env('XENDIT_WEBHOOK_SECRET');

        if ($callbackToken !== $xenditCallbackToken) {
            Log::info('Invalid webhook signature: ', [$callbackToken]);
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid webhook signature'
            ], 403);
        }

        $payload = $request->all();

        return $this->paymentService->processWebhook($payload);
    }
}
