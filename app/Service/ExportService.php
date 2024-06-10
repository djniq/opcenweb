<?php

namespace App\Service;

use Illuminate\Support\Facades\Response;

class ExportService
{
    public function export($filename, $tableHeaders, $content) {
        $csvFileName = $filename;
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, $tableHeaders);

        foreach ($content as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return Response::make('', 200, $headers);
    }
}