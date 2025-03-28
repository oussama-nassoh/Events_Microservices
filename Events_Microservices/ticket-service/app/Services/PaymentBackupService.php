<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;

class PaymentBackupService
{
    protected $database;

    public function __construct()
    {
        $this->database = (new Factory)
        ->withServiceAccount(config('firebase.credentials_path'))
        ->withDatabaseUri(config('firebase.database_url'))
        ->createDatabase();
    }

    public function backupPayments()
    {
        $payments = Payment::whereNull('last_backup_at')
            ->orWhere('last_backup_at', '<', now()->subDay())
            ->get();

        if ($payments->isEmpty()) {
            return;
        }

        $reference = $this->database->getReference('payment_backups/' . date('Y/m/d/H_i_s'));

        foreach ($payments as $payment) {
            $paymentData = [
                'id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'amount' => (float)$payment->amount,
                'status' => $payment->status,
                'payment_method' => $payment->payment_method,
                'payment_details' => json_encode($payment->payment_details),
                'backup_date' => now()->toDateTimeString()
            ];

            // Add to database with payment ID as key
            $reference->getChild($payment->id)->set($paymentData);

            // Update backup timestamp
            $payment->update(['last_backup_at' => now()]);
        }

        Log::info('Payment data backed up to Firebase Realtime Database', [
            'count' => $payments->count(),
            'path' => 'payment_backups/' . date('Y/m/d/H_i_s')
        ]);
    }
}