<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Xendit\Invoice\CreateInvoiceRequest;

class PaymentService
{
    public function createPaymentData(Order $order): Payment {
        return Payment::query()->create([
            'order_id' => $order->id,
            'external_id' => 'INV-' . uniqid(),
            'amount' => $order->orderDetail->amount,
        ]);
    }

    public function createInvoiceRequest(Payment $payment) {
        return new CreateInvoiceRequest([
            'external_id' => $payment->external_id,
            'description' => 'Test Invoice',
            'amount' => $payment->amount,
            'invoice_duration' => 172800,
            'currency' => 'IDR',
            'reminder_time' => 1
        ]);
    }

    public function processWebhook($payload) {
        $payment = Payment::query()->where('external_id', $payload['external_id'])->first();

        if(!$payment) {
            Log::info('Payment not found: ', [$payload['external_id']]);
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }

        $order = Order::query()->find($payment->order_id);

        if ($payload['status'] === 'PAID') {
            $order->update([
                'status' => 'lunas'
            ]);
            
            $order->orderDetail()->update([
                'payment_status' => 'paid'
            ]);

            $payment->update([
                'status' => 'success',
                'payment_method' => $payload['payment_method'],
                'payment_status' => 'PAID'
            ]);

            Log::info('Payment & order updated successfully with status SUCCESS: ', [$payment, $order]);

        } elseif ($payload['status'] === 'FAILED') {
            $order->orderDetail()->update([
                'payment_status' => 'unpaid'
            ]);

            $payment->update([
                'status' => 'failed',
                'payment_status' => 'FAILED',
            ]);
            
            Log::info('Payment & order updated successfully with status FAILED: ', [$payment, $order]);

        } else {
            Log::info('Payment status not handled: ', [$payload['status']]);
            return response()->json([
                'status' => 'error',
                'message' => 'Payment status not handled'
            ], 400);
        }
    }
}
