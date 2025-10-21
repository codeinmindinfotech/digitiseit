<?php

namespace App\Imports;

use App\Models\Document;
use App\Models\ExcelDocuments;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class DocumentsImport implements ToCollection, WithHeadingRow
{
    protected $documentId;

    public function __construct($documentId)
    {
        $this->documentId = $documentId;
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (empty($row['usersid']) || empty($row['documentname'])) {
                continue;
            }

            $rawDate = trim($row['dateuploaded'] ?? '');

            if (is_numeric($rawDate)) {
                $uploadedDate = Carbon::createFromDate(1899, 12, 30)->addDays((int)$rawDate)->format('Y-m-d');
            } else {
                try {
                    $uploadedDate = Carbon::parse($rawDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    $uploadedDate = now()->format('Y-m-d');
                }
            }

            
            ExcelDocuments::create([
                'user_id'           => $row['usersid'],
                'document_id'       => $this->documentId,
                'search_field'      => $row['datasearchfield'] ?? null,
                'document_name'     => $row['documentname'],
                'document_directory'=> $row['documentdirectory'] ?? 'Unknown',
                'uploaded_at' => $uploadedDate,
            ]);
        }
    }
}

