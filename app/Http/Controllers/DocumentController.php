<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DocumentsImport; // optional for Excel import
use App\Imports\SimpleImport;
use App\Models\ExcelDocuments;
use App\Models\User;
use Carbon\Carbon;

class DocumentController extends Controller
{
    public function mainIndex(Request $request)
    {
        $company_id = $request->get('company_id');
        $documents = Document::companyOnly()->when($company_id, fn($q) => $q->where('company_id', $company_id))
            ->get();
        $companies = Company::all();
        return view('documents.list', compact('documents', 'companies'));
    }

    public function index(Request $request)
    {
        // $company_id = $request->get('company_id');

        // Get companies with document counts for each type (PDF, Excel, Word)
        $companies = Company::companyOnly()->withCount('documents')->get();

        // Get all documents
        $documents = Document::companyOnly()->get();

        // Group documents by company and type
        $documentsGrouped = $documents->groupBy('company_id');

        return view('documents.index', compact('documentsGrouped', 'companies'));
    }

    public function uploadForm()
    {
        $companies = Company::companyOnly()->get();
        return view('documents.upload', compact('companies'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'company_id' => 'nullable|integer',
            'directory_name' => 'required|string|max:255',
            'files.*' => 'required|file', // allow multiple files
        ]);

        $companyId = $request->company_id;
        $company = is_numeric($companyId) ? Company::find($companyId) : null;
        $dirName = rtrim($request->directory_name, '/') ?: ($company?->folder_path ?? 'default');

        $sourceFolder = public_path('allFiles'); // Folder where PDFs exist
        $disk = Storage::disk('public');
        $overallLog = [];

        foreach ($request->file('files') as $file) {
            $fileName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());

            // If Excel, process rows and copy PDFs based on company folder path
            if (in_array($extension, ['xls', 'xlsx'])) {
                try {
                    $allSheets = Excel::toCollection(new SimpleImport, $file);
                    $log = ['uploaded' => [], 'missing' => []];

                    foreach ($allSheets as $sheet) {
                        foreach ($sheet as $row) {

                            // Skip metadata rows if needed
                            if (isset($row['usersid']) && $row['usersid'] === 'UsersID') continue;

                            $userId  = $row['usersid'] ?? null;
                            $pdfName = $row['documentname'] ?? null;
                            $pdfDir  = $row['documentdirectory'] ?? null;
                            $searchField  = $row['datasearchfield'] ?? null;
                            $rawDate = trim($row['DateUploaded'] ?? '');

                            if (is_numeric($rawDate)) {
                                $uploadedAt = Carbon::createFromDate(1899, 12, 30)->addDays((int)$rawDate)->format('Y-m-d');
                            } else {
                                try {
                                    $uploadedAt = Carbon::parse($rawDate)->format('Y-m-d');
                                } catch (\Exception $e) {
                                    $uploadedAt = now()->format('Y-m-d');
                                }
                            }
                            // \Log::error('Excel searchField: ' . $row);
                            // \Log::error('Excel uploadedAt: ' . $uploadedAt);

                            if (empty($userId) || empty($pdfName) || empty($pdfDir)) continue;
                            // Now you can process PDFs as before
                            // $user = is_numeric($userId) ? User::find($userId) : null;
                            // $companyId = $user->company_id;
                            $company = is_numeric($userId) ? Company::find($userId) : null;
                            $dirName = rtrim($request->directory_name, '/') ?: ($company?->folder_path ?? 'default');


                            $sourcePdf = $sourceFolder . '/' . $pdfName;


                            if (file_exists($sourcePdf)) {
                                $targetDir = "uploads/{$company?->folder_path}/{$pdfDir}";
                                if (!$disk->exists($targetDir)) {
                                    $disk->makeDirectory($targetDir);
                                }

                                $pdfPath = $targetDir . '/' . $pdfName;
                                $disk->put($pdfPath, file_get_contents($sourcePdf));

                                Document::create([
                                    'company_id' => $company?->id,
                                    'directory'  => $pdfDir,
                                    'filename'   => $pdfName,
                                    'filepath'   => $pdfPath,
                                    'search_field' => $searchField,
                                    'uploaded_at' => $uploadedAt
                                ]);

                                $log['uploaded'][] = ['filename' => $pdfName, 'target' => $targetDir];
                            } else {
                                $log['missing'][] = ['filename' => $pdfName, 'directory' => $pdfDir];
                            }
                        }
                    }
                    // Save log per Excel file
                    // dd("111");
                    $logDir = storage_path('logs'); // points to storage/logs
                    if (!file_exists($logDir)) {
                        mkdir($logDir, 0777, true); // create folder if it doesn't exist
                    }

                    $logFile = $logDir . '/upload_log_' . pathinfo($fileName, PATHINFO_FILENAME) . '.json';
                    file_put_contents($logFile, json_encode($log, JSON_PRETTY_PRINT));
                    $overallLog[$fileName] = $log;
                } catch (\Exception $e) {
                    \Log::error('Excel import failed: ' . $e->getMessage());
                }
            } else {
                // Non-Excel files (PDFs, etc.) — use old code
                $dirPath = "uploads/{$dirName}";
                if (!$disk->exists($dirPath)) {
                    $disk->makeDirectory($dirPath);
                }

                $filePath = $file->storeAs($dirPath, $fileName, 'public');

                // Create document record
                Document::create([
                    'company_id' => $company?->id,
                    'directory' => $dirName,
                    'filename' => $fileName,
                    'filepath' => $filePath,
                ]);
            }
        }

        return view('documents.upload-result', [
            'overallLog' => $overallLog
        ]);
    }

    public function clientView(Request $request)
    {
        $search = $request->get('search');
        $company_id = auth()->user()->company_id ?? null;

        // Filter documents FIRST (important!)
        $documents = Document::when($company_id, fn($q) => $q->where('company_id', $company_id))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {

                $query->where('search_field', 'like', "%{$search}%")
                    ->orWhere('filename', 'like', "%{$search}%");
                });
            })
            ->get();

        // --- BUILD TREE ONLY FROM FILTERED DOCUMENTS ---
        $tree = [];

        foreach ($documents as $doc) {

            // Convert filepath into folders
            $parts = explode('/', $doc->filepath);

            $ref = &$tree;

            foreach ($parts as $i => $part) {

                if ($i === count($parts) - 1) {
                    // Last part = file
                    $ref['__files'][] = [
                        'name' => $part,
                        'path' => $doc->filepath,
                        'original_name' => $doc->filename, // helpful for display
                    ];
                } else {
                    // Folder
                    if (!isset($ref[$part])) {
                        $ref[$part] = [];
                    }
                    $ref = &$ref[$part];
                }
            }
        }

        return view('client.documents', compact('tree', 'search'));
    }

    // DocumentController
    public function view(Document $document)
    {
        $path = storage_path('app/public/' . $document->file_path);
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if (!file_exists($path)) {
            abort(404);
        }

        $inlineTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp'];
        $headers = [];

        if (in_array(strtolower($ext), $inlineTypes)) {
            $headers['Content-Disposition'] = 'inline; filename="' . $document->file_name . '"';
        }

        return response()->file($path, $headers);
    }
}
