<?php

namespace App\Filament\Widgets;

use App\Models\SuratKeluar;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ExampleChart extends ChartWidget
{
protected static ?string $heading = 'Surat Keluar per Bulan';

    protected function getData(): array
    {
        $data = SuratKeluar::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', date('Y'))  // Mengambil data tahun ini
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Inisialisasi array dengan 12 bulan
        $monthlyData = array_fill(1, 12, 0);

        // Mengisi data yang ada
        foreach ($data as $month => $total) {
            $monthlyData[$month] = $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Surat Keluar',
                    'data' => array_values($monthlyData),
                    'borderColor' => '#1c64f2',
                    'fill' => false,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    public static function getSort(): int
    {
        return 9; // Semakin besar angkanya, semakin ke bawah letaknya
    }

    protected function getType(): string
    {
        return 'line';
    }
}
