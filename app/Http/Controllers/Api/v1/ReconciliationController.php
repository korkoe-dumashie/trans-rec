<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReconciliationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $transactionData = $request->validate([
        'system_trace_ids' => 'required|array',
        'system_trace_ids.*' => 'required|string'
    ]);

    $filePath = storage_path('app/reconciliation_data.csv');
    $file = fopen($filePath, 'w');

    // Header row
    fputcsv($file, ['System Trace ID']);

    // Data rows
    foreach ($transactionData['system_trace_ids'] as $id) {
        fputcsv($file, [$id]);
    }

    fclose($file);

    return response()->json([
        'message' => 'CSV file created successfully.',
        'file_path' => $filePath
    ]);
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
