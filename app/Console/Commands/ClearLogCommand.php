<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogCommand extends Command
{
    /**
     * Nama dan signature dari command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * Deskripsi dari command.
     *
     * @var string
     */
    protected $description = 'Clear the Laravel log file';

    /**
     * Jalankan command.
     */
    public function handle()
    {
        // Path ke file log Laravel
        $logPath = storage_path('logs/laravel.log');

        // Periksa apakah file log ada
        if (!File::exists($logPath)) {
            $this->warn('Log file does not exist.');
            return;
        }

        try {
            // Kosongkan isi file log
            File::put($logPath, '');

            // Tampilkan pesan sukses
            $this->info('Log file has been cleared successfully.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi
            $this->error('Failed to clear log file: ' . $e->getMessage());
        }
    }
}
