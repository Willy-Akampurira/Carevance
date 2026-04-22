<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\PerformanceReport;
use Carbon\Carbon;

class GenerateReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run with: php artisan reports:generate
     */
    protected $signature = 'reports:generate';

    /**
     * The console command description.
     */
    protected $description = 'Automatically generate monthly performance reports from attendance hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = Carbon::now()->startOfMonth();
        $end   = Carbon::now()->endOfMonth();

        // Aggregate attendance hours per staff
        $attendance = Attendance::whereBetween('date', [$start, $end])
            ->selectRaw('staff_id, SUM(TIMESTAMPDIFF(HOUR, clock_in, clock_out)) as total_hours')
            ->groupBy('staff_id')
            ->get();

        foreach ($attendance as $record) {
            PerformanceReport::updateOrCreate(
                [
                    'staff_id'     => $record->staff_id,
                    'period_start' => $start->toDateString(),
                    'period_end'   => $end->toDateString(),
                ],
                [
                    'title'               => 'Performance Report ' . $start->format('F Y'),
                    'score'               => $record->total_hours,
                    'total_hours'         => $record->total_hours,
                    'remarks'             => 'Auto-generated from attendance records',
                    'generated_by_system' => true,
                ]
            );
        }

        $this->info('Performance reports generated successfully for period: ' . $start->format('F Y'));
    }
}
