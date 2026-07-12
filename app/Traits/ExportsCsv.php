<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

/**
 * Reusable CSV export trait.
 *
 * Usage:
 *   use App\Traits\ExportsCsv;
 *
 *   return $this->exportCsv($collection, ['Name', 'Email'], function($item) {
 *       return [$item->name, $item->email];
 *   }, 'filename.csv');
 */
trait ExportsCsv
{
    /**
     * Export a collection as a downloadable CSV file.
     *
     * @param Collection $data
     * @param array<string> $headers  Column headers
     * @param callable $rowCallback   fn($item): array — maps item to row values
     * @param string $filename       Default: export-{timestamp}.csv
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCsv(
        Collection $data,
        array $headers,
        callable $rowCallback,
        string $filename = null
    ) {
        $filename = $filename ?? 'export-' . now()->format('Y-m-d-His') . '.csv';

        $callback = function () use ($data, $headers, $rowCallback) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8 compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, $headers);

            // Data rows
            foreach ($data as $item) {
                $row = $rowCallback($item);
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
