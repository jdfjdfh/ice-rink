<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Skate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;
use YooKassa\Model\Payment\PaymentStatus;

class BookingController extends Controller
{
    private function getYooKassaClient(): Client
    {
        $shopId = config('yookassa.shop_id') ?: env('YOOKASSA_SHOP_ID');
        $secretKey = config('yookassa.secret_key') ?: env('YOOKASSA_SECRET_KEY');

        $client = new Client();
        $client->setAuth($shopId, $secretKey);
        return $client;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'fio' => 'required|string|max:255',
                'phone' => 'required|string|max:18',
                'hours' => 'required|in:1,2,3,4',
                'skate_id' => 'nullable|exists:skates,id'
            ]);

            $phone = preg_replace('/[^0-9]/', '', $validated['phone']);
            if (strlen($phone) === 11) {
                $phoneForYooKassa = $phone;
            } elseif (strlen($phone) === 10) {
                $phoneForYooKassa = '7' . $phone;
            } else {
                $phoneForYooKassa = $phone;
            }

            $ticketPrice = 300;
            $skatePrice = 150;
            $totalAmount = $ticketPrice;

            $hasSkates = $request->has('needSkates') && $request->filled('skate_id');
            if ($hasSkates) {
                $totalAmount += $skatePrice * $request->hours;
            }

            $skateModel = null;
            $skateSize = null;
            if ($hasSkates) {
                $skate = Skate::find($request->skate_id);
                if ($skate) {
                    $skateModel = $skate->model;
                    $skateSize = $skate->size;
                }
            }

            $booking = Booking::create([
                'fio' => $validated['fio'],
                'phone' => $validated['phone'],
                'hours' => $validated['hours'],
                'skate_id' => $hasSkates ? $request->skate_id : null,
                'skate_model' => $skateModel,
                'skate_size' => $skateSize,
                'total_amount' => $totalAmount,
                'has_skates' => $hasSkates,
                'status' => 'pending',
                'is_paid' => false
            ]);

            try {
                $client = $this->getYooKassaClient();

                $paymentData = [
                    'amount' => [
                        'value' => number_format($totalAmount, 2, '.', ''),
                        'currency' => 'RUB',
                    ],
                    'confirmation' => [
                        'type' => 'redirect',
                        'return_url' => route('payment.pending', $booking->id),
                    ],
                    'capture' => true,
                    'description' => "Бронирование катка #{$booking->id}",
                    'metadata' => [
                        'booking_id' => $booking->id,
                    ],
                ];

                if (!empty($phoneForYooKassa) && strlen($phoneForYooKassa) >= 10) {
                    $items = [
                        [
                            'description' => 'Входной билет на каток',
                            'quantity' => '1.00',
                            'amount' => [
                                'value' => number_format($ticketPrice, 2, '.', ''),
                                'currency' => 'RUB',
                            ],
                            'vat_code' => 1,
                            'payment_mode' => 'full_prepayment',
                            'payment_subject' => 'service',
                        ]
                    ];

                    if ($hasSkates) {
                        $items[] = [
                            'description' => "Аренда коньков ({$request->hours} ч)",
                            'quantity' => '1.00',
                            'amount' => [
                                'value' => number_format($skatePrice * $request->hours, 2, '.', ''),
                                'currency' => 'RUB',
                            ],
                            'vat_code' => 1,
                            'payment_mode' => 'full_prepayment',
                            'payment_subject' => 'service',
                        ];
                    }

                    $paymentData['receipt'] = [
                        'customer' => [
                            'full_name' => $validated['fio'],
                            'phone' => $phoneForYooKassa,
                        ],
                        'items' => $items
                    ];
                }

                $payment = $client->createPayment(
                    $paymentData,
                    uniqid('booking_', true)
                );

                $booking->update([
                    'payment_id' => $payment->getId(),
                    'payment_url' => $payment->getConfirmation()->getConfirmationUrl(),
                ]);

                return redirect($payment->getConfirmation()->getConfirmationUrl());

            } catch (\Exception $e) {
                $booking->update(['status' => 'failed']);
                return back()->with('error', 'Ошибка при создании платежа: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка валидации: ' . $e->getMessage());
        }
    }

    public function pending(Booking $booking)
    {
        if ($booking->is_paid && $booking->status === 'paid') {
            return redirect()->route('payment.success', $booking);
        }

        if ($booking->status === 'failed' || $booking->status === 'cancelled') {
            return redirect()->route('payment.cancel', $booking);
        }

        return view('payment.pending', compact('booking'));
    }

    public function success(Booking $booking)
    {
        if (!$booking->is_paid || $booking->status !== 'paid') {
            return redirect()->route('payment.pending', $booking);
        }

        return view('payment.success', compact('booking'));
    }

    public function cancel(Booking $booking, Request $request)
    {
        $timeout = $request->get('timeout', false);

        if ($booking->status !== 'failed' && $booking->status !== 'cancelled') {
            $booking->update([
                'status' => 'failed',
                'is_paid' => false
            ]);
        }

        return view('payment.cancel', [
            'booking' => $booking,
            'timeout' => $timeout
        ]);
    }

    private function markAsPaid(Booking $booking)
    {
        if ($booking->has_skates && $booking->skate_id) {
            $skate = Skate::find($booking->skate_id);
            if ($skate && $skate->quantity > 0) {
                $skate->decrement('quantity');
            }
        }

        $booking->update([
            'status' => 'paid',
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        return true;
    }

    public function checkStatus(Booking $booking)
    {
        try {
            if (empty($booking->payment_id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No payment ID'
                ]);
            }

            $client = $this->getYooKassaClient();
            $payment = $client->getPaymentInfo($booking->payment_id);
            $status = $payment->getStatus();

            $response = [
                'status' => $status,
                'paid' => $status === PaymentStatus::SUCCEEDED
            ];

            if ($status === PaymentStatus::SUCCEEDED && !$booking->is_paid) {
                $this->markAsPaid($booking);
                $response['updated'] = true;
                $response['redirect'] = route('payment.success', $booking);
            } elseif ($status === PaymentStatus::CANCELED && $booking->status !== 'failed') {
                $booking->update([
                    'status' => 'failed',
                    'is_paid' => false
                ]);
                $response['redirect'] = route('payment.cancel', $booking);
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        $data = $request->all();

        if (isset($data['object']['id'])) {
            $paymentId = $data['object']['id'];
            $status = $data['object']['status'] ?? null;

            $booking = Booking::where('payment_id', $paymentId)->first();

            if ($booking) {
                Log::info('Webhook: booking found', [
                    'booking_id' => $booking->id,
                    'old_status' => $booking->status,
                    'new_status' => $status
                ]);

                if ($status === 'succeeded') {
                    $this->markAsPaid($booking);
                } elseif ($status === 'canceled') {
                    $booking->update([
                        'status' => 'failed',
                        'is_paid' => false
                    ]);
                    Log::info('Booking cancelled via webhook', [
                        'booking_id' => $booking->id
                    ]);
                }
            }
        }

        return response('OK', 200);
    }
}
