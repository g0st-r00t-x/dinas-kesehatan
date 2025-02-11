<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TreeChart extends ChartWidget
{
    protected static ?string $heading = 'Sertifikasi Pegawai';

    protected function getData(): array
    {
        return [
            'labels' => ['Valid', 'Expired', 'Pending'],
            'datasets' => [
                [
                    'label' => 'Jumlah Sertifikasi',
                    'data' => [35, 15, 10], // Contoh jumlah sertifikasi untuk masing-masing status
                    'backgroundColor' => ['#4CAF50', '#F44336', '#FFEB3B'],
                ],
            ],
        ];
    }

    public static function getSort(): int
    {
        return 10; // Semakin besar angkanya, semakin ke bawah letaknya
    }
    protected function getType(): string
    {
        return 'pie';
    }
}
