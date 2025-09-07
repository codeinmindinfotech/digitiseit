<?php 
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DocumentsImport; // optional for Excel import

class DocumentController extends Controller
{
    public function index(Request $request) {
        $company_id = $request->get('company_id');
        $documents = Document::when($company_id, fn($q) => $q->where('company_id', $company_id))
            ->get();
        $companies = Company::all();
        return view('documents.index', compact('documents', 'companies'));
    }

    public function uploadForm() {
        $companies = Company::all();
        return view('documents.upload', compact('companies'));
    }

    public function upload(Request $request) {
        $request->validate([
            'company_id' => 'nullable|string|max:255', // allow string for new company
            'directory_name' => 'required|string|max:255',
            'file' => 'required|file',
        ]);

        $companyId = $request->company_id;

        if (!is_numeric($companyId) && !empty($companyId)) {
            // Create new company
            $company = Company::create(['name' => $companyId]);
            $companyId = $company->id; // now numeric ID
        }

        $company = is_numeric($companyId) ? Company::find($companyId) : null;
        $dirName = $request->directory_name ?: ($company?->name ?? 'default');

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $dirPath = "uploads/{$dirName}";

        if (!Storage::disk('public')->exists($dirPath)) {
            Storage::disk('public')->makeDirectory($dirPath);
        }
        
        $filePath = $file->storeAs($dirPath, $fileName, 'public');

        // Optional: if Excel, import data
        if (in_array($file->getClientOriginalExtension(), ['xls','xlsx'])) {
            Excel::import(new DocumentsImport, $file);
        }

        Document::create([
            'company_id' => $company?->id,
            'directory' => $dirName,
            'filename' => $fileName,
            'filepath' => $filePath,
        ]);

        return redirect()->route('documents.index')->with('success','Document uploaded.');
    }

    public function clientView(Request $request) {
        $search = $request->get('search');
        $company_id = $request->get('company_id');

        // Fetch documents with optional search & company filter
        $documents = Document::when($company_id, fn($q) => $q->where('company_id', $company_id))
            ->when($search, fn($q) => $q->where('filename', 'like', "%{$search}%"))
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
