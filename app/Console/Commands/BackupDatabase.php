<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--compress}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database to a file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        try {
            $connection = DB::connection();
            $database = $connection->getDatabaseName();
            $username = $connection->getConfig('username');
            $password = $connection->getConfig('password');
            $host = $connection->getConfig('host');
            $port = $connection->getConfig('port') ?? 3306;

            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$database}_{$timestamp}.sql";
            $filepath = storage_path("app/backups/{$filename}");

            // Ensure backups directory exists
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            // Build mysqldump command
            $command = sprintf(
                'mysqldump -h %s -P %s -u %s -p%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            // Execute backup
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Database backup failed');
            }

            // Compress if requested
            if ($this->option('compress')) {
                $compressedFile = $filepath . '.gz';
                exec("gzip -c {$filepath} > {$compressedFile}");
                unlink($filepath);
                $filepath = $compressedFile;
                $filename = basename($compressedFile);
            }

            $this->info("Database backup completed: {$filename}");
            
            // Clean old backups (keep last 7 days)
            $this->cleanOldBackups();

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            \Log::error('Database backup error', ['error' => $e->getMessage()]);
            return Command::FAILURE;
        }
    }

    /**
     * Clean old backup files
     */
    protected function cleanOldBackups()
    {
        $backupDir = storage_path('app/backups');
        $files = glob($backupDir . '/*.{sql,sql.gz}', GLOB_BRACE);
        $cutoffDate = Carbon::now()->subDays(7);

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffDate->timestamp) {
                unlink($file);
                $this->info("Deleted old backup: " . basename($file));
            }
        }
    }
}

