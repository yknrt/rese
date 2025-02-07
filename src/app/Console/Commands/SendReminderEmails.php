<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;
use App\Models\Reservation;
use Carbon\Carbon;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-reminders';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リマインダーメールを送信';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 今日の予約を取得
        $reservations = Reservation::where('date', Carbon::today())->get();

        foreach ($reservations as $reservation) {
            $data = [
                'name' => $reservation->user->name,
                'email' => $reservation->user->email,
                'shop' => $reservation->shop->name,
                'date' => $reservation->date,
                'time' => $reservation->time
            ];

            Mail::to($reservation->user->email)->send(new ReminderMail($data));
        }

        $this->info('リマインダーメールを送信しました');
    }
}
