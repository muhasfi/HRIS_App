<?php

namespace App\Console\Commands;

use App\Models\EmployeeSchedule;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateAlpha extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-alpha';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $today = $now->toDateString();
        $dayOfWeek = $now->dayOfWeek;

        $schedules = EmployeeSchedule::where('day_of_week', $dayOfWeek)->get();

        foreach ($schedules as $schedule) {

            // Gabungkan tanggal + jam pulang
            $endTimeToday = Carbon::parse($today . ' ' . $schedule->end_time);

            // Tambahkan toleransi 10 menit
            $endTimeWithTolerance = $endTimeToday->copy()->addMinutes(10);

            // Kalau sekarang belum lewat jam pulang + toleransi → skip
            if ($now->lt($endTimeWithTolerance)) {
                continue;
            }

            // Ambil presence hari ini (kalau ada)
            $presence = Presence::where('employee_id', $schedule->employee_id)
                ->whereDate('date', $today)
                ->first();

            // Kalau tidak ada presence sama sekali → buat alpha
            if (!$presence) {
                Presence::create([
                    'employee_id' => $schedule->employee_id,
                    'date' => $today,
                    'status' => 'alpha',
                ]);
                continue;
            }

            // Kalau ada presence tapi tidak check-in dan tidak check-out → update jadi alpha
            if (is_null($presence->check_in) && is_null($presence->check_out)) {
                $presence->update([
                    'status' => 'alpha',
                ]);
            }
        }
    }
}
