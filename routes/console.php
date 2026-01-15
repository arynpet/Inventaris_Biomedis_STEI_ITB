<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Loan;
use App\Notifications\OverdueItemNotification;
use Carbon\Carbon;

// Inspire Command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// =====================================================
// Scheduled Task: Send Overdue Notifications
// =====================================================
Schedule::call(function () {
    // Get all loans that are:
    // 1. Status is 'active' or 'approved' (not returned yet)
    // 2. return_date is in the past (overdue)
    $overdueLoans = Loan::with(['item', 'user'])
        ->whereIn('status', ['active', 'approved'])
        ->where('return_date', '<', Carbon::today())
        ->get();

    $count = 0;
    foreach ($overdueLoans as $loan) {
        if ($loan->user && $loan->user->email) {
            try {
                $loan->user->notify(new OverdueItemNotification($loan));
                $count++;
            } catch (\Exception $e) {
                \Log::error('Failed to send overdue notification: ' . $e->getMessage());
            }
        }
    }

    \Log::info("Overdue notifications sent: {$count} emails");
})->dailyAt('08:00')->name('send-overdue-notifications');

// =====================================================
// Optional: Test Command for Overdue Notifications
// =====================================================
Artisan::command('notify:overdue', function () {
    $overdueLoans = Loan::with(['item', 'user'])
        ->whereIn('status', ['active', 'approved'])
        ->where('return_date', '<', Carbon::today())
        ->get();

    if ($overdueLoans->isEmpty()) {
        $this->info('No overdue loans found.');
        return;
    }

    $count = 0;
    foreach ($overdueLoans as $loan) {
        if ($loan->user && $loan->user->email) {
            $loan->user->notify(new OverdueItemNotification($loan));
            $count++;
            $this->line("Sent to: {$loan->user->email} ({$loan->item->name})");
        }
    }

    $this->info("Overdue notifications sent to {$count} users.");
})->purpose('Manually send overdue item notifications');
