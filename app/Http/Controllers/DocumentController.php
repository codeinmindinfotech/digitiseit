<?php 
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DocumentsImport; // optional for Excel import
use App\Models\ExcelDocuments;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $company_id = $request->get('company_id');
        
        // Get companies with document counts for each type (PDF, Excel, Word)
        $companies = Company::withCount('documents')->get();

        // Get all documents
        $documents = Document::when($company_id, 
        fn($q) => $q->where('company_id', $company_id))
            ->get();

        // Group documents by company and type
        $documentsGrouped = $documents->groupBy('company_id');

        return view('documents.index', compact('documentsGrouped', 'companies', 'company_id'));
    }


    public function uploadForm() {
        $companies = Company::all();
        return view('documents.upload', compact('companies'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'company_id' => 'nullable|string|max:255',
            'directory_name' => 'required|string|max:255',
            'files.*' => 'required|file', // allow multiple
        ]);

        $companyId = $request->company_id;

        if (!is_numeric($companyId) && !empty($companyId)) {
            $company = Company::create(['name' => $companyId]);
            $companyId = $company->id;
        }

        $company = is_numeric($companyId) ? Company::find($companyId) : null;
        $dirName = $request->directory_name ?: ($company?->name ?? 'default');

        foreach ($request->file('files') as $file) {
            $fileName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $dirPath = "uploads/{$dirName}";

            if (!Storage::disk('public')->exists($dirPath)) {
                Storage::disk('public')->makeDirectory($dirPath);
            }

            $filePath = $file->storeAs($dirPath, $fileName, 'public');

            // First, create the Document record
            $document = Document::create([
                'company_id' => $company?->id,
                'directory' => $dirName,
                'filename' => $fileName,
                'filepath' => $filePath,
            ]);

            // If Excel file, import its data and link to this document
            if (in_array($extension, ['xls', 'xlsx'])) {
                try {
                    Excel::import(new DocumentsImport($document->id), $file);
                } catch (\Exception $e) {
                    \Log::error('Excel import failed: ' . $e->getMessage());
                    continue;
                }
            }
        }

        return redirect()->route('documents.index')->with('success', 'Files uploaded successfully.');
    }

    public function clientView(Request $request, $company_id) {
        $search = $request->get('search');
        $company_id = base64_decode($company_id);

        // If company_id is not provided or invalid, return 404
        if (!$company_id || !Company::find($company_id)) {
            abort(404, 'You do not have access to this page');
        }

        $documents = Document::when($company_id, fn($q) => $q->where('company_id', $company_id))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    // Search excelDocument relation for search_field match
                    $query->whereHas('excelDocument', function ($excel) use ($search) {
                        $excel->where('search_field', 'like', "%{$search}%");
                    })
                    // OR fallback: search filename directly on Document model
                    ->orWhere('filename', 'like', "%{$search}%");
                });
            })
            ->get();

        // Fetch all companies for the filter dropdown
        $companies = Company::all();
        return view('client.documents', compact('documents','companies'));
    }

    // DocumentController
    public function view(Document $document)
    {
        $path = storage_path('app/public/' . $document->file_path);
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if (!file_exists($path)) {
            abort(404);
        }

        $inlineTypes = ['pdf','jpg','jpeg','png','gif','bmp'];
        $headers = [];

        if (in_array(strtolower($ext), $inlineTypes)) {
            $headers['Content-Disposition'] = 'inline; filename="' . $document->file_name . '"';
        }

        return response()->file($path, $headers);
    }

}
