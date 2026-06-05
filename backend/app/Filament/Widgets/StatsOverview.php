<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $sessionsThisWeek = \App\Models\ExpertSession::where('started_at', '>=', now()->startOfWeek())->count();
        $sessionsThisMonth = \App\Models\ExpertSession::where('started_at', '>=', now()->startOfMonth())->count();
        
        $topConclusion = \App\Models\ExpertSession::whereNotNull('conclusion_id')
            ->select('conclusion_id')
            ->selectRaw('count(*) as count')
            ->groupBy('conclusion_id')
            ->orderByDesc('count')
            ->with('conclusion')
            ->first();

        $topConclusionLabel = $topConclusion ? $topConclusion->conclusion->label : 'Belum Ada Data';

        return [
            Stat::make('Sesi Minggu Ini', $sessionsThisWeek)
                ->description('Total sesi konsultasi sejak awal minggu')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Sesi Bulan Ini', $sessionsThisMonth)
                ->description('Total sesi konsultasi bulan ini')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Konklusi Terbanyak', $topConclusionLabel)
                ->description('Klasifikasi pelanggaran paling sering muncul')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}
