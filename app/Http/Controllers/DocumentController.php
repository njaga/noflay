<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents for a specific contract.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $contractId = $request->query('contract');
        $documents = Document::where('contract_id', $contractId)->get();

        return Inertia::render('Documents/Index', [
            'initialDocuments' => $documents,
            'contractId' => $contractId,
        ]);
    }

    /**
     * Store a newly created document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:10240', // max 10MB
                'document_type' => 'required|string',
                'contract_id' => 'required|exists:contracts,id',
            ]);

            $file = $request->file('file');
            $contract = Contract::findOrFail($request->contract_id);

            $fileName = Str::slug($contract->id . '-' . $request->document_type . '-' . now()->timestamp) . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('contract_documents', $fileName, 'public');

            $document = new Document([
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'document_type' => $request->document_type,
                'uploaded_by' => auth()->id(), // Add this line
            ]);

            $contract->documents()->save($document);

            return response()->json($document, 201);
        } catch (\Exception $e) {
            Log::error('Document upload failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Document upload failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download the specified document.
     *
     * @param  \App\Models\Document  $document
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        $filePath = Storage::disk('public')->path($document->file_path);

        return response()->download($filePath, $document->name);
    }

    /**
     * Remove the specified document from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Document $document)
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json(null, 204);
    }

    public function show(Document $document)
    {
        return Inertia::render('Documents/Show', ['document' => $document]);
    }
}
