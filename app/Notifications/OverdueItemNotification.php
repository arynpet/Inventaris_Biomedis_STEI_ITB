<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class OverdueItemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $loan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $itemName = $this->loan->item->name ?? 'Barang';
        $returnDate = Carbon::parse($this->loan->return_date)->format('d F Y');
        $daysOverdue = Carbon::parse($this->loan->return_date)->diffInDays(Carbon::now());

        return (new MailMessage)
            ->subject('⏰ Pengingat: Peminjaman Barang Lab Sudah Lewat Jatuh Tempo')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Kami ingin mengingatkan bahwa peminjaman Anda sudah lewat jatuh tempo.')
            ->line('**Detail Peminjaman:**')
            ->line('- **Barang**: ' . $itemName)
            ->line('- **Tanggal Seharusnya Kembali**: ' . $returnDate)
            ->line('- **Terlambat**: ' . $daysOverdue . ' hari')
            ->line('Harap segera mengembalikan barang ke laboratorium untuk menghindari sanksi akademik.')
            ->action('Lihat Detail Peminjaman', route('student.loans.index'))
            ->line('Terima kasih atas perhatiannya!')
            ->salutation('Salam, Tim Lab Biomedis STEI ITB');
    }

    /**
     * Get the array representation (for database notifications).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'loan_id' => $this->loan->id,
            'item_name' => $this->loan->item->name ?? 'Barang',
            'return_date' => $this->loan->return_date,
            'days_overdue' => Carbon::parse($this->loan->return_date)->diffInDays(Carbon::now()),
            'message' => 'Peminjaman Anda sudah lewat jatuh tempo. Harap segera kembalikan.',
        ];
    }
}
