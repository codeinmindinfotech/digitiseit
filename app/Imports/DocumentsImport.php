<?php

namespace App\Imports;

use App\Models\Document;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DocumentsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip empty rows
            if (!isset($row['file_name']) || empty($row['file_name'])) {
                continue;
            }

            Document::create([
                'company_id'     => $row['company_id'] ?? null,   // optional
                'directory_name' => $row['directory_name'] ?? 'default',
                'file_name'      => $row['file_name'],
                'file_path'      => $row['file_path'] ?? null,
            ]);
        }
    }
}

