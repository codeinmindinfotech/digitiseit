<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SimpleImport;
use Illuminate\Support\Facades\Storage;

class ProcessExcelFiles extends Command
{
    protected $signature = 'excel:process {--source=} {--disk=public} {--uploads=uploads}';
    protected $description = 'Process all Excel files from a server folder and upload referenced PDFs.';

    public function handle()
    {
        $sourceFolder = $this->option('source') ?? storage_path('app/allFiles');
        $diskName = $this->option('disk');
        $uploadBase = rtrim($this->option('uploads'), '/');

        $disk = Storage::disk($diskName);

        if (!is_dir($sourceFolder)) {
            $this->error("Source folder not found: {$sourceFolder}");
            return 1;
        }

        $excelFiles = glob($sourceFolder . '/*.xls*');

        if (empty($excelFiles)) {
            $this->info("No Excel files found in: {$sourceFolder}");
            return 0;
        }

        foreach ($excelFiles as $excelPath) {
            $this->info("Processing Excel file: " . basename($excelPath));

            // Load Excel rows
            $rows = Excel::toCollection(new SimpleImport, $excelPath)->first();

            $log = [
                'uploaded' => [],
                'missing' => [],
            ];

            foreach ($rows as $row) {
                $filename  = trim($row['documentname'] ?? '');
                $directory = trim($row['documentdirectory'] ?? '');

                if (empty($filename) || empty($directory)) {
                    continue;
                }

                $pdfPath = $sourceFolder . '/' . $filename;

                if (file_exists($pdfPath)) {
                    // Ensure target directory exists
                    $targetDir = "{$uploadBase}/{$directory}";
                    if (!$disk->exists($targetDir)) {
                        $disk->makeDirectory($targetDir);
                    }

                    // Copy PDF to upload disk
                    $disk->put($targetDir . '/' . $filename, file_get_contents($pdfPath));

                    $log['uploaded'][] = [
                        'filename' => $filename,
                        'target' => $targetDir,
                    ];
                } else {
                    $log['missing'][] = [
                        'filename' => $filename,
                        'directory' => $directory,
                    ];
                }
            }

            // Save log file
            $logFile = storage_path('app/logs/upload_log_' . pathinfo($excelPath, PATHINFO_FILENAME) . '.json');
            file_put_contents($logFile, json_encode($log, JSON_PRETTY_PRINT));

            $this->info("Finished: " . basename($excelPath));
            $this->info("Uploaded: " . count($log['uploaded']));
            $this->info("Missing: " . count($log['missing']));
            $this->info("Log saved to: {$logFile}");
            $this->line("------------------------------------------------");
        }

        $this->info("All Excel files processed.");
        return 0;
    }
}
