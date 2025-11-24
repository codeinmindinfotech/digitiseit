<?php

namespace App\Imports;

use App\Models\Document;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SimpleImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        return $rows;
    }
    // public function collection(Collection $rows)
    // {
    //     $sourceFolder = public_path('allFiles'); // Folder where PDFs exist
    //     $disk = Storage::disk('public');
    //     $overallLog = [];

    //     foreach ($rows as $row) {

    //         if (empty($row['usersid']) || empty($row['documentname'])) {
    //             continue;
    //         }

    //         $rawDate = trim($row['dateuploaded'] ?? '');

    //         if (is_numeric($rawDate)) {
    //             $uploadedDate = Carbon::createFromDate(1899, 12, 30)->addDays((int)$rawDate)->format('Y-m-d');
    //         } else {
    //             try {
    //                 $uploadedDate = Carbon::parse($rawDate)->format('Y-m-d');
    //             } catch (\Exception $e) {
    //                 $uploadedDate = now()->format('Y-m-d');
    //             }
    //         }

    //         if (empty($pdfName) || empty($pdfDir) || empty($userId)) continue;

    //         $sourcePdf = $sourceFolder . '/' . $pdfName;

    //         if (file_exists($sourcePdf)) {
    //             $targetDir = "{$company?->folder_path}/{$pdfDir}";
    //             if (!$disk->exists($targetDir)) {
    //                 $disk->makeDirectory($targetDir);
    //             }
        
    //             $pdfPath = $targetDir . '/' . $pdfName;
    //             $disk->put($pdfPath, file_get_contents($sourcePdf));
        
    //             Document::create([
    //                 'company_id' => $company?->id,
    //                 'directory'  => $pdfDir,
    //                 'filename'   => $pdfName,
    //                 'filepath'   => $pdfPath,
    //             ]);
        
    //             $log['uploaded'][] = ['filename' => $pdfName, 'target' => $targetDir];
    //         } else {
    //             $log['missing'][] = ['filename' => $pdfName, 'directory' => $pdfDir];
    //         }

            
    //         ExcelDocuments::create([
    //             'user_id'           => $row['usersid'],
    //             'document_id'       => $this->documentId,
    //             'search_field'      => $row['datasearchfield'] ?? null,
    //             'document_name'     => $row['documentname'],
    //             'document_directory'=> $row['documentdirectory'] ?? 'Unknown',
    //             'uploaded_at' => $uploadedDate,
    //         ]);
    //     }
    //     dd("111");
    //                 $logDir = storage_path('logs'); // points to storage/logs
    //                 if (!file_exists($logDir)) {
    //                     mkdir($logDir, 0777, true); // create folder if it doesn't exist
    //                 }

    //                 $logFile = $logDir . '/upload_log_' . pathinfo($fileName, PATHINFO_FILENAME) . '.json';
    //                 file_put_contents($logFile, json_encode($log, JSON_PRETTY_PRINT));
    //                 $overallLog[$fileName] = $log;
    // }
}

