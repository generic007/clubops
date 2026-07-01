<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\ImportRow;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    protected AuditService $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function index()
    {
        $imports = Import::with('creator')->latest()->paginate(25);
        return view('imports.index', compact('imports'));
    }

    public function create()
    {
        return view('imports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:players,ledger,game_sessions,promotions,tickets',
            'file' => 'required|file|mimes:csv,txt,tsv|max:20480',
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->store('imports/' . date('Y/m'), 'local');

        // Read CSV
        $handle = fopen($file->getRealPath(), 'r');
        $rows = [];
        $headers = [];
        $rowNumber = 0;

        while (($data = fgetcsv($handle)) !== false) {
            $rowNumber++;
            if ($rowNumber === 1) {
                $headers = $data;
                continue;
            }

            $raw = count($headers) === count($data)
                ? array_combine($headers, $data)
                : ['_raw' => implode(',', $data)];

            $rows[] = [
                'import_id' => null, // Will be set after import creation
                'row_number' => $rowNumber - 1,
                'raw_data' => $raw,
                'status' => 'pending',
            ];
        }
        fclose($handle);

        $import = Import::create([
            'type' => $validated['type'],
            'filename' => $filename,
            'status' => 'pending',
            'total_rows' => count($rows),
            'accepted_rows' => 0,
            'skipped_rows' => 0,
            'flagged_rows' => 0,
            'created_by' => $request->user()->id,
        ]);

        // Create rows with correct import_id
        foreach ($rows as $rowData) {
            ImportRow::create([
                'import_id' => $import->id,
                'row_number' => $rowData['row_number'],
                'raw_data' => $rowData['raw_data'],
                'status' => 'pending',
            ]);
        }

        $import->update(['status' => 'processing']);

        $this->audit->log(
            $request->user(),
            'import_created',
            $import,
            null,
            ['type' => $validated['type'], 'rows' => count($rows)],
            "Import {$filename} ({$validated['type']}) - {$import->total_rows} rows"
        );

        return redirect()->route('imports.show', $import)
            ->with('success', "Import created with {$import->total_rows} rows. Review and accept rows.");
    }

    public function show(Import $import)
    {
        $import->load(['rows', 'creator']);
        return view('imports.show', compact('import'));
    }

    public function acceptRow(Request $request, Import $import, ImportRow $row)
    {
        if ($row->status !== 'pending') {
            return back()->with('error', 'Row already processed.');
        }

        $row->update(['status' => 'accepted']);
        $import->increment('accepted_rows');

        // Check if all rows processed
        $this->checkImportComplete($import);

        $this->audit->log(
            $request->user(),
            'import_row_accepted',
            $row,
            null,
            ['import_id' => $import->id, 'row_number' => $row->row_number],
            "Accepted row {$row->row_number} in import {$import->filename}"
        );

        return back()->with('success', "Row {$row->row_number} accepted.");
    }

    public function skipRow(Request $request, Import $import, ImportRow $row)
    {
        if ($row->status !== 'pending') {
            return back()->with('error', 'Row already processed.');
        }

        $row->update(['status' => 'skipped']);
        $import->increment('skipped_rows');

        $this->checkImportComplete($import);

        return back()->with('success', "Row {$row->row_number} skipped.");
    }

    protected function checkImportComplete(Import $import)
    {
        $pendingCount = $import->rows()->where('status', 'pending')->count();
        if ($pendingCount === 0) {
            $import->update(['status' => 'completed']);
        }
    }
}
